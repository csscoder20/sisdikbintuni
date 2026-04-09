<?php

namespace App\Filament\Resources\LaporanGtk\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class LaporanGtkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('laporan_id')
                    ->relationship('laporan', 'id')
                    ->required(),
                Select::make('gtk_id')
                    ->relationship('gtk', 'nama')
                    ->required(),
            ]);
    }
}
