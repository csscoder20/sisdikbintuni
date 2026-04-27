<?php

namespace App\Filament\Resources\Rombels\Tables;

use Filament\Actions\Action;
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


class RombelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->poll('5s')
            ->columns([
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('tingkat')
                    ->sortable(),
                TextColumn::make('sekolah.nama')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                    Action::make('assignSiswa')
                        ->label('Kelola Rombel')
                        ->icon('heroicon-o-user-plus')
                        ->color('success')
                        ->url(fn (\App\Models\Rombel $record): string => \App\Filament\Resources\Rombels\RombelResource::getUrl('assign-siswa', ['record' => $record])),
                    ViewAction::make()
                        ->modalWidth(\Filament\Support\Enums\Width::Medium)
                        ->icon(Heroicon::OutlinedEye),
                    EditAction::make()
                        ->modalWidth(\Filament\Support\Enums\Width::Medium)
                        ->successNotificationTitle('Data berhasil disimpan')
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
                ])
            ]);
    }
}
