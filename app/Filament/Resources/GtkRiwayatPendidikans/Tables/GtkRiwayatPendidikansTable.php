<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GtkRiwayatPendidikansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_gtk')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('thn_tamat_sd')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('thn_tamat_smp')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('thn_tamat_sma')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('thn_tamat_d1')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jurusan_d1')
                    ->searchable(),
                TextColumn::make('thn_tamat_d2')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jurusan_d2')
                    ->searchable(),
                TextColumn::make('thn_tamat_d3')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jurusan_d3')
                    ->searchable(),
                TextColumn::make('thn_tamat_s1')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jurusan_s1')
                    ->searchable(),
                TextColumn::make('thn_tamat_s2')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jurusan_s2')
                    ->searchable(),
                TextColumn::make('thn_tamat_s3')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jurusan_s3')
                    ->searchable(),
                TextColumn::make('thn_akta_1')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jurusan_akta_1')
                    ->searchable(),
                TextColumn::make('thn_akta_2')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jurusan_akta_2')
                    ->searchable(),
                TextColumn::make('thn_akta_3')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jurusan_akta_3')
                    ->searchable(),
                TextColumn::make('thn_akta_4')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jurusan_akta_4')
                    ->searchable(),
                TextColumn::make('nama_perguruan_tinggi')
                    ->searchable(),
                TextColumn::make('gelar_akademik')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
