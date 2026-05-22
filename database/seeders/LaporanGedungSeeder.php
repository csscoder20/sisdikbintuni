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
            'Ruang Kepala Sekolah' => [1, 1],
            'Ruang Guru' => [1, 2],
            'Ruang Tata Usaha' => [1, 1],
            'Ruang Kelas' => [10, 14],
            'Lab Komputer' => [1, 2],
            'Lab IPA' => [1, 2],
            'Perpustakaan' => [1, 1],
            'Ruang UKS' => [1, 1],
            'Ruang OSIS' => [1, 1],
            'Gudang' => [1, 2],
            'Toilet Siswa' => [4, 8],
            'Toilet Guru' => [2, 4],
        ];

        $laporans = Laporan::all();

        foreach ($laporans as $laporan) {

            foreach ($ruangan as $namaRuang => [$min, $max]) {
                $seed = $laporan->sekolah_id * 100 + $laporan->bulan + strlen($namaRuang);
                $total = $min + ($seed % ($max - $min + 1));
                $rusak = $seed % 5 === 0 ? max(1, intdiv($total, 5)) : 0;
                $baik = $total - $rusak;

                LaporanGedung::updateOrCreate(
                    [
                        'laporan_id' => $laporan->id,
                        'nama_ruang' => $namaRuang,
                    ],
                    [
                        'jumlah_total' => $total,
                        'jumlah_baik' => $baik,
                        'jumlah_rusak' => $rusak,
                        'status_kepemilikan' => $seed % 9 === 0 ? 'pinjam' : 'milik',
                    ]
                );
            }
        }
    }
}
