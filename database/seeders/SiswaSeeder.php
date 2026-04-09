<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        $agamas = ['Islam', 'Kristen', 'Katolik', 'Hindu'];
        $daerah = ['Papua', 'Non Papua'];

        $rombels = Rombel::all();

        foreach ($rombels as $rombel) {

            $jumlahSiswa = rand(20, 36);

            for ($i = 1; $i <= $jumlahSiswa; $i++) {

                $umur = match ($rombel->tingkat) {
                    10 => rand(14, 16),
                    11 => rand(15, 17),
                    12 => rand(16, 18),
                    default => rand(14, 18),
                };

                $tanggalLahir = now()->subYears($umur)->subDays(rand(0, 365));

                // 🔹 1. INSERT SISWA
                $siswa = Siswa::create([
                    'sekolah_id' => $rombel->sekolah_id,
                    'nama' => 'Siswa ' . $i . ' ' . $rombel->nama,
                    'nisn' => rand(1000000000, 9999999999),
                    'nik' => rand(1000000000000000, 9999999999999999),

                    'jenis_kelamin' => rand(0,1) ? 'Laki-laki' : 'Perempuan',
                    'tempat_lahir' => 'Teluk Bintuni',
                    'tanggal_lahir' => $tanggalLahir,

                    'alamat' => 'Jl. Pendidikan',
                    'kecamatan' => 'Bintuni',
                    'kabupaten' => 'Teluk Bintuni',
                    'provinsi' => 'Papua Barat',

                    'agama' => $agamas[array_rand($agamas)],
                    'daerah_asal' => $daerah[array_rand($daerah)],

                    'nama_ayah' => 'Ayah ' . $i,
                    'nama_ibu' => 'Ibu ' . $i,

                    'status' => 'aktif',
                ]);

                // 🔹 2. INSERT KE PIVOT siswa_rombel
                DB::table('siswa_rombel')->insert([
                    'siswa_id' => $siswa->id,
                    'rombel_id' => $rombel->id,
                    'tahun_ajaran' => now()->year,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}