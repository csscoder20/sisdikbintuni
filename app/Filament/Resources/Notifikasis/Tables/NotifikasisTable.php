<?php

namespace App\Filament\Resources\Notifikasis\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;


class NotifikasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable(),
                TextColumn::make('recipient_type')
                    ->label('Penerima')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'all' => 'Semua Operator',
                        'schools' => 'Sekolah Tertentu',
                        'users' => 'Pengguna Tertentu',
                        default => $state,
                    })
                    ->badge()
                    ->color('info'),
                TextColumn::make('sender.name')
                    ->label('Pengirim'),
                TextColumn::make('created_at')
                    ->label('Tanggal Kirim')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
    }
}
