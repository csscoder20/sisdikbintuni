<?php

namespace App\Filament\Resources\KehadiranGtk\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;


class KehadiranGtkTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->striped()
            ->defaultSort('id', 'asc')
            ->modifyQueryUsing(fn ($query) => $query->with('gtk.pendidikan'))
            ->columns([
                TextColumn::make('gtk.nama')
                    ->label('Nama GTK')
                    ->formatStateUsing(fn ($state, $record): string => self::formatGtkName($record->gtk, $state))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('hadir')
                    ->label('Hadir')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('hari_kerja')
                    ->label('Hari Kerja')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('sakit')
                    ->label('Sakit')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('izin')
                    ->label('Izin')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('alfa')
                    ->label('Alpa')
                    ->sortable()
                    ->alignCenter(),
                // TextColumn::make('laporan.id')
                //     ->label('ID Laporan')
                //     ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('laporan_id')
                    ->label('Pilih Periode')
                    ->options(function () {
                        $sekolahId = filament()->getTenant()?->id ?? (auth()->check() ? auth()->user()->sekolah_id : null);
                        return \App\Models\Laporan::where('sekolah_id', $sekolahId)
                            ->orderBy('tahun', 'desc')
                            ->orderBy('bulan', 'desc')
                            ->get()
                            ->mapWithKeys(function ($laporan) {
                                $namaBulan = \Carbon\Carbon::create()->month($laporan->bulan)->translatedFormat('F');
                                return [$laporan->id => "{$namaBulan} {$laporan->tahun}"];
                            });
                    })
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        if (!empty($data['value'])) {
                            return $query->where('laporan_id', $data['value']);
                        }
                        return $query->whereNull('laporan_id');
                    }),
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

    protected static function formatGtkName($gtk, ?string $nama = null): string
    {
        $nama = trim((string) ($nama ?? $gtk?->nama ?? ''));
        $pendidikan = $gtk?->pendidikan->first();
        $gelarDepan = trim((string) ($pendidikan?->gelar_depan ?? ''));
        $gelarBelakang = trim((string) ($pendidikan?->gelar_belakang ?? ''));

        return trim(($gelarDepan ? $gelarDepan . ' ' : '') . $nama . ($gelarBelakang ? ', ' . $gelarBelakang : ''));
    }
}
