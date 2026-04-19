<?php

namespace App\Filament\Resources\GtkJamAjars\Pages;

use App\Filament\Resources\GtkJamAjars\GtkJamAjarResource;
use App\Filament\Actions\ValidateChecklistAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGtkJamAjars extends ListRecords
{
    protected static string $resource = GtkJamAjarResource::class;

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
                ->label('Tambah Sebaran Jam Ajar')
                ->modalHeading('Tambah Data Sebaran Jam Ajar')
                ->modalSubmitActionLabel('Simpan Sebaran Jam Ajar')
                ->createAnother(false),
            ValidateChecklistAction::make('validateSebaranJam', 'sebaran_jam', fn() => \App\Models\Mengajar::whereHas('gtk', fn($q) => $q->where('sekolah_id', filament()->getTenant()?->id))->exists()),
        ];
    }
}
