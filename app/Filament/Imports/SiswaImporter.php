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

    protected static function textOrNull($state, int $maxLength = 255): ?string
    {
        if (blank($state)) {
            return null;
        }

        $value = trim((string) $state);

        if ($value === '') {
            return null;
        }

        if (in_array(strtolower($value), ['null', 'nil', 'n/a', 'na', '-'], true)) {
            return null;
        }

        return mb_substr($value, 0, $maxLength);
    }

    protected static function normalizeRombelName(string $value): string
    {
        return (string) str($value)
            ->lower()
            ->replaceMatches('/\bkelas\b/u', '')
            ->replaceMatches('/[^a-z0-9]+/u', '')
            ->trim();
    }

    protected static function strictDateFromParts(int $year, int $month, int $day): ?string
    {
        if ($year < 1900 || $year > 2100 || ! checkdate($month, $day, $year)) {
            return null;
        }

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    public static function parseFlexibleDate($state): ?string
    {
        if (blank($state)) return null;
        if ($state instanceof \DateTimeInterface) {
            return $state->format('Y-m-d');
        }

        try {
            $stateString = trim((string) $state);
            if (empty($stateString)) return null;

            // Excel serial dates are often imported as plain numbers.
            if (is_numeric($stateString) && (float) $stateString >= 20000 && (float) $stateString <= 60000) {
                return \Illuminate\Support\Carbon::create(1899, 12, 30)
                    ->addDays((int) $stateString)
                    ->format('Y-m-d');
            }

            // Map Indonesian months
            $months = [
                'januari' => 'January', 'februari' => 'February', 'maret' => 'March',
                'april' => 'April', 'mei' => 'May', 'juni' => 'June',
                'juli' => 'July', 'agustus' => 'August', 'september' => 'September',
                'oktober' => 'October', 'november' => 'November', 'desember' => 'December'
            ];

            $normalizedState = strtolower($stateString);
            foreach ($months as $indo => $eng) {
                if (str_contains($normalizedState, $indo)) {
                    $normalizedState = str_replace($indo, $eng, $normalizedState);
                    break;
                }
            }

            // Handle d/m/Y, d-m-Y, d.m.Y even with 2 digit years
            if (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{2,4})$/', $normalizedState, $matches)) {
                $first = (int) $matches[1];
                $second = (int) $matches[2];
                $year = (int) $matches[3];
                if ($year < 100) $year += 2000;

                return static::strictDateFromParts($year, $second, $first)
                    ?? static::strictDateFromParts($year, $first, $second);
            }

            // Handle Y-m-d, Y/m/d, Y.m.d strictly so impossible dates become null.
            if (preg_match('/^(\d{4})[\/\-\.](\d{1,2})[\/\-\.](\d{1,2})$/', $normalizedState, $matches)) {
                return static::strictDateFromParts((int) $matches[1], (int) $matches[2], (int) $matches[3]);
            }

            $date = \Illuminate\Support\Carbon::parse($normalizedState);

            if ($date->year < 1900 || $date->year > 2100) {
                return null;
            }

            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required'])
                ->castStateUsing(fn($state) => static::textOrNull($state) ?? '-')
                ->example('Budi Santoso'),
            ImportColumn::make('nisn')
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state))
                ->example('1234567890'),
            ImportColumn::make('nik')
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state))
                ->example('3201010101010001'),
            ImportColumn::make('tempat_lahir')
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state))
                ->example('Bandung'),
            ImportColumn::make('tanggal_lahir')
                ->rules(['nullable'])
                ->example('20/08/2011')
                ->castStateUsing(fn ($state) => static::parseFlexibleDate($state)),
            ImportColumn::make('jenis_kelamin')
                ->rules(['nullable'])
                ->example('Laki-laki')
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) return null;
                    $state = strtolower(trim((string)$state));
                    if ($state === 'l' || str_contains($state, 'laki')) return 'Laki-laki';
                    if ($state === 'p' || str_contains($state, 'perempuan')) return 'Perempuan';
                    return null;
                }),
            ImportColumn::make('agama')
                ->rules(['nullable'])
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
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state))
                ->example('3201010101010002'),
            ImportColumn::make('nobpjs')
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state))
                ->example('0001234567890'),
            ImportColumn::make('daerah_asal')
                ->rules(['nullable'])
                ->example('Non Papua')
                ->castStateUsing(function ($state): string {
                    if (blank($state)) return 'Non Papua';
                    $state = (string) $state;
                    return stripos($state, 'papua') !== false && stripos($state, 'non') === false ? 'Papua' : 'Non Papua';
                }),
            ImportColumn::make('alamat')
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state, 65535))
                ->example('Jl. Merdeka No. 10'),
            ImportColumn::make('provinsi')
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state))
                ->example('Jawa Barat'),
            ImportColumn::make('kabupaten')
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state))
                ->example('Bandung'),
            ImportColumn::make('kecamatan')
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state))
                ->example('Coblong'),
            ImportColumn::make('desa')
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state))
                ->example('Dago'),
            ImportColumn::make('nama_ayah')
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state))
                ->example('Ahmad Santoso'),
            ImportColumn::make('nama_ibu')
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state))
                ->example('Siti Aminah'),
            ImportColumn::make('nama_wali')
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state))
                ->example('Paman Budi'),
            ImportColumn::make('nohp_ortuwali')
                ->label('No. HP Orang Tua/Wali')
                ->guess(['nohp_ortu_wali', 'no_hp_ortu_wali', 'no_hp_orang_tua_wali', 'hp_ortu_wali'])
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state, 30))
                ->example('081234567890'),
            ImportColumn::make('status')
                ->rules(['nullable'])
                ->example('Aktif')
                ->castStateUsing(function ($state): string {
                    $state = strtolower(trim((string)($state ?? 'aktif')));

                    return match (true) {
                        $state === '',
                        str_contains($state, 'aktif') => 'aktif',
                        str_contains($state, 'masuk') => 'mutasi_masuk',
                        str_contains($state, 'keluar'),
                        str_contains($state, 'pindah') => 'mutasi_keluar',
                        str_contains($state, 'lulus') => 'lulus',
                        str_contains($state, 'putus') => 'putus_sekolah',
                        str_contains($state, 'ulang') => 'mengulang',
                        default => 'aktif',
                    };
                }),
            ImportColumn::make('disabilitas')
                ->rules(['nullable'])
                ->example('Tidak')
                ->castStateUsing(function ($state): string {
                    if (blank($state)) return 'tidak';
                    $state = strtolower(trim((string)$state));
                    if (in_array($state, ['tidak', 'no', 'none', 'normal', 'sehat', '-'], true)) return 'tidak';
                    if (str_contains($state, 'netra')) return 'tuna_netra';
                    if (str_contains($state, 'rungu')) return 'tuna_rungu';
                    if (str_contains($state, 'wicara')) return 'tuna_wicara';
                    if (str_contains($state, 'daksa')) return 'tuna_daksa';
                    if (str_contains($state, 'grahita')) return 'tuna_grahita';
                    return in_array($state, ['tuna_netra', 'tuna_rungu', 'tuna_wicara', 'tuna_daksa', 'tuna_grahita', 'tuna_lainnya'], true)
                        ? $state
                        : 'tuna_lainnya';
                }),
            ImportColumn::make('beasiswa')
                ->rules(['nullable'])
                ->example('Tidak')
                ->castStateUsing(function ($state): string {
                    if (blank($state)) return 'tidak';
                    $state = strtolower(trim((string)$state));
                    if (in_array($state, ['tidak', 'no', 'none', 'tidak ada', '-'], true)) return 'tidak';
                    if ($state === 'ya' || $state === 'yes') return 'beasiswa_pemerintah_pusat';
                    if (str_contains($state, 'pusat')) return 'beasiswa_pemerintah_pusat';
                    if (str_contains($state, 'daerah')) return 'beasiswa_pemerintah_daerah';
                    if (str_contains($state, 'swasta')) return 'beasiswa_swasta';
                    if (str_contains($state, 'khusus')) return 'beasiswa_khusus';
                    if (str_contains($state, 'afirmasi')) return 'beasiswa_afirmasi';
                    return in_array($state, ['beasiswa_pemerintah_pusat', 'beasiswa_pemerintah_daerah', 'beasiswa_swasta', 'beasiswa_khusus', 'beasiswa_afirmasi', 'beasiswa_lainnya'], true)
                        ? $state
                        : 'beasiswa_lainnya';
                }),
            ImportColumn::make('rombel')
                ->label('ROMBEL')
                ->guess(['kelas_rombel', 'kelas', 'nama_rombel'])
                ->rules(['nullable'])
                ->castStateUsing(fn($state) => static::textOrNull($state))
                ->fillRecordUsing(fn() => null)
                ->example('Kelas XII IPS 1'),
        ];
    }

    public function resolveRecord(): ?Siswa
    {
        // Skip if this is the instruction or example row from the template
        $nama = $this->data['nama'] ?? '';
        if (str_contains(strtolower($nama), 'nama lengkap') || str_contains($nama, 'Budi Santoso')) {
            return null;
        }

        $sekolahId = filament()->getTenant()?->id ?? $this->import->user->sekolah?->id;

        if (! $sekolahId) {
            throw new \Exception('Gagal mendeteksi data Sekolah. Pastikan Anda melakukan import di dalam panel sekolah yang benar.');
        }

        return new Siswa(['sekolah_id' => $sekolahId]);
    }

    protected function afterSave(): void
    {
        $siswa = $this->record;
        $kelasRombel = $this->data['rombel'] ?? $this->data['kelas_rombel'] ?? null;

        if (blank($kelasRombel)) {
            return;
        }

        $sekolahId = $siswa->sekolah_id;
        $kelasRombel = trim((string) $kelasRombel);

        $rombel = \App\Models\Rombel::where('sekolah_id', $sekolahId)
            ->get()
            ->first(function (\App\Models\Rombel $rombel) use ($kelasRombel): bool {
                return strcasecmp(trim($rombel->nama), $kelasRombel) === 0
                    || static::normalizeRombelName($rombel->nama) === static::normalizeRombelName($kelasRombel);
            });

        if ($rombel) {
            $year = now()->year;
            $month = now()->month;
            $tahunAjaran = ($month >= 7) ? "$year/".($year + 1) : ($year - 1)."/$year";

            // Enforce 1 rombel per year by detaching existing for the same year first
            $siswa->rombel()->wherePivot('tahun_ajaran', $tahunAjaran)->detach();
            $siswa->rombel()->attach($rombel->id, ['tahun_ajaran' => $tahunAjaran]);
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
