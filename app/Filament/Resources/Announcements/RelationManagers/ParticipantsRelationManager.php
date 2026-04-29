<?php

namespace App\Filament\Resources\Announcements\RelationManagers;

use App\Filament\Resources\Participants\ParticipantResource;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'participants';

    protected static ?string $relatedResource = ParticipantResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
                Action::make('importCsv')
                    ->label('Import CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        FileUpload::make('file')
                            ->label('File CSV')
                            ->required()
                            ->disk('local') // Simpan di local saja untuk diproses
                            ->visibility('private'),
                    ])
                    ->action(function (array $data) {
                        $path = storage_path('app/' . $data['file']);
                        
                        if (!file_exists($path)) {
                            Notification::make()
                                ->title('File tidak ditemukan')
                                ->danger()
                                ->send();
                            return;
                        }

                        $rows = array_map('str_getcsv', file($path));
                        $header = array_shift($rows);

                        $count = 0;
                        foreach ($rows as $row) {
                            if (count($header) !== count($row)) continue;
                            
                            $record = array_combine($header, $row);

                            $this->getOwnerRecord()->participants()->updateOrCreate(
                                ['nisn' => $record['nisn']],
                                [
                                    'nama' => $record['nama'],
                                    'kelas' => $record['kelas'],
                                    'keterangan' => $record['keterangan'],
                                ]
                            );
                            $count++;
                        }

                        Notification::make()
                            ->title($count . ' data berhasil diimport')
                            ->success()
                            ->send();
                        
                        unlink($path); // Hapus file setelah diproses
                    })
            ]);
    }
}
