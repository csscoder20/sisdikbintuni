<?php

namespace App\Filament\Resources\Sekolahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SekolahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_sekolah')
                    ->label('Nama Sekolah')
                    ->searchable(),
                TextColumn::make('npsn')
                    ->searchable(),
                TextColumn::make('nss')
                    ->searchable(),
                TextColumn::make('npwp')
                    ->searchable(),
                TextColumn::make('desa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kecamatan')
                    ->searchable(),
                TextColumn::make('kabupaten')
                    ->searchable(),
                TextColumn::make('provinsi')
                    ->searchable(),
                TextColumn::make('tahun_berdiri')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nomor_sk_pendirian')
                    ->searchable(),
                TextColumn::make('tgl_sk_pendirian')
                    ->date()
                    ->sortable(),
                TextColumn::make('status_sekolah')
                    ->searchable(),
                TextColumn::make('nama_penyelenggara_yayasan')
                    ->searchable(),
                TextColumn::make('sk_pendirian_yayasan')
                    ->searchable(),
                TextColumn::make('gedung_sekolah')
                    ->searchable(),
                TextColumn::make('akreditasi_sekolah')
                    ->searchable(),
                TextColumn::make('status_tanah_sekolah')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('luas_tanah_sekolah')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('email_sekolah')
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
