<?php

namespace App\Filament\Resources\GedungRuangs\Schemas;

use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Schema;

class GedungRuangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_gedung_ruang')
                    ->required()
                    ->maxLength(255),
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
                Select::make('status_kepemilikan')
                    ->options([
                        'milik' => 'Milik',
                        'pinjam' => 'Pinjam',
                    ]),
                Select::make('id_sekolah')
                    ->relationship('sekolah', 'nama_sekolah')
                    ->required(),
            ]);
    }
}
