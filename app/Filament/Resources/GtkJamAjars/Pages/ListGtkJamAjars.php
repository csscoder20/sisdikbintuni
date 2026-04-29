<?php

namespace App\Filament\Resources\GtkJamAjars\Pages;

use App\Filament\Resources\GtkJamAjars\GtkJamAjarResource;
use App\Filament\Actions\ValidateChecklistAction;
use App\Models\Gtk;
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
            ValidateChecklistAction::make(
                'validateSebaranJam',
                'sebaran_jam',
                fn() => $this->allGtkTeachingHoursFulfilled(),
                'Sebaran Jam Belum Terpenuhi',
                'Masih ada GTK dengan kolom Keterangan "Belum Terpenuhi". Pastikan total jam setiap GTK minimal 24 sebelum melakukan validasi.',
            ),
        ];
    }

    protected function allGtkTeachingHoursFulfilled(): bool
    {
        $sekolahId = filament()->getTenant()?->id;

        if (blank($sekolahId)) {
            return false;
        }

        $gtks = Gtk::query()
            ->where('sekolah_id', $sekolahId)
            ->with('tugasTambahan')
            ->withSum(['mengajar as total_jam_mengajar' => function ($query): void {
                $query->where(function ($query): void {
                    $query
                        ->whereNotNull('rombel_id')
                        ->orWhereNotNull('mapel_id');
                });
            }], 'jumlah_jam')
            ->get();

        if ($gtks->isEmpty()) {
            return false;
        }

        return $gtks->every(function (Gtk $gtk): bool {
            $total = (int) ($gtk->total_jam_mengajar ?? 0) + (int) ($gtk->tugasTambahan?->jumlah_jam ?? 0);

            return $total >= 24;
        });
    }
}
