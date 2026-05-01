<?php

namespace App\Filament\Resources\Gtks\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\ImportAction;
use App\Filament\Imports\GtkImporter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;


class GtksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->defaultSort('id', 'asc')
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama GTK')
                    ->searchable(),
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable(),
                TextColumn::make('nokarpeg')
                    ->label('No Karpeg')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nuptk')
                    ->label('NUPTK')
                    ->searchable(),
                TextColumn::make('jenis_gtk')
                    ->label('Jenis GTK')
                    ->searchable(),
                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->searchable(),
                TextColumn::make('status_kepegawaian')
                    ->label('Status Kepegawaian')
                    ->searchable(),
                TextColumn::make('pangkat_gol_terakhir')
                    ->label('Pangkat Gol Terakhir')
                    ->searchable(),
                TextColumn::make('tmt_pns')
                    ->label('TMT PNS')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pendidikan_terakhir')
                    ->label('Pendidikan Terakhir')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('daerah_asal')
                    ->label('Daerah Asal')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('agama')
                    ->label('Agama')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('desa')
                    ->label('Desa')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kecamatan')
                    ->label('Kecamatan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kabupaten')
                    ->label('Kabupaten')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('provinsi')
                    ->label('Provinsi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tmt_pangkat_gol_terakhir')
                    ->label('TMT Pangkat Gol')
                    ->date('d/m/Y')
                    ->sortable()
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
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                    ViewAction::make()
                        ->modalWidth(\Filament\Support\Enums\Width::FiveExtraLarge)
                        ->icon(Heroicon::OutlinedEye),
                    EditAction::make()
                        ->icon(Heroicon::OutlinedPencilSquare),
                    DeleteAction::make()
                        ->icon(Heroicon::OutlinedTrash),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('primary'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ])
            ;
    }
}
