<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaporanSiswa;
use App\Models\LaporanSiswaKategori;

class LaporanSiswaKategoriSeeder extends Seeder
{
    public function run()
    {
        $laporanSiswas = LaporanSiswa::all();

        foreach ($laporanSiswas as $ls) {

            // ========================
            // 🔹 UMUR (13 - 23)
            // ========================
            for ($umur = 13; $umur <= 23; $umur++) {

                $l = rand(0, 5);
                $p = rand(0, 5);

                LaporanSiswaKategori::create([
                    'laporan_siswa_id' => $ls->id,
                    'jenis_kategori' => 'umur',
                    'sub_kategori' => (string)$umur,
                    'laki_laki' => $l,
                    'perempuan' => $p,
                    'total' => $l + $p,
                ]);
            }

            // ========================
            // 🔹 AGAMA
            // ========================
            $agamas = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'];

            foreach ($agamas as $agama) {

                $l = rand(5, 15);
                $p = rand(5, 15);

                LaporanSiswaKategori::create([
                    'laporan_siswa_id' => $ls->id,
                    'jenis_kategori' => 'agama',
                    'sub_kategori' => $agama,
                    'laki_laki' => $l,
                    'perempuan' => $p,
                    'total' => $l + $p,
                ]);
            }

            // ========================
            // 🔹 ASAL DAERAH
            // ========================
            $daerah = ['Papua', 'Non Papua'];

            foreach ($daerah as $d) {

                $l = rand(10, 20);
                $p = rand(10, 20);

                LaporanSiswaKategori::create([
                    'laporan_siswa_id' => $ls->id,
                    'jenis_kategori' => 'asal_daerah',
                    'sub_kategori' => $d,
                    'laki_laki' => $l,
                    'perempuan' => $p,
                    'total' => $l + $p,
                ]);
            }

            // ========================
            // 🔹 DISABILITAS
            // ========================
            $disabilitas = [
                'Tuna Netra',
                'Tuna Rungu',
                'Tuna Wicara',
                'Tuna Daksa',
                'Tuna Grahita',
                'Lainnya'
            ];

            foreach ($disabilitas as $d) {

                $l = rand(0, 2);
                $p = rand(0, 2);

                LaporanSiswaKategori::create([
                    'laporan_siswa_id' => $ls->id,
                    'jenis_kategori' => 'disabilitas',
                    'sub_kategori' => $d,
                    'laki_laki' => $l,
                    'perempuan' => $p,
                    'total' => $l + $p,
                ]);
            }

            // ========================
            // 🔹 BEASISWA
            // ========================
            $beasiswa = ['KIP', 'Beasiswa Pemda', 'Lainnya'];

            foreach ($beasiswa as $b) {

                $l = rand(0, 10);
                $p = rand(0, 10);

                LaporanSiswaKategori::create([
                    'laporan_siswa_id' => $ls->id,
                    'jenis_kategori' => 'beasiswa',
                    'sub_kategori' => $b,
                    'laki_laki' => $l,
                    'perempuan' => $p,
                    'total' => $l + $p,
                ]);
            }
        }
    }
}