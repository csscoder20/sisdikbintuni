<?php

namespace App\Filament\Resources\PeriodeLaporans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PeriodeLaporanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tahun')
                    ->required()
                    ->numeric(),
                TextInput::make('bulan')
                    ->required()
                    ->numeric(),
            ]);
    }
}
