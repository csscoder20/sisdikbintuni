<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sekolah;
use App\Models\Gtk;
use Illuminate\Support\Str;

class GtkSeeder extends Seeder
{
    public function run()
    {
        $agamas = ['Islam', 'Kristen', 'Katolik', 'Hindu'];
        $daerah = ['Papua', 'Non Papua'];
        $statusKepegawaian = ['PNS', 'PPPK', 'Honorer Sekolah'];
        $pendidikan = ['SMA', 'D3', 'S1', 'S2'];

        $sekolahs = Sekolah::all();

        foreach ($sekolahs as $sekolah) {

            // 🔹 1. Kepala Sekolah
            Gtk::create([
                'sekolah_id' => $sekolah->id,
                'nama' => 'Kepala ' . $sekolah->nama,
                'jenis_kelamin' => 'Laki-laki',
                'tanggal_lahir' => now()->subYears(rand(45, 58)),
                'agama' => $agamas[array_rand($agamas)],
                'pendidikan_terakhir' => 'S2',
                'daerah_asal' => $daerah[array_rand($daerah)],
                'jenis_gtk' => 'Kepala Sekolah',
                'status_kepegawaian' => 'PNS',
                'pangkat_gol_terakhir' => 'IV/a',
            ]);

            // 🔹 2. Guru (8–12 orang)
            $jumlahGuru = rand(8, 12);

            for ($i = 1; $i <= $jumlahGuru; $i++) {
                Gtk::create([
                    'sekolah_id' => $sekolah->id,
                    'nama' => 'Guru ' . $i . ' ' . $sekolah->nama,
                    'jenis_kelamin' => rand(0,1) ? 'Laki-laki' : 'Perempuan',
                    'tanggal_lahir' => now()->subYears(rand(25, 55)),
                    'agama' => $agamas[array_rand($agamas)],
                    'pendidikan_terakhir' => $pendidikan[array_rand($pendidikan)],
                    'daerah_asal' => $daerah[array_rand($daerah)],
                    'jenis_gtk' => 'Guru',
                    'status_kepegawaian' => $statusKepegawaian[array_rand($statusKepegawaian)],
                ]);
            }

            // 🔹 3. Tenaga Administrasi (2–4 orang)
            $jumlahTU = rand(2, 4);

            for ($i = 1; $i <= $jumlahTU; $i++) {
                Gtk::create([
                    'sekolah_id' => $sekolah->id,
                    'nama' => 'TU ' . $i . ' ' . $sekolah->nama,
                    'jenis_kelamin' => rand(0,1) ? 'Laki-laki' : 'Perempuan',
                    'tanggal_lahir' => now()->subYears(rand(23, 50)),
                    'agama' => $agamas[array_rand($agamas)],
                    'pendidikan_terakhir' => $pendidikan[array_rand($pendidikan)],
                    'daerah_asal' => $daerah[array_rand($daerah)],
                    'jenis_gtk' => 'Tenaga Administrasi',
                    'status_kepegawaian' => $statusKepegawaian[array_rand($statusKepegawaian)],
                ]);
            }
        }
    }
}