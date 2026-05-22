<?php

namespace Database\Seeders;

use App\Models\Gtk;
use App\Models\LaporanGtk;
use App\Models\LaporanGtkKategori;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LaporanGtkKategoriSeeder extends Seeder
{
    public function run(): void
    {
        $laporanGtks = LaporanGtk::with('laporan')->get();

        foreach ($laporanGtks as $laporanGtk) {
            $gtks = Gtk::where('sekolah_id', $laporanGtk->laporan->sekolah_id)
                ->get()
                ->filter(fn ($gtk) => $this->normalizeJenisGtk($gtk->jenis_gtk) === $laporanGtk->jenis_gtk);

            $periodEnd = Carbon::create($laporanGtk->laporan->tahun, $laporanGtk->laporan->bulan, 1)->endOfMonth();

            $this->writeGenderSeries($laporanGtk->id, 'agama', [
                'islam' => fn ($gtk) => str_contains(strtolower((string) $gtk->agama), 'islam'),
                'kristen_protestan' => fn ($gtk) => str_contains(strtolower((string) $gtk->agama), 'kristen'),
                'katolik' => fn ($gtk) => str_contains(strtolower((string) $gtk->agama), 'katolik'),
                'hindu' => fn ($gtk) => str_contains(strtolower((string) $gtk->agama), 'hindu'),
                'budha' => fn ($gtk) => str_contains(strtolower((string) $gtk->agama), 'budha') || str_contains(strtolower((string) $gtk->agama), 'buddha'),
                'konghucu' => fn ($gtk) => str_contains(strtolower((string) $gtk->agama), 'konghucu') || str_contains(strtolower((string) $gtk->agama), 'khonghucu'),
            ], $gtks);

            $this->writeGenderSeries($laporanGtk->id, 'daerah', [
                'papua' => fn ($gtk) => str_contains(strtolower((string) $gtk->daerah_asal), 'papua') && ! str_contains(strtolower((string) $gtk->daerah_asal), 'non'),
                'non_papua' => fn ($gtk) => ! (str_contains(strtolower((string) $gtk->daerah_asal), 'papua') && ! str_contains(strtolower((string) $gtk->daerah_asal), 'non')),
            ], $gtks);

            $this->writeGenderSeries($laporanGtk->id, 'umur', [
                'umur_20_29' => fn ($gtk) => $this->ageInRange($gtk, $periodEnd, 20, 29),
                'umur_30_39' => fn ($gtk) => $this->ageInRange($gtk, $periodEnd, 30, 39),
                'umur_40_49' => fn ($gtk) => $this->ageInRange($gtk, $periodEnd, 40, 49),
                'umur_50_59' => fn ($gtk) => $this->ageInRange($gtk, $periodEnd, 50, 59),
                'umur_60_ke_atas' => fn ($gtk) => $this->ageInRange($gtk, $periodEnd, 60, null),
            ], $gtks);

            $this->writeFlatSeries($laporanGtk->id, 'status_kepegawaian', [
                'gol_i_a', 'gol_i_b', 'gol_i_c', 'gol_i_d',
                'gol_ii_a', 'gol_ii_b', 'gol_ii_c', 'gol_ii_d',
                'gol_iii_a', 'gol_iii_b', 'gol_iii_c', 'gol_iii_d',
                'gol_iv_a', 'gol_iv_b', 'gol_iv_c', 'gol_iv_d', 'gol_iv_e',
                'pppk', 'honorer_sekolah',
            ], function ($key) use ($gtks) {
                return $gtks->filter(function ($gtk) use ($key) {
                    $status = strtolower((string) $gtk->status_kepegawaian);
                    $golongan = 'gol_' . strtolower(str_replace('/', '_', (string) $gtk->pangkat_gol_terakhir));

                    if ($key === 'pppk') {
                        return str_contains($status, 'pppk');
                    }

                    if ($key === 'honorer_sekolah') {
                        return str_contains($status, 'honorer') || str_contains($status, 'gty') || str_contains($status, 'pty') || str_contains($status, 'kontrak');
                    }

                    return str_contains($status, 'pns') && $golongan === $key;
                })->count();
            });

            $this->writeFlatSeries($laporanGtk->id, 'pendidikan', ['slta', 'di', 'dii', 'diii', 's1', 's2', 's3'], function ($key) use ($gtks) {
                return $gtks->filter(function ($gtk) use ($key) {
                    $pendidikan = strtolower((string) $gtk->pendidikan_terakhir);

                    return match ($key) {
                        'slta' => str_contains($pendidikan, 'sma') || str_contains($pendidikan, 'smk') || str_contains($pendidikan, 'slta'),
                        'di' => $pendidikan === 'd1',
                        'dii' => $pendidikan === 'd2',
                        'diii' => $pendidikan === 'd3' || $pendidikan === 'diii',
                        's1' => str_contains($pendidikan, 's1') || str_contains($pendidikan, 'd4'),
                        's2' => str_contains($pendidikan, 's2'),
                        's3' => str_contains($pendidikan, 's3'),
                    };
                })->count();
            });
        }
    }

    private function writeGenderSeries(int $laporanGtkId, string $jenisKategori, array $checks, iterable $gtks): void
    {
        $gtks = collect($gtks);

        foreach ($checks as $subKategori => $check) {
            $rows = $gtks->filter($check);
            $laki = $rows->filter(fn ($gtk) => str_starts_with(strtoupper((string) $gtk->jenis_kelamin), 'L'))->count();
            $perempuan = $rows->filter(fn ($gtk) => str_starts_with(strtoupper((string) $gtk->jenis_kelamin), 'P'))->count();

            $this->writeCategory($laporanGtkId, $jenisKategori, "{$subKategori}_l", $laki);
            $this->writeCategory($laporanGtkId, $jenisKategori, "{$subKategori}_p", $perempuan);
            $this->writeCategory($laporanGtkId, $jenisKategori, "{$subKategori}_jml", $laki + $perempuan);
        }
    }

    private function writeFlatSeries(int $laporanGtkId, string $jenisKategori, array $keys, callable $resolver): void
    {
        foreach ($keys as $key) {
            $this->writeCategory($laporanGtkId, $jenisKategori, $key, $resolver($key));
        }
    }

    private function writeCategory(int $laporanGtkId, string $jenisKategori, string $subKategori, int $jumlah): void
    {
        LaporanGtkKategori::updateOrCreate(
            [
                'laporan_gtk_id' => $laporanGtkId,
                'jenis_kategori' => $jenisKategori,
                'sub_kategori' => $subKategori,
            ],
            ['jumlah' => $jumlah]
        );
    }

    private function normalizeJenisGtk(?string $value): string
    {
        $value = strtolower(trim((string) $value));

        return match (true) {
            str_contains($value, 'kepala') => 'kepala_sekolah',
            str_contains($value, 'administrasi') || str_contains($value, 'tenaga') => 'tenaga_administrasi',
            default => 'guru',
        };
    }

    private function ageInRange(object $gtk, Carbon $periodEnd, int $min, ?int $max): bool
    {
        if (! $gtk->tanggal_lahir) {
            return false;
        }

        $age = Carbon::parse($gtk->tanggal_lahir)->diffInYears($periodEnd);

        return $age >= $min && ($max === null || $age <= $max);
    }
}
