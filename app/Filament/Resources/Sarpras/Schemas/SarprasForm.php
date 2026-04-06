<?php

namespace App\Filament\Resources\Sarpras\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SarprasForm
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
                TextInput::make('baik')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('rusak')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('status_kepemilikan')
                    ->required()
                    ->default('Milik Sekolah'),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }
}
