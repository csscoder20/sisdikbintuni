<?php

namespace App\Filament\Resources\LaporanGedung\Tables;

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


class LaporanGedungTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->striped()
            ->columns([
                // TextColumn::make('laporan.tahun')
                //     ->label('Tahun')
                //     ->sortable(),
                // TextColumn::make('laporan.bulan')
                //     ->label('Bulan')
                //     ->sortable(),
                TextColumn::make('nama_ruang')
                    ->label('Nama Ruang')
                    ->searchable(),
                TextColumn::make('jumlah_total')
                    ->label('Jumlah Total')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('jumlah_baik')
                    ->label('Jumlah Baik')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('jumlah_rusak')
                    ->label('Jumlah Rusak')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('status_kepemilikan')
                    ->label('Status Kepemilikan')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                        
                        // Default to the latest active Laporan for this school if no filter is selected
                        $sekolahId = filament()->getTenant()?->id ?? (auth()->check() ? auth()->user()->sekolah_id : null);
                        $latestLaporan = \App\Models\Laporan::where('sekolah_id', $sekolahId)
                            ->orderBy('tahun', 'desc')
                            ->orderBy('bulan', 'desc')
                            ->first();
                            
                        if ($latestLaporan) {
                            return $query->where('laporan_id', $latestLaporan->id);
                        }
                        return $query;
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
}
