<?php

namespace App\Filament\Imports;

use App\Models\Laporan;
use App\Models\LaporanKeuangan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Carbon\Carbon;

class LaporanKeuanganImporter extends Importer
{
    protected static ?string $model = LaporanKeuangan::class;

    protected static function parseFlexibleDate($state): ?string
    {
        if (blank($state)) {
            return null;
        }

        if ($state instanceof \DateTimeInterface) {
            return $state->format('Y-m-d');
        }

        try {
            $state = trim((string) $state);
            if ($state === '') {
                return null;
            }

            if (is_numeric($state) && (float) $state >= 20000 && (float) $state <= 60000) {
                return Carbon::create(1899, 12, 30)
                    ->addDays((int) $state)
                    ->format('Y-m-d');
            }

            $months = [
                'januari' => 'January',
                'februari' => 'February',
                'maret' => 'March',
                'april' => 'April',
                'mei' => 'May',
                'juni' => 'June',
                'juli' => 'July',
                'agustus' => 'August',
                'september' => 'September',
                'oktober' => 'October',
                'november' => 'November',
                'desember' => 'December',
            ];

            $normalizedState = strtolower($state);
            foreach ($months as $indo => $eng) {
                if (str_contains($normalizedState, $indo)) {
                    $normalizedState = str_replace($indo, $eng, $normalizedState);
                    break;
                }
            }

            if (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{2,4})$/', $normalizedState, $matches)) {
                $day = (int) $matches[1];
                $month = (int) $matches[2];
                $year = (int) $matches[3];

                if ($year < 100) {
                    $year += 2000;
                }

                if (checkdate($month, $day, $year)) {
                    return sprintf('%04d-%02d-%02d', $year, $month, $day);
                }

                if (checkdate($day, $month, $year)) {
                    return sprintf('%04d-%02d-%02d', $year, $day, $month);
                }
            }

            $date = Carbon::parse($normalizedState);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    protected static function parseNominal($state): ?float
    {
        if (blank($state)) {
            return null;
        }

        if (is_int($state) || is_float($state)) {
            return (float) $state;
        }

        $value = trim((string) $state);
        if ($value === '') {
            return null;
        }

        if (str_contains($value, ',')) {
            $value = str_replace(['.', ','], ['', '.'], $value);
        } else {
            $value = str_replace('.', '', $value);
        }

        return is_numeric($value) ? (float) $value : null;
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('tanggal')
                ->label('Tanggal')
                ->rules(['required'])
                ->example('01/05/2026')
                ->guess(['tgl', 'date'])
                ->castStateUsing(fn($state) => static::parseFlexibleDate($state)),
            ImportColumn::make('jenis_transaksi')
                ->label('Jenis Transaksi')
                ->rules(['required'])
                ->example('Debit')
                ->guess(['transaksi', 'jenis'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }

                    $value = strtolower(trim((string) $state));
                    if (str_contains($value, 'debit') || str_contains($value, 'masuk')) {
                        return 'debit';
                    }

                    if (str_contains($value, 'kredit') || str_contains($value, 'keluar')) {
                        return 'kredit';
                    }

                    return null;
                }),
            ImportColumn::make('keterangan')
                ->label('Keterangan')
                ->rules(['required'])
                ->example('Pembayaran listrik')
                ->castStateUsing(fn($state) => blank($state) ? null : trim((string) $state)),
            ImportColumn::make('nominal')
                ->label('Nominal')
                ->rules(['required'])
                ->example('500000')
                ->guess(['jumlah', 'saldo'])
                ->castStateUsing(fn($state) => static::parseNominal($state)),
        ];
    }

    public function resolveRecord(): ?LaporanKeuangan
    {
        $keterangan = (string) ($this->data['keterangan'] ?? '');
        if (
            str_contains(strtolower($keterangan), 'diisi') ||
            str_contains(strtolower($keterangan), 'contoh') ||
            str_contains(strtolower($keterangan), 'format')
        ) {
            return null;
        }

        $tanggal = $this->data['tanggal'] ?? null;
        if (blank($tanggal)) {
            return null;
        }

        $sekolahId = $this->options['dinas_selected_sekolah_id'] ?? (Filament::getTenant()?->id ?? $this->import->user->sekolah?->id);
        if (!$sekolahId) {
            throw new \Exception('Gagal mendeteksi data Sekolah. Pastikan Anda mengimpor dari panel sekolah yang benar.');
        }

        $laporanId = $this->getLaporanIdFromDate($tanggal, $sekolahId);
        if (!$laporanId) {
            throw new \Exception('Gagal menentukan laporan keuangan berdasarkan tanggal. Pastikan format tanggal benar.');
        }

        $record = LaporanKeuangan::where('laporan_id', $laporanId)
            ->where('tanggal', $tanggal)
            ->where('jenis_transaksi', $this->data['jenis_transaksi'] ?? '')
            ->where('keterangan', $keterangan)
            ->first();

        if ($record) {
            return $record;
        }

        $record = new LaporanKeuangan();
        $record->laporan_id = $laporanId;

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $message = 'Berhasil mengimpor ' . number_format($import->successful_rows) . ' baris.';

        if ($import->getFailedRowsCount() > 0) {
            $message .= ' Gagal mengimpor ' . number_format($import->getFailedRowsCount()) . ' baris.';
        }

        return $message;
    }

    protected function getLaporanIdFromDate(string $tanggal, int $sekolahId): ?int
    {
        try {
            $date = Carbon::parse($tanggal);
        } catch (\Exception $e) {
            return null;
        }

        return Laporan::firstOrCreate([
            'sekolah_id' => $sekolahId,
            'bulan' => $date->month,
            'tahun' => $date->year,
        ])->id;
    }
}
