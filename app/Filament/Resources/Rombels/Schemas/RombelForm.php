<?php

namespace App\Filament\Resources\Rombels\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RombelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                Select::make('tingkat')
                    ->options(array_combine(range(1, 12), range(1, 12)))
                    ->required(),
            ]);
    }
}
