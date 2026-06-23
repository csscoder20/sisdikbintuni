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
use Filament\Actions\ViewAction;
use Illuminate\Support\Str;


class NotifikasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'release_note' => 'Rilis Note',
                        'general'      => 'Umum',
                        default        => $state,
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'release_note' => 'success',
                        default        => 'info',
                    }),
                TextColumn::make('recipient_type')
                    ->label('Penerima')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'all' => 'Semua Operator',
                        'schools' => 'Sekolah Tertentu',
                        'users' => 'Pengguna Tertentu',
                        default => $state,
                    })
                    ->badge()
                    ->color('info'),
                TextColumn::make('sender.name')
                    ->label('Pengirim'),
                TextColumn::make('content')
                    ->label('Isi Pemberitahuan')
                    ->formatStateUsing(function ($state) {
                        $plainText = strip_tags($state);
                        return Str::limit($plainText, 60);
                    }),
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
                    ViewAction::make()
                        ->modalWidth(\Filament\Support\Enums\Width::FourExtraLarge),
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
