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

    private static array $seenRuang = [];

    public static function resetDuplicateTracker(): void
    {
        self::$seenRuang = [];
    }

    public function resolveRecord(): ?LaporanGedung
    {
        // Skip if this is the instruction or example row from the template
        $namaRuang = $this->data['nama_ruang'] ?? '';
        if (str_contains(strtolower($namaRuang), 'diisi dengan') || str_contains($namaRuang, 'Ruang Kelas X-1')) {
            return null;
        }

        $sekolahId = $this->options['sekolah_id'] ?? (filament()->getTenant()?->id ?? $this->import->user->sekolah?->id);

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

        $namaRuang = trim((string) ($this->data['nama_ruang'] ?? ''));
        $key = strtolower($namaRuang) . '||' . $laporan->id;

        if (isset(self::$seenRuang[$key])) {
            throw new \Exception("Duplikat dalam file: Ruang \"{$namaRuang}\" muncul lebih dari sekali.");
        }
        self::$seenRuang[$key] = true;

        if (LaporanGedung::where('laporan_id', $laporan->id)->where('nama_ruang', $namaRuang)->exists()) {
            throw new \Exception("Sudah ada: Ruang \"{$namaRuang}\" sudah terdaftar pada laporan bulan ini.");
        }

        return new LaporanGedung([
            'laporan_id' => $laporan->id,
            'nama_ruang' => $namaRuang,
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
