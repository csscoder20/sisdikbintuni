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
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('jumlah_total')
                ->numeric()
                ->rules(['required', 'integer', 'min:0']),
            ImportColumn::make('jumlah_baik')
                ->numeric()
                ->rules(['required', 'integer', 'min:0']),
            ImportColumn::make('jumlah_rusak')
                ->numeric()
                ->rules(['required', 'integer', 'min:0']),
            ImportColumn::make('status_kepemilikan')
                ->rules(['required', 'string', 'in:milik,pinjam']),
        ];
    }

    public function resolveRecord(): LaporanGedung
    {
        $sekolahId = filament()->getTenant()?->id;
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
        $body = 'Impor data keadaan gedung telah selesai dan ' . Number::format($import->successful_rows) . ' ' . str('baris')->plural($import->successful_rows) . ' berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal diimpor.';
        }

        return $body;
    }
}
