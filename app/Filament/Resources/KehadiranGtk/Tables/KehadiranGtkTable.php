<?php

namespace App\Filament\Resources\KehadiranGtk\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KehadiranGtkTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->columns([
                TextColumn::make('gtk.nama')
                    ->label('Nama GTK')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hari_kerja')
                    ->label('Hari Kerja')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('sakit')
                    ->label('Sakit')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('izin')
                    ->label('Izin')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('alfa')
                    ->label('Alpa')
                    ->sortable()
                    ->alignCenter(),
                // TextColumn::make('laporan.id')
                //     ->label('ID Laporan')
                //     ->sortable(),
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
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
