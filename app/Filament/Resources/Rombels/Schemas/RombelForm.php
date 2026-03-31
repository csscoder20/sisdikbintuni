<?php

namespace App\Filament\Resources\Rombels\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RombelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_rombel')
                    ->required(),
                TextInput::make('id_sekolah')
                    ->required()
                    ->numeric(),
            ]);
    }
}
