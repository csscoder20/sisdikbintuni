<?php

namespace App\Filament\Resources\LaporanGedung\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LaporanGedungTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('laporan.tahun')
                //     ->label('Tahun')
                //     ->sortable(),
                // TextColumn::make('laporan.bulan')
                //     ->label('Bulan')
                //     ->sortable(),
                TextColumn::make('nama_ruang')
                    ->searchable(),
                TextColumn::make('jumlah_total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jumlah_baik')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jumlah_rusak')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status_kepemilikan')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make(),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
