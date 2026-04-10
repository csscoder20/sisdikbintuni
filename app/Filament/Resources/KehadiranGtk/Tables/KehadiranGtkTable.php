<?php

namespace App\Filament\Resources\KehadiranGtk\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KehadiranGtkTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gtk.nama')
                    ->label('Nama GTK')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hari_kerja')
                    ->label('Hari Kerja')
                    ->sortable(),
                TextColumn::make('sakit')
                    ->label('Sakit')
                    ->sortable(),
                TextColumn::make('izin')
                    ->label('Izin')
                    ->sortable(),
                TextColumn::make('alfa')
                    ->label('Alpa')
                    ->sortable(),
                // TextColumn::make('laporan.id')
                //     ->label('ID Laporan')
                //     ->sortable(),
            ])

            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
