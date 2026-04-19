<?php

namespace App\Filament\Resources\GtkJamAjars\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class GtkJamAjarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->columns([
                TextColumn::make('gtk.nama')
                    ->label('GTK')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rombel.nama')
                    ->label('Rombel')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mapel.nama_mapel')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jumlah_jam')
                    ->label('Jam')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('semester')
                    ->label('Semester')
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                TextColumn::make('tahun_ajaran')
                    ->label('Tahun Ajaran'),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->modalWidth(\Filament\Support\Enums\Width::FiveExtraLarge)
                        ->icon(Heroicon::OutlinedEye),
                    EditAction::make()
                        ->icon(Heroicon::OutlinedPencilSquare),
                    DeleteAction::make()
                        ->icon(Heroicon::OutlinedTrash),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('primary')
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
