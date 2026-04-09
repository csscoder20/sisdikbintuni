<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GtkRiwayatPendidikansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gtk.nama')
                    ->label('GTK')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('thn_tamat_sma')
                    ->label('Tamat SMA')
                    ->sortable(),
                TextColumn::make('jurusan_s1')
                    ->label('Jurusan S1')
                    ->searchable(),
                TextColumn::make('perguruan_tinggi_s1')
                    ->label('PT S1')
                    ->searchable(),
                TextColumn::make('gelar_akademik')
                    ->label('Gelar')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
