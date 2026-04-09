<?php

namespace App\Filament\Resources\LaporanSiswa\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class LaporanSiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('laporan_id')
                    ->relationship('laporan', 'id')
                    ->required(),
                Select::make('rombel_id')
                    ->relationship('rombel', 'nama')
                    ->required(),
            ]);
    }
}
