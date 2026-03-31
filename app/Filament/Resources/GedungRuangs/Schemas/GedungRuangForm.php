<?php

namespace App\Filament\Resources\GedungRuangs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GedungRuangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_gedung_ruang')
                    ->required(),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('kondisi_baik')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('kondisi_rusak')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('status_kepemilikan'),
                TextInput::make('id_sekolah')
                    ->required()
                    ->numeric(),
            ]);
    }
}
