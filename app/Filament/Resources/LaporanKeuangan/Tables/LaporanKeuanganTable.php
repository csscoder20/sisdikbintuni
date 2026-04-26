<?php

namespace App\Filament\Resources\LaporanKeuangan\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LaporanKeuanganTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('laporan.bulan')
                    ->label('Periode')
                    ->formatStateUsing(fn($record) => $record->laporan ? "Bulan {$record->laporan->bulan} / {$record->laporan->tahun}" : '-')
                    ->sortable(),
                TextColumn::make('sumber_dana')
                    ->label('Sumber Dana')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('penerimaan')
                    ->label('Penerimaan')
                    ->money('idr')
                    ->sortable(),
                TextColumn::make('pengeluaran')
                    ->label('Pengeluaran')
                    ->money('idr')
                    ->sortable(),
                TextColumn::make('saldo')
                    ->label('Saldo Akhir')
                    ->money('idr')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
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
                //
            ]);
    }
}
