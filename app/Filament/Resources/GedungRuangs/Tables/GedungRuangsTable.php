<?php

namespace App\Filament\Resources\GedungRuangs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;

class GedungRuangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_gedung_ruang')
                    ->searchable(),
                TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kondisi_baik')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kondisi_rusak')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status_kepemilikan'),
                TextColumn::make('sekolah.nama_sekolah')
                    ->sortable(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
