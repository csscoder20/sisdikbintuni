<?php

namespace App\Filament\Resources\ActivityLog\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Enums\Size;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable(),
                TextColumn::make('event')
                    ->label('Aktivitas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'login' => 'success',
                        'logout' => 'warning',
                        'created' => 'info',
                        'updated' => 'primary',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('event')
                    ->label('Aktivitas')
                    ->options([
                        'login' => 'Login',
                        'logout' => 'Logout',
                        'created' => 'Ditambahkan',
                        'updated' => 'Diperbarui',
                        'deleted' => 'Dihapus',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                    ViewAction::make()
                        ->modalWidth(\Filament\Support\Enums\Width::FiveExtraLarge)
                        ->icon(Heroicon::OutlinedEye),
                    DeleteAction::make()
                        ->icon(Heroicon::OutlinedTrash),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('primary')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
