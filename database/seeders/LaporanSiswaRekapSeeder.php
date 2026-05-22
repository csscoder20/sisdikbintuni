<?php

namespace Database\Seeders;

use App\Models\LaporanSiswa;
use App\Models\LaporanSiswaRekap;
use Illuminate\Database\Seeder;

class LaporanSiswaRekapSeeder extends Seeder
{
    public function run(): void
    {
        $laporanSiswas = LaporanSiswa::with('rombel.siswa')->get();

        foreach ($laporanSiswas as $laporanSiswa) {
            $siswas = $laporanSiswa->rombel?->siswa ?? collect();

            $categories = [
                'mutasi_masuk' => $siswas->where('status', 'mutasi_masuk'),
                'mutasi_keluar' => $siswas->where('status', 'mutasi_keluar'),
                'putus_sekolah' => $siswas->where('status', 'putus_sekolah'),
                'mengulang' => $siswas->where('status', 'mengulang'),
            ];

            $akhirBulan = $siswas->filter(fn ($siswa) => in_array($siswa->status, ['aktif', 'mutasi_masuk', 'mengulang'], true));
            $awalBulan = $akhirBulan
                ->reject(fn ($siswa) => $siswa->status === 'mutasi_masuk')
                ->merge($categories['mutasi_keluar'])
                ->merge($categories['putus_sekolah']);

            $categories = array_merge([
                'awal_bulan' => $awalBulan,
            ], $categories, [
                'akhir_bulan' => $akhirBulan,
            ]);

            foreach ($categories as $kategori => $rows) {
                $laki = $rows->filter(fn ($siswa) => str_starts_with((string) $siswa->jenis_kelamin, 'L'))->count();
                $perempuan = $rows->filter(fn ($siswa) => str_starts_with((string) $siswa->jenis_kelamin, 'P'))->count();

                LaporanSiswaRekap::updateOrCreate(
                    [
                        'laporan_siswa_id' => $laporanSiswa->id,
                        'kategori' => $kategori,
                    ],
                    [
                        'laki_laki' => $laki,
                        'perempuan' => $perempuan,
                        'total' => $laki + $perempuan,
                    ]
                );
            }
        }
    }
}
