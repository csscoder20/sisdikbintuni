<?php

namespace App\Filament\Resources\GtkKeuangan\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GtkKeuanganTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gtk.nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nomor_rekening')
                    ->label('No. Rekening')
                    ->sortable(),
                TextColumn::make('nama_bank')
                    ->label('Nama Bank'),
                TextColumn::make('npwp')
                    ->label('NPWP')
                    ->sortable(),
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
