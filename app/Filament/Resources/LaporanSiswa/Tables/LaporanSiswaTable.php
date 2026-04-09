<?php

namespace App\Filament\Resources\LaporanSiswa\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LaporanSiswaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('laporan.tahun')
                    ->label('Tahun')
                    ->sortable(),
                TextColumn::make('laporan.bulan')
                    ->label('Bulan')
                    ->sortable(),
                TextColumn::make('rombel.nama')
                    ->label('Rombel')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
