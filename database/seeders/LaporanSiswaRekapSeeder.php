<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaporanSiswaRekap;

class LaporanSiswaRekapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laporanSiswaId = 1;

        $data = [
            [
                'kategori' => 'awal_bulan',
                'laki_laki' => 12,
                'perempuan' => 17,
            ],
            [
                'kategori' => 'mutasi_masuk',
                'laki_laki' => 1,
                'perempuan' => 2,
            ],
            [
                'kategori' => 'mutasi_keluar',
                'laki_laki' => 0,
                'perempuan' => 1,
            ],
            [
                'kategori' => 'putus_sekolah',
                'laki_laki' => 0,
                'perempuan' => 0,
            ],
            [
                'kategori' => 'mengulang',
                'laki_laki' => 1,
                'perempuan' => 0,
            ],
            [
                'kategori' => 'akhir_bulan',
                'laki_laki' => 14,
                'perempuan' => 18,
            ],
        ];

        foreach ($data as $item) {
            LaporanSiswaRekap::create([
                'laporan_siswa_id' => $laporanSiswaId,
                'kategori' => $item['kategori'],
                'laki_laki' => $item['laki_laki'],
                'perempuan' => $item['perempuan'],
                'total' => $item['laki_laki'] + $item['perempuan'],
            ]);
        }
    }
}
