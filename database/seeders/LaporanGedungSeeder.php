<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laporan;
use App\Models\LaporanGedung;

class LaporanGedungSeeder extends Seeder
{
    public function run()
    {
        $ruangan = [
            'Ruang Kepala Sekolah',
            'Ruang Guru',
            'Ruang Kelas',
            'Lab Komputer',
            'Perpustakaan',
            'Musholla',
        ];

        $laporans = Laporan::all();

        foreach ($laporans as $laporan) {

            foreach ($ruangan as $namaRuang) {

                $total = rand(1, 10);
                $rusak = rand(0, $total);
                $baik = $total - $rusak;

                LaporanGedung::create([
                    'laporan_id' => $laporan->id,
                    'nama_ruang' => $namaRuang,
                    'jumlah_total' => $total,
                    'jumlah_baik' => $baik,
                    'jumlah_rusak' => $rusak,
                    'status_kepemilikan' => rand(0,1) ? 'milik' : 'pinjam',
                ]);
            }
        }
    }
}