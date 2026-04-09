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
                TextColumn::make('gapok')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('bruto')
                    ->money('IDR'),
                TextColumn::make('netto')
                    ->money('IDR')
                    ->sortable(),
            ]);
    }
}
