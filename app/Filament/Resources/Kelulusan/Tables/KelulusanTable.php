<?php

namespace App\Filament\Resources\Kelulusan\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;


class KelulusanTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->columns([
                TextColumn::make('tahun')
                    ->label('Tahun Lulus')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('jumlah_peserta_ujian')
                    ->label('Peserta Ujian')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('jumlah_lulus')
                    ->label('Lulus')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('persentase_kelulusan')
                    ->label('Persentase Kelulusan')
                    ->suffix('%')
                    ->alignCenter(),
                TextColumn::make('jumlah_lanjut_pt')
                    ->label('Lanjut PT')
                    ->alignCenter(),
                TextColumn::make('sekolah.nama')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([

                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                    ViewAction::make()
                        ->modalWidth(\Filament\Support\Enums\Width::FiveExtraLarge)
                        ->icon(Heroicon::OutlinedEye),
                    EditAction::make()
                        ->icon(Heroicon::OutlinedPencilSquare),
                    DeleteAction::make()
                        ->icon(Heroicon::OutlinedTrash),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('primary'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ])
            ;
    }
}
