<?php

namespace App\Filament\Resources\Siswas\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\ImportAction;
use App\Filament\Imports\SiswaImporter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->searchable(),
                TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable(),
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->searchable(),
                TextColumn::make('rombel.nama')
                    ->label('Rombel')
                    ->sortable()
                    ->badge(),
                TextColumn::make('status')
                    ->label('Status Siswa')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'aktif' => 'success',
                        'mutasi_masuk' => 'info',
                        'mutasi_keluar' => 'warning',
                        'lulus' => 'success',
                        'putus_sekolah' => 'danger',
                        'mengulang' => 'danger',
                        default => 'gray',
                    }),
                // TextColumn::make('tahun_masuk')
                //     ->label('Tahun Masuk')
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('agama')
                    ->label('Agama')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nokk')
                    ->label('Nomor KK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nobpjs')
                    ->label('Nomor BPJS')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('daerah_asal')
                    ->label('Daerah Asal')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('alamat')
                    ->label('Alamat Domisili')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('provinsi')
                    ->label('Provinsi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kabupaten')
                    ->label('Kabupaten')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kecamatan')
                    ->label('Kecamatan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('desa')
                    ->label('Desa/Kelurahan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nama_ayah')
                    ->label('Nama Ayah')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nama_ibu')
                    ->label('Nama Ibu')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nama_wali')
                    ->label('Nama Wali')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('disabilitas')
                    ->label('Jenis Disabilitas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('beasiswa')
                    ->label('Status Beasiswa')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->modalHeading(fn ($record): string => 'Lihat Data Siswa: ' . ($record->nama ?? '-'))
                        ->modalWidth(\Filament\Support\Enums\Width::FiveExtraLarge)
                        ->icon(Heroicon::OutlinedEye),
                    EditAction::make()
                        ->icon(Heroicon::OutlinedPencilSquare),
                    DeleteAction::make()
                        ->icon(Heroicon::OutlinedTrash),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('primary')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
