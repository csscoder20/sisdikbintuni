<?php

namespace App\Filament\Resources\Notifikasis\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
            ->actions([
                // Read-only history
            ])
            ->bulkActions([
                //
            ]);
    }
}
