<?php

namespace App\Filament\Resources\LaporanKeuangan\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;


class LaporanKeuanganTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                TextColumn::make('laporan.bulan')
                    ->label('Periode')
                    ->formatStateUsing(fn($record) => $record->laporan ? "Bulan {$record->laporan->bulan} / {$record->laporan->tahun}" : '-')
                    ->sortable(),
                TextColumn::make('sumber_dana')
                    ->label('Sumber Dana')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('penerimaan')
                    ->label('Penerimaan')
                    ->money('idr')
                    ->sortable(),
                TextColumn::make('pengeluaran')
                    ->label('Pengeluaran')
                    ->money('idr')
                    ->sortable(),
                TextColumn::make('saldo')
                    ->label('Saldo Akhir')
                    ->money('idr')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d M Y H:i')
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
                    ->color('primary')
            ])
            ->toolbarActions([
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
                //
            ]);
    }
}
