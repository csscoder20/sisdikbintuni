<?php

namespace App\Filament\Resources\GtkKeuangan\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class GtkKeuangansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->striped()
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama GTK')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_bank_gaji')
                    ->label('Bank Gaji')
                    ->searchable(),
                TextColumn::make('no_rek_gaji')
                    ->label('No. Rek. Gaji')
                    ->searchable(),
                TextColumn::make('nama_bank_tunjangan')
                    ->label('Bank Tunjangan')
                    ->searchable(),
                TextColumn::make('no_rek_tunjangan')
                    ->label('No. Rek. Tunjangan')
                    ->searchable(),
                TextColumn::make('npwp')
                    ->label('NPWP')
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                    EditAction::make()
                        ->modalWidth('lg')
                        ->modalHeading('Edit Rekening & NPWP GTK')
                        ->icon(Heroicon::OutlinedPencilSquare),
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
            ->emptyStateHeading('Belum ada data GTK')
            ->emptyStateDescription('Data GTK akan otomatis terambil dari menu Nominatif GTK.');
    }
}
