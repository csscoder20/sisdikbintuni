<?php

namespace App\Filament\Resources\GtkJamAjars\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GtkJamAjarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gtk.nama')
                    ->label('GTK')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rombel.nama')
                    ->label('Rombel')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mata_pelajaran')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jumlah_jam')
                    ->label('Jam')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('semester')
                    ->label('Semester')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('tahun_ajaran')
                    ->label('Tahun Ajaran'),
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
