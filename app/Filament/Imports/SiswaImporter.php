<?php

namespace App\Filament\Imports;

use App\Models\Siswa;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class SiswaImporter extends Importer
{
    protected static ?string $model = Siswa::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('Budi Santoso'),
            ImportColumn::make('nisn')
                ->rules(['string', 'max:255', 'unique:siswa,nisn'])
                ->example('1234567890'),
            ImportColumn::make('nik')
                ->rules(['string', 'max:255', 'unique:siswa,nik'])
                ->example('3201010101010001'),
            ImportColumn::make('tempat_lahir')
                ->rules(['string', 'max:255'])
                ->example('Bandung'),
            ImportColumn::make('tanggal_lahir')
                ->rules(['nullable', 'date'])
                ->example('20/08/2011')
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) return null;
                    if ($state instanceof \DateTimeInterface) {
                        return $state->format('Y-m-d');
                    }
                    try {
                        $stateString = trim((string) $state);
                        if (empty($stateString)) return null;

                        // Handle d/m/Y or d-m-Y even with 2 digit years
                        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2,4})$/', $stateString, $matches)) {
                            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                            $year = $matches[3];
                            if (strlen($year) === 2) $year = '20' . $year;
                            return "{$year}-{$month}-{$day}";
                        }
                        return \Illuminate\Support\Carbon::parse($stateString)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return null;
                    }
                }),
            ImportColumn::make('jenis_kelamin')
                ->rules(['string', 'max:255'])
                ->example('Laki-laki')
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) return null;
                    $state = strtolower(trim((string)$state));
                    if ($state === 'l' || str_contains($state, 'laki')) return 'Laki-laki';
                    if ($state === 'p' || str_contains($state, 'perempuan')) return 'Perempuan';
                    return null;
                }),
            ImportColumn::make('agama')
                ->rules(['string', 'max:255'])
                ->example('Islam')
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) return null;
                    $state = trim((string)$state);
                    $valid = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
                    foreach ($valid as $v) {
                        if (stripos($v, $state) !== false || stripos($state, $v) !== false) return $v;
                    }
                    return null;
                }),
            ImportColumn::make('nokk')
                ->rules(['string', 'max:16'])
                ->example('3201010101010002'),
            ImportColumn::make('nobpjs')
                ->rules(['string', 'max:20', 'unique:siswa,nobpjs'])
                ->example('0001234567890'),
            ImportColumn::make('daerah_asal')
                ->rules(['string', 'max:255'])
                ->example('Non Papua')
                ->castStateUsing(function ($state): string {
                    if (blank($state)) return 'Non Papua';
                    $state = (string) $state;
                    return stripos($state, 'papua') !== false && stripos($state, 'non') === false ? 'Papua' : 'Non Papua';
                }),
            ImportColumn::make('alamat')
                ->rules(['string'])
                ->example('Jl. Merdeka No. 10'),
            ImportColumn::make('provinsi')
                ->rules(['string', 'max:255'])
                ->example('Jawa Barat'),
            ImportColumn::make('kabupaten')
                ->rules(['string', 'max:255'])
                ->example('Bandung'),
            ImportColumn::make('kecamatan')
                ->rules(['string', 'max:255'])
                ->example('Coblong'),
            ImportColumn::make('desa')
                ->rules(['string', 'max:255'])
                ->example('Dago'),
            ImportColumn::make('nama_ayah')
                ->rules(['string', 'max:255'])
                ->example('Ahmad Santoso'),
            ImportColumn::make('nama_ibu')
                ->rules(['string', 'max:255'])
                ->example('Siti Aminah'),
            ImportColumn::make('nama_wali')
                ->rules(['string', 'max:255'])
                ->example('Paman Budi'),
            ImportColumn::make('status')
                ->rules(['string', 'max:255'])
                ->example('Aktif')
                ->castStateUsing(fn($state): string => strtolower(trim((string)($state ?? 'aktif')))),
            ImportColumn::make('disabilitas')
                ->rules(['string', 'max:255'])
                ->example('Tidak')
                ->castStateUsing(function ($state): string {
                    if (blank($state)) return 'tidak';
                    $state = strtolower(trim((string)$state));
                    if ($state === 'tidak' || $state === 'no' || $state === 'none') return 'tidak';
                    return $state;
                }),
            ImportColumn::make('beasiswa')
                ->rules(['string', 'max:255'])
                ->example('Tidak')
                ->castStateUsing(function ($state): string {
                    if (blank($state)) return 'tidak';
                    $state = strtolower(trim((string)$state));
                    if ($state === 'tidak' || $state === 'no' || $state === 'none') return 'tidak';
                    if ($state === 'ya' || $state === 'yes') return 'beasiswa_pemerintah_pusat';
                    return $state;
                }),
            ImportColumn::make('kelas_rombel')
                ->rules(['string', 'max:255'])
                ->example('Kelas 10-A'),
        ];
    }

    public function resolveRecord(): Siswa
    {
        $sekolahId = filament()->getTenant()?->id ?? $this->import->user->sekolah?->id;

        if (! $sekolahId) {
            throw new \Exception('Gagal mendeteksi data Sekolah. Pastikan Anda melakukan import di dalam panel sekolah yang benar.');
        }

        // Always return a new instance if we want unique validation to trigger properly
        // If we use firstOrNew, the unique rule might not trigger as the record "exists" in the resolved state
        return new Siswa(['sekolah_id' => $sekolahId]);
    }

    public function getValidationMessages(): array
    {
        return [
            'nisn.unique' => 'NISN :input sudah terdaftar di database.',
            'nik.unique' => 'NIK :input sudah terdaftar di database.',
            'nobpjs.unique' => 'Nomor BPJS :input sudah terdaftar di database.',
        ];
    }

    protected function afterSave(): void
    {
        $siswa = $this->record;
        $kelasRombel = $siswa->kelas_rombel;

        if (blank($kelasRombel)) {
            return;
        }

        $sekolahId = $siswa->sekolah_id;

        $rombel = \App\Models\Rombel::where('sekolah_id', $sekolahId)
            ->where('nama', $kelasRombel)
            ->first();

        if ($rombel) {
            $year = now()->year;
            $month = now()->month;
            $tahunAjaran = ($month >= 7) ? "$year/".($year + 1) : ($year - 1)."/$year";

            $siswa->rombel()->syncWithoutDetaching([
                $rombel->id => ['tahun_ajaran' => $tahunAjaran],
            ]);
        } else {
            // Clear the invalid mapping
            $siswa->update(['kelas_rombel' => null]);
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Impor siswa selesai. ' . Number::format($import->successful_rows) . ' baris berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' baris gagal diimpor.';
        }

        return $body;
    }
}
