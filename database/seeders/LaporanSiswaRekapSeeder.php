<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaporanSiswa;
use App\Models\LaporanSiswaRekap;

class LaporanSiswaRekapSeeder extends Seeder
{
    public function run()
    {
        $laporanSiswas = LaporanSiswa::all();

        foreach ($laporanSiswas as $ls) {

            // 🔹 AWAL BULAN
            $l_awal = rand(10, 20);
            $p_awal = rand(10, 20);
            $awal = $l_awal + $p_awal;

            // 🔹 MUTASI
            $l_mutasi = rand(0, 3);
            $p_mutasi = rand(0, 3);
            $mutasi = $l_mutasi + $p_mutasi;

            // 🔹 PUTUS
            $l_putus = rand(0, 2);
            $p_putus = rand(0, 2);
            $putus = $l_putus + $p_putus;

            // 🔹 MENGULANG
            $l_ulang = rand(0, 2);
            $p_ulang = rand(0, 2);
            $ulang = $l_ulang + $p_ulang;

            // 🔹 AKHIR (DIHITUNG)
            $l_akhir = $l_awal + $l_mutasi - $l_putus - $l_ulang;
            $p_akhir = $p_awal + $p_mutasi - $p_putus - $p_ulang;
            $akhir = $l_akhir + $p_akhir;

            // 🔹 INSERT SEMUA
            $data = [
                [
                    'kategori' => 'awal',
                    'laki_laki' => $l_awal,
                    'perempuan' => $p_awal,
                    'total' => $awal,
                ],
                [
                    'kategori' => 'mutasi',
                    'laki_laki' => $l_mutasi,
                    'perempuan' => $p_mutasi,
                    'total' => $mutasi,
                ],
                [
                    'kategori' => 'akhir',
                    'laki_laki' => $l_akhir,
                    'perempuan' => $p_akhir,
                    'total' => $akhir,
                ],
                [
                    'kategori' => 'putus',
                    'laki_laki' => $l_putus,
                    'perempuan' => $p_putus,
                    'total' => $putus,
                ],
                [
                    'kategori' => 'mengulang',
                    'laki_laki' => $l_ulang,
                    'perempuan' => $p_ulang,
                    'total' => $ulang,
                ],
            ];

            foreach ($data as $item) {
                LaporanSiswaRekap::create([
                    'laporan_siswa_id' => $ls->id,
                    'kategori' => $item['kategori'],
                    'laki_laki' => $item['laki_laki'],
                    'perempuan' => $item['perempuan'],
                    'total' => $item['total'],
                ]);
            }
        }
    }
}