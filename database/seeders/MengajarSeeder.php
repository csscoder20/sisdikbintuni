<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gtk;
use App\Models\Rombel;
use App\Models\Mengajar;

class MengajarSeeder extends Seeder
{
    public function run()
    {
        $mapelList = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Fisika',
            'Kimia',
            'Biologi',
            'Sejarah',
            'Geografi',
            'Ekonomi',
            'PKN'
        ];

        // ambil hanya guru
        $gurus = Gtk::where('jenis_gtk', 'Guru')->get();

        foreach ($gurus as $guru) {

            $rombels = Rombel::where('sekolah_id', $guru->sekolah_id)->get();

            // tiap guru mengajar 1–3 rombel
            $selectedRombels = $rombels->random(min(3, $rombels->count()));

            foreach ($selectedRombels as $rombel) {

                Mengajar::create([
                    'gtk_id' => $guru->id,
                    'rombel_id' => $rombel->id,
                    'mata_pelajaran' => $mapelList[array_rand($mapelList)],
                    'jumlah_jam' => rand(2, 6),
                    'laporan_id' => null, // operasional
                    'semester' => ['ganjil', 'genap'][array_rand(['ganjil', 'genap'])],
                    'tahun_ajaran' => now()->format('Y') . '/' . (now()->format('Y') + 1)
                ]);
            }
        }
    }
}