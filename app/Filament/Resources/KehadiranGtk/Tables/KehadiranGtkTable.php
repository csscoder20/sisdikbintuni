<?php

namespace App\Filament\Resources\KehadiranGtk\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KehadiranGtkTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gtk.nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hari_kerja')
                    ->sortable(),
                TextColumn::make('sakit'),
                TextColumn::make('izin'),
                TextColumn::make('alfa'),
                TextColumn::make('laporan.id')
                    ->label('ID Laporan')
                    ->sortable(),
            ]);
    }
}
