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
                Action::make('downloadTemplate')
                    ->label('Download Template')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->action(function () {
                        $headers = ['nisn', 'nama', 'kelas', 'keterangan'];
                        $callback = function () use ($headers) {
                            $file = fopen('php://output', 'w');
                            fputcsv($file, $headers);
                            fputcsv($file, ['1234567890', 'Ahmad Siswa', 'XII MIPA 1', 'LULUS']);
                            fclose($file);
                        };
                        return response()->streamDownload($callback, 'template_impor_peserta.csv');
                    }),
                Action::make('exportCsv')
                    ->label('Export Data')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        $participants = $this->getOwnerRecord()->participants;
                        $headers = ['nisn', 'nama', 'kelas', 'keterangan'];
                        
                        $callback = function () use ($participants, $headers) {
                            $file = fopen('php://output', 'w');
                            fputcsv($file, $headers);
                            
                            foreach ($participants as $participant) {
                                fputcsv($file, [
                                    $participant->nisn,
                                    $participant->nama,
                                    $participant->kelas,
                                    $participant->keterangan,
                                ]);
                            }
                            fclose($file);
                        };
                        
                        $filename = 'data_peserta_' . \Illuminate\Support\Str::slug($this->getOwnerRecord()->judul) . '.csv';
                        return response()->streamDownload($callback, $filename);
                    }),
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

                        $handle = fopen($path, 'r');
                        
                        // Deteksi delimiter (koma atau titik koma)
                        $firstLine = fgets($handle);
                        $separator = (str_contains($firstLine, ';') && !str_contains($firstLine, ',')) ? ';' : ',';
                        rewind($handle);

                        // Ambil header dan bersihkan
                        $header = fgetcsv($handle, 0, $separator);
                        if ($header) {
                            // Hapus BOM UTF-8 jika ada pada elemen pertama
                            $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
                            $header = array_map(fn($h) => strtolower(trim($h)), $header);
                        }

                        $count = 0;
                        while (($row = fgetcsv($handle, 0, $separator)) !== false) {
                            // Skip jika baris kosong
                            if (count($row) === 1 && empty($row[0])) continue;
                            
                            if (count($header) !== count($row)) continue;
                            
                            $record = array_combine($header, $row);
                            $record = array_map('trim', $record);

                            // Cari key nisn secara case-insensitive (sudah dilowercase di atas)
                            $nisn = $record['nisn'] ?? null;

                            if (!$nisn) continue;

                            $this->getOwnerRecord()->participants()->updateOrCreate(
                                ['nisn' => $nisn],
                                [
                                    'nama' => $record['nama'] ?? $record['name'] ?? '',
                                    'kelas' => $record['kelas'] ?? $record['class'] ?? '',
                                    'keterangan' => $record['keterangan'] ?? $record['info'] ?? '',
                                ]
                            );
                            $count++;
                        }
                        fclose($handle);

                        Notification::make()
                            ->title($count . ' data berhasil diimport')
                            ->success()
                            ->send();
                        
                        unlink($path); // Hapus file setelah diproses
                    })
            ]);
    }
}
