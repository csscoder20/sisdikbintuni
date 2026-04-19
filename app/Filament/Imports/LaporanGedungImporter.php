<?php

namespace App\Filament\Imports;

use App\Models\LaporanGedung;
use App\Models\Laporan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class LaporanGedungImporter extends Importer
{
    protected static ?string $model = LaporanGedung::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama_ruang')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('Ruang Kelas X-1'),
            ImportColumn::make('jumlah_total')
                ->numeric()
                ->rules(['required', 'integer', 'min:0'])
                ->example('1'),
            ImportColumn::make('jumlah_baik')
                ->numeric()
                ->rules(['required', 'integer', 'min:0'])
                ->example('1'),
            ImportColumn::make('jumlah_rusak')
                ->numeric()
                ->rules(['required', 'integer', 'min:0'])
                ->example('0'),
            ImportColumn::make('status_kepemilikan')
                ->rules(['required', 'string', 'in:milik,pinjam'])
                ->example('milik'),
        ];
    }

    public function resolveRecord(): LaporanGedung
    {
        $sekolahId = filament()->getTenant()?->id ?? $this->import->user->sekolah?->id;

        if (! $sekolahId) {
            throw new \Exception('Gagal mendeteksi data Sekolah. Pastikan Anda melakukan import di dalam panel sekolah yang benar.');
        }

        $month = (int) date('m');
        $year = (int) date('Y');

        // Ensure current month's report exists
        $laporan = Laporan::firstOrCreate([
            'sekolah_id' => $sekolahId,
            'bulan' => $month,
            'tahun' => $year,
        ]);

        return LaporanGedung::firstOrNew([
            'laporan_id' => $laporan->id,
            'nama_ruang' => $this->data['nama_ruang'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Impor data sarana/gedung selesai. ' . Number::format($import->successful_rows) . ' baris berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' baris gagal diimpor.';
        }

        return $body;
    }
}
