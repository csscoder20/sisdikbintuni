<?php

namespace App\Filament\Resources\Siswas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nik')
                    ->searchable(),
                TextColumn::make('nisn')
                    ->searchable(),
                TextColumn::make('no_bpjs')
                    ->searchable(),
                TextColumn::make('nama_siswa')
                    ->searchable(),
                TextColumn::make('tempat_lahir')
                    ->searchable(),
                TextColumn::make('tgl_lahir')
                    ->date()
                    ->sortable(),
                TextColumn::make('jenkel')
                    ->searchable(),
                TextColumn::make('agama')
                    ->searchable(),
                TextColumn::make('kategori_papua')
                    ->searchable(),
                TextColumn::make('disabilitas')
                    ->searchable(),
                TextColumn::make('penerima_beasiswa')
                    ->searchable(),
                TextColumn::make('id_rombel')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nama_ayah')
                    ->searchable(),
                TextColumn::make('nama_ibu')
                    ->searchable(),
                TextColumn::make('nama_wali')
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
