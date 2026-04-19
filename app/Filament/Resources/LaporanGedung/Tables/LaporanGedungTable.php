<?php

namespace App\Filament\Resources\LaporanGedung\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LaporanGedungTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->columns([
                // TextColumn::make('laporan.tahun')
                //     ->label('Tahun')
                //     ->sortable(),
                // TextColumn::make('laporan.bulan')
                //     ->label('Bulan')
                //     ->sortable(),
                TextColumn::make('nama_ruang')
                    ->label('Nama Ruang')
                    ->searchable(),
                TextColumn::make('jumlah_total')
                    ->label('Jumlah Total')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('jumlah_baik')
                    ->label('Jumlah Baik')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('jumlah_rusak')
                    ->label('Jumlah Rusak')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('status_kepemilikan')
                    ->label('Status Kepemilikan')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
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
