<?php

namespace App\Filament\Resources\GtkKeuangan\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GtkKeuanganTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gtk.nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nomor_rekening')
                ->label('No. Rekening')
                    ->sortable(),
                TextColumn::make('nama_bank')
                ->label('Nama Bank'),
                TextColumn::make('npwp')
                ->label('NPWP')
                    ->sortable(),
            ]);
    }
}


