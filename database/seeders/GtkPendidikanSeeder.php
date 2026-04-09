<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gtk;
use App\Models\GtkPendidikan;

class GtkPendidikanSeeder extends Seeder
{
    public function run()
    {
        $jurusan = ['Pendidikan Matematika', 'Bahasa Indonesia', 'IPA', 'Manajemen', 'Teknik Informatika'];
        $kampus = ['Universitas Cenderawasih', 'Universitas Papua', 'Universitas Negeri Makassar', 'Universitas Hasanuddin'];

        $gtks = Gtk::all();

        foreach ($gtks as $gtk) {

            $tahunLahir = \Carbon\Carbon::parse($gtk->tanggal_lahir)->year;

            // estimasi tahun tamat
            $sd = $tahunLahir + 12;
            $smp = $sd + 3;
            $sma = $smp + 3;

            $data = [
                'gtk_id' => $gtk->id,
                'thn_tamat_sd' => $sd,
                'thn_tamat_smp' => $smp,
                'thn_tamat_sma' => $sma,
            ];

            // pendidikan lanjut berdasarkan pendidikan terakhir
            switch ($gtk->pendidikan_terakhir) {

                case 'D3':
                    $data['thn_tamat_d3'] = $sma + 3;
                    $data['jurusan_d3'] = $jurusan[array_rand($jurusan)];
                    $data['perguruan_tinggi_d3'] = $kampus[array_rand($kampus)];
                    break;

                case 'S1':
                    $data['thn_tamat_s1'] = $sma + 4;
                    $data['jurusan_s1'] = $jurusan[array_rand($jurusan)];
                    $data['perguruan_tinggi_s1'] = $kampus[array_rand($kampus)];
                    break;

                case 'S2':
                    $data['thn_tamat_s1'] = $sma + 4;
                    $data['jurusan_s1'] = $jurusan[array_rand($jurusan)];
                    $data['perguruan_tinggi_s1'] = $kampus[array_rand($kampus)];

                    $data['thn_tamat_s2'] = $sma + 6;
                    $data['jurusan_s2'] = $jurusan[array_rand($jurusan)];
                    $data['perguruan_tinggi_s2'] = $kampus[array_rand($kampus)];
                    break;

                case 'SMA':
                default:
                    // cukup sampai SMA
                    break;
            }

            // optional: akta IV untuk guru
            if ($gtk->jenis_gtk === 'Guru') {
                $data['thn_akta4'] = $sma + 1;
                $data['jurusan_akta4'] = 'Pendidikan';
                $data['perguruan_tinggi_akta4'] = $kampus[array_rand($kampus)];
            }

            // gelar akademik
            if ($gtk->pendidikan_terakhir === 'S1') {
                $data['gelar_akademik'] = 'S.Pd';
            } elseif ($gtk->pendidikan_terakhir === 'S2') {
                $data['gelar_akademik'] = 'M.Pd';
            }

            GtkPendidikan::create($data);
        }
    }
}
