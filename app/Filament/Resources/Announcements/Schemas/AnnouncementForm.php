<?php

namespace App\Filament\Resources\Announcements\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Umum')
                    ->columns(2)
                    ->schema([
                        TextInput::make('judul')
                            ->required(),
                        DateTimePicker::make('tanggal_buka')
                            ->timezone(request()->cookie('browser_timezone') ?? 'UTC')
                            ->required(),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->required()
                            ->default('draft'),
                        Textarea::make('deskripsi')
                            ->columnSpanFull(),
                    ]),

                Section::make('Kustomisasi Hasil Kelulusan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('judul_lulus')
                            ->default('Selamat!')
                            ->required(),
                        TextInput::make('judul_tidak_lulus')
                            ->default('Informasi Hasil')
                            ->required(),
                        Textarea::make('pesan_lulus')
                            ->placeholder('Contoh: Berdasarkan keputusan rapat Dewan Guru...')
                            ->columnSpanFull(),
                        Textarea::make('pesan_tidak_lulus')
                            ->placeholder('Contoh: Mohon maaf, berdasarkan hasil evaluasi...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
