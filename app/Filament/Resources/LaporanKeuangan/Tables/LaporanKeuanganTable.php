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
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;



class LaporanKeuanganTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultSort('tanggal', 'asc')
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->searchable()
                    ->wrap()
                    ->grow(),
                TextColumn::make('debet')
                    ->label('Debit')
                    ->state(fn($record) => $record->jenis_transaksi === 'debit' ? $record->nominal : null)
                    ->money('idr')
                    ->alignment('center')
                    ->extraAttributes(['style' => 'min-width: 250px; padding: 0 1rem;'])
                    ->extraHeaderAttributes(['style' => 'min-width: 250px']),
                TextColumn::make('kredit')
                    ->label('Kredit')
                    ->state(fn($record) => $record->jenis_transaksi === 'kredit' ? $record->nominal : null)
                    ->money('idr')
                    ->alignment('center')
                    ->extraAttributes(['style' => 'min-width: 250px; padding: 0 1rem;'])
                    ->extraHeaderAttributes(['style' => 'min-width: 250px']),
                TextColumn::make('running_balance')
                    ->label('Saldo')
                    ->money('idr')
                    ->alignment('center')
                    ->extraAttributes(['style' => 'min-width: 250px; padding: 0 1rem;'])
                    ->extraHeaderAttributes(['style' => 'min-width: 250px'])
                    ->state(function ($record) {
                        $sekolahId = $record->laporan->sekolah_id ?? filament()->getTenant()?->id;

                        $query = \App\Models\LaporanKeuangan::query()
                            ->whereHas('laporan', fn($q) => $q->where('sekolah_id', $sekolahId))
                            ->where(function ($q) use ($record) {
                                $q->where('tanggal', '<', $record->tanggal)
                                    ->orWhere(function ($q2) use ($record) {
                                        $q2->where('tanggal', '=', $record->tanggal)
                                            ->where('id', '<=', $record->id);
                                    });
                            });

                        return $query->sum('saldo');
                    })
                    ->color(fn($state) => $state < 0 ? 'danger' : 'success')
                    ->weight('bold'),
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
                        ->modalWidth(\Filament\Support\Enums\Width::Medium)
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
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
