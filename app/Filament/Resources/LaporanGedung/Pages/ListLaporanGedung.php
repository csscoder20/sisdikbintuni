<?php

namespace App\Filament\Resources\LaporanGedung\Pages;

use App\Filament\Resources\LaporanGedung\LaporanGedungResource;
use App\Filament\Actions\ValidateChecklistAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLaporanGedung extends ListRecords
{
    protected static string $resource = LaporanGedungResource::class;

    public function getLaporanStatus(string $type): bool
    {
        $sekolahId = filament()->getTenant()?->id;
        $laporan = \App\Models\Laporan::where([
            'sekolah_id' => $sekolahId,
            'bulan' => (int) date('m'),
            'tahun' => (int) date('Y'),
        ])->first();

        $column = "is_{$type}_valid";
        return $laporan ? (bool) $laporan->$column : false;
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Data')
                ->modalHeading('Tambah Laporan Gedung')
                ->modalSubmitActionLabel('Tambah Data')
                ->createAnother(false),
            ValidateChecklistAction::make('validateKondisiSarpras', 'kondisi_sarpras', fn() => \App\Models\LaporanGedung::whereHas('laporan', fn($q) => $q->where('sekolah_id', filament()->getTenant()?->id))->exists()),
        ];
    }
}
