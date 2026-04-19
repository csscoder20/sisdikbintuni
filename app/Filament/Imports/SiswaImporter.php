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
                ->rules(['string', 'max:255'])
                ->example('1234567890'),
            ImportColumn::make('nik')
                ->rules(['string', 'max:255'])
                ->example('3201010101010001'),
            ImportColumn::make('tempat_lahir')
                ->rules(['string', 'max:255'])
                ->example('Bandung'),
            ImportColumn::make('tanggal_lahir')
                ->rules(['date'])
                ->example('20/08/2011')
                ->castStateUsing(function (string $state): ?string {
                    if (blank($state)) return null;
                    try {
                        // Jika formatnya d/m/Y (misal 20/08/2011)
                        if (preg_match('/^\d{1,2}[\/\-]\d{1,2}[\/\-]\d{4}$/', $state)) {
                            // Deteksi separator / atau -
                            $separator = str_contains($state, '/') ? '/' : '-';
                            return \Illuminate\Support\Carbon::createFromFormat("d{$separator}m{$separator}Y", $state)->format('Y-m-d');
                        }
                        return \Illuminate\Support\Carbon::parse($state)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return $state;
                    }
                }),
            ImportColumn::make('jenis_kelamin')
                ->rules(['string', 'max:255'])
                ->example('Laki-laki')
                ->castStateUsing(function (string $state): ?string {
                    $state = strtolower($state);
                    if ($state === 'l' || str_contains($state, 'laki')) return 'Laki-laki';
                    if ($state === 'p' || str_contains($state, 'perempuan')) return 'Perempuan';
                    return null;
                }),
            ImportColumn::make('agama')
                ->rules(['string', 'max:255'])
                ->example('Islam')
                ->castStateUsing(function (string $state): ?string {
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
                ->rules(['string', 'max:20'])
                ->example('0001234567890'),
            ImportColumn::make('daerah_asal')
                ->rules(['string', 'max:255'])
                ->example('Non Papua')
                ->castStateUsing(fn(string $state): string => 
                    stripos($state, 'papua') !== false && stripos($state, 'non') === false ? 'Papua' : 'Non Papua'
                ),
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
                ->example(''),
            ImportColumn::make('status')
                ->rules(['string', 'max:255'])
                ->example('Aktif')
                ->castStateUsing(fn(string $state): string => strtolower($state)),
            ImportColumn::make('disabilitas')
                ->rules(['string', 'max:255'])
                ->example('Tidak')
                ->castStateUsing(function (string $state): string {
                    $state = strtolower($state);
                    if ($state === 'tidak' || $state === 'no' || $state === 'none') return 'tidak';
                    return $state;
                }),
            ImportColumn::make('beasiswa')
                ->rules(['string', 'max:255'])
                ->example('Tidak')
                ->castStateUsing(function (string $state): string {
                    $state = strtolower($state);
                    if ($state === 'tidak' || $state === 'no' || $state === 'none') return 'tidak';
                    if ($state === 'ya' || $state === 'yes') return 'beasiswa_pemerintah_pusat';
                    return $state;
                }),
        ];
    }

    public function resolveRecord(): Siswa
    {
        $sekolahId = filament()->getTenant()?->id ?? $this->import->user->sekolah?->id;

        if (! $sekolahId) {
            throw new \Exception('Gagal mendeteksi data Sekolah. Pastikan Anda melakukan import di dalam panel sekolah yang benar.');
        }

        $nisn = $this->data['nisn'] ?? null;

        if (blank($nisn)) {
            return new Siswa(['sekolah_id' => $sekolahId]);
        }

        return Siswa::firstOrNew([
            'sekolah_id' => $sekolahId,
            'nisn' => $nisn,
        ]);
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
