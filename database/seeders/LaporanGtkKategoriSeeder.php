<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaporanGtk;
use App\Models\LaporanGtkKategori;

class LaporanGtkKategoriSeeder extends Seeder
{
    public function run()
    {
        $laporanGtks = LaporanGtk::all();

        foreach ($laporanGtks as $lg) {

            // ========================
            // 🔹 AGAMA
            // ========================
            $agamas = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'];

            foreach ($agamas as $agama) {
                LaporanGtkKategori::create([
                    'laporan_gtk_id' => $lg->id,
                    'jenis_kategori' => 'agama',
                    'sub_kategori' => $agama,
                    'jumlah' => rand(0, 10),
                ]);
            }

            // ========================
            // 🔹 DAERAH
            // ========================
            $daerah = ['Papua', 'Non Papua'];

            foreach ($daerah as $d) {
                LaporanGtkKategori::create([
                    'laporan_gtk_id' => $lg->id,
                    'jenis_kategori' => 'daerah',
                    'sub_kategori' => $d,
                    'jumlah' => rand(0, 15),
                ]);
            }

            // ========================
            // 🔹 STATUS KEPEGAWAIAN
            // ========================
            $status = [
                'PNS',
                'CPNS',
                'PPPK',
                'GTY/PTY',
                'Kontrak',
                'Honorer Sekolah'
            ];

            foreach ($status as $s) {
                LaporanGtkKategori::create([
                    'laporan_gtk_id' => $lg->id,
                    'jenis_kategori' => 'status_kepegawaian',
                    'sub_kategori' => $s,
                    'jumlah' => rand(0, 10),
                ]);
            }

            // ========================
            // 🔹 UMUR
            // ========================
            $umurRanges = [
                '23-30',
                '31-40',
                '41-50',
                '51-60',
                '61-66'
            ];

            foreach ($umurRanges as $range) {
                LaporanGtkKategori::create([
                    'laporan_gtk_id' => $lg->id,
                    'jenis_kategori' => 'umur',
                    'sub_kategori' => $range,
                    'jumlah' => rand(0, 10),
                ]);
            }
        }
    }
}