<?php

namespace App\Filament\Resources\Kelulusan\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KelulusanTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tahun')
                    ->sortable(),
                TextColumn::make('jumlah_lulus')
                    ->sortable(),
                TextColumn::make('persentase_kelulusan')
                    ->suffix('%'),
                TextColumn::make('jumlah_lanjut_pt')
                    ->label('Lanjut PT'),
                TextColumn::make('sekolah.nama')
                    ->sortable(),
            ]);
    }
}
