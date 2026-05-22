<?php

namespace Database\Seeders;

use App\Models\LaporanSiswa;
use App\Models\LaporanSiswaKategori;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LaporanSiswaKategoriSeeder extends Seeder
{
    public function run(): void
    {
        $laporanSiswas = LaporanSiswa::with(['rombel.siswa', 'laporan'])->get();

        foreach ($laporanSiswas as $laporanSiswa) {
            $siswas = $laporanSiswa->rombel?->siswa ?? collect();
            $periodEnd = Carbon::create($laporanSiswa->laporan->tahun, $laporanSiswa->laporan->bulan, 1)->endOfMonth();

            for ($umur = 13; $umur <= 23; $umur++) {
                $rows = $siswas->filter(fn ($siswa) => $siswa->tanggal_lahir && Carbon::parse($siswa->tanggal_lahir)->diffInYears($periodEnd) === $umur);
                $this->writeGenderCategory($laporanSiswa->id, 'umur', (string) $umur, $rows);
            }

            foreach (['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'khonghucu'] as $agamaKey) {
                $rows = $siswas->filter(function ($siswa) use ($agamaKey) {
                    $agama = strtolower((string) $siswa->agama);

                    return match ($agamaKey) {
                        'islam' => str_contains($agama, 'islam'),
                        'kristen' => str_contains($agama, 'kristen'),
                        'katolik' => str_contains($agama, 'katolik'),
                        'hindu' => str_contains($agama, 'hindu'),
                        'buddha' => str_contains($agama, 'buddha') || str_contains($agama, 'budha'),
                        'khonghucu' => str_contains($agama, 'konghucu') || str_contains($agama, 'khonghucu'),
                    };
                });
                $this->writeGenderCategory($laporanSiswa->id, 'agama', $agamaKey, $rows);
            }

            foreach (['papua', 'non_papua'] as $daerahKey) {
                $rows = $siswas->filter(function ($siswa) use ($daerahKey) {
                    $daerah = strtolower((string) $siswa->daerah_asal);
                    $isPapua = str_contains($daerah, 'papua') && ! str_contains($daerah, 'non');

                    return $daerahKey === 'papua' ? $isPapua : ! $isPapua;
                });
                $this->writeGenderCategory($laporanSiswa->id, 'asal_daerah', $daerahKey, $rows);
            }

            foreach (['tidak', 'tuna_netra', 'tuna_rungu', 'tuna_wicara', 'tuna_daksa', 'tuna_grahita', 'tuna_lainnya'] as $disabilitas) {
                $this->writeGenderCategory(
                    $laporanSiswa->id,
                    'disabilitas',
                    $disabilitas,
                    $siswas->where('disabilitas', $disabilitas)
                );
            }

            foreach (['tidak', 'beasiswa_pemerintah_pusat', 'beasiswa_pemerintah_daerah', 'beasisswa_swasta', 'beasiswa_khusus', 'beasiswa_afirmasi', 'beasiswa_lainnya'] as $beasiswa) {
                $this->writeGenderCategory(
                    $laporanSiswa->id,
                    'beasiswa',
                    $beasiswa,
                    $siswas->where('beasiswa', $beasiswa)
                );
            }
        }
    }

    private function writeGenderCategory(int $laporanSiswaId, string $jenisKategori, string $subKategori, iterable $rows): void
    {
        $rows = collect($rows);
        $laki = $rows->filter(fn ($siswa) => str_starts_with((string) $siswa->jenis_kelamin, 'L'))->count();
        $perempuan = $rows->filter(fn ($siswa) => str_starts_with((string) $siswa->jenis_kelamin, 'P'))->count();

        LaporanSiswaKategori::updateOrCreate(
            [
                'laporan_siswa_id' => $laporanSiswaId,
                'jenis_kategori' => $jenisKategori,
                'sub_kategori' => $subKategori,
            ],
            [
                'laki_laki' => $laki,
                'perempuan' => $perempuan,
                'total' => $laki + $perempuan,
            ]
        );
    }
}
