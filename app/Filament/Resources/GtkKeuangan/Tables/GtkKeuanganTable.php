<?php

namespace App\Filament\Resources\GtkKeuangan\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\ImportAction;
use App\Filament\Imports\GtkKeuanganImporter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GtkKeuanganTable
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
                TextColumn::make('nomor_rekening')
                    ->label('No. Rekening')
                    ->sortable(),
                TextColumn::make('nama_bank')
                    ->label('Nama Bank'),
                TextColumn::make('npwp')
                    ->label('NPWP')
                    ->sortable(),
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
