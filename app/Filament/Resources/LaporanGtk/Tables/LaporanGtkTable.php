<?php

namespace App\Filament\Resources\LaporanGtk\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LaporanGtkTable
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
                TextColumn::make('gtk.nama')
                    ->label('Nama GTK')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
