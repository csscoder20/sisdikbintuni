<?php

namespace App\Filament\Resources\Gtks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GtksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nik')
                    ->searchable(),
                TextColumn::make('nip')
                    ->searchable(),
                TextColumn::make('nuptk')
                    ->searchable(),
                TextColumn::make('nama_gtk')
                    ->searchable(),
                TextColumn::make('tempat_lahir')
                    ->searchable(),
                TextColumn::make('tgl_lahir')
                    ->date()
                    ->sortable(),
                TextColumn::make('jenis_gtk')
                    ->searchable(),
                TextColumn::make('jenkel')
                    ->searchable(),
                TextColumn::make('agama')
                    ->searchable(),
                TextColumn::make('kategori_papua')
                    ->searchable(),
                TextColumn::make('pendidikan_terakhir')
                    ->searchable(),
                TextColumn::make('status_kepegawaian')
                    ->searchable(),
                TextColumn::make('golongan_pegawai')
                    ->searchable(),
                TextColumn::make('tmt_pegawai')
                    ->date()
                    ->sortable(),
                TextColumn::make('tgl_penempatan_sk_terakhir')
                    ->date()
                    ->sortable(),
                TextColumn::make('npwp')
                    ->searchable(),
                TextColumn::make('no_rekening')
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
