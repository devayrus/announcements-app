<?php

namespace App\Filament\Resources\Participants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ParticipantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('announcement_id')
                    ->relationship('announcement', 'judul')
                    ->required(),
                TextInput::make('nisn')
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('kelas')
                    ->required(),
                TextInput::make('keterangan')
                    ->required(),
            ]);
    }
}
