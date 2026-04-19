<?php

namespace App\Filament\Resources\LaporanSiswa\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LaporanSiswaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->columns([
                TextColumn::make('laporan.tahun')
                    ->label('Tahun')
                    ->sortable(),
                TextColumn::make('laporan.bulan')
                    ->label('Bulan')
                    ->sortable(),
                TextColumn::make('rombel.nama')
                    ->label('Rombel')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->modalWidth(\Filament\Support\Enums\Width::FiveExtraLarge)
                        ->icon(Heroicon::OutlinedEye),
                    EditAction::make()
                        ->icon(Heroicon::OutlinedPencilSquare),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('primary')
            ]);
    }
}
