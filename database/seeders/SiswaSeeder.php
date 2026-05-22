<?php

namespace Database\Seeders;

use App\Models\Rombel;
use App\Models\Sekolah;
use Database\Seeders\Concerns\RealisticDummyData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    use RealisticDummyData;

    public function run(): void
    {
        $sekolahs = Sekolah::all();
        $now = now();

        foreach ($sekolahs as $schoolIndex => $sekolah) {
            $rombels = Rombel::where('sekolah_id', $sekolah->id)
                ->orderBy('tingkat')
                ->orderBy('nama')
                ->get();

            if ($rombels->isEmpty()) {
                continue;
            }

            $targetSiswa = 300 + (($schoolIndex * 17) % 45);
            $basePerRombel = intdiv($targetSiswa, $rombels->count());
            $remaining = $targetSiswa % $rombels->count();

            foreach ($rombels as $rombelIndex => $rombel) {
                $jumlahSiswa = $basePerRombel + ($rombelIndex < $remaining ? 1 : 0);

                for ($i = 0; $i < $jumlahSiswa; $i++) {
                    $seed = ($schoolIndex + 1) * 100000 + ($rombelIndex + 1) * 1000 + $i;
                    $gender = $seed % 2 === 0 ? 'L' : 'P';
                    $birthDate = $this->studentBirthDate((int) $rombel->tingkat, $seed);
                    $agama = $this->weightedPick([
                        'Kristen' => 38,
                        'Islam' => 34,
                        'Katolik' => 19,
                        'Hindu' => 5,
                        'Buddha' => 3,
                        'Konghucu' => 1,
                    ], $seed);
                    $daerahAsal = $seed % 10 < 7 ? 'Papua' : 'Non Papua';
                    $status = $this->weightedPick([
                        'aktif' => 91,
                        'mutasi_masuk' => 3,
                        'mutasi_keluar' => 2,
                        'mengulang' => 2,
                        'lulus' => 1,
                        'putus_sekolah' => 1,
                    ], $seed + 19);
                    $disabilitas = $this->weightedPick([
                        'tidak' => 95,
                        'tuna_netra' => 1,
                        'tuna_rungu' => 1,
                        'tuna_wicara' => 1,
                        'tuna_daksa' => 1,
                        'tuna_grahita' => 1,
                    ], $seed + 29);
                    $beasiswa = $this->weightedPick([
                        'tidak' => 68,
                        'beasiswa_pemerintah_pusat' => 13,
                        'beasiswa_pemerintah_daerah' => 9,
                        'beasisswa_swasta' => 3,
                        'beasiswa_khusus' => 2,
                        'beasiswa_afirmasi' => 4,
                        'beasiswa_lainnya' => 1,
                    ], $seed + 37);

                    $siswaId = DB::table('siswa')->insertGetId([
                        'sekolah_id' => $sekolah->id,
                        'nama' => $this->personName($gender, $seed, $daerahAsal === 'Papua'),
                        'nisn' => $this->nisn($birthDate, $seed),
                        'nokk' => '9206' . str_pad((string) (100000000000 + $seed), 12, '0', STR_PAD_LEFT),
                        'nik' => $this->nik($birthDate, $gender, $seed),
                        'nobpjs' => '000' . str_pad((string) (3000000000 + $seed), 10, '0', STR_PAD_LEFT),
                        'jenis_kelamin' => $gender === 'L' ? 'Laki-laki' : 'Perempuan',
                        'tempat_lahir' => $this->pick($this->birthPlaces, $seed),
                        'tanggal_lahir' => $birthDate->toDateString(),
                        'alamat' => $this->address($sekolah, $seed),
                        'desa' => $sekolah->desa,
                        'kecamatan' => $sekolah->kecamatan,
                        'kabupaten' => $sekolah->kabupaten,
                        'provinsi' => $sekolah->provinsi,
                        'agama' => $agama,
                        'daerah_asal' => $daerahAsal,
                        'nama_ayah' => $this->personName('L', $seed + 701, $daerahAsal === 'Papua'),
                        'nama_ibu' => $this->personName('P', $seed + 1301, $daerahAsal === 'Papua'),
                        'nama_wali' => $seed % 8 === 0 ? $this->personName($gender === 'L' ? 'P' : 'L', $seed + 1901, $daerahAsal === 'Papua') : null,
                        'nohp_ortuwali' => $this->phone($seed),
                        'disabilitas' => $disabilitas,
                        'beasiswa' => $beasiswa,
                        'status' => $status,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    DB::table('siswa_rombel')->insert([
                        'siswa_id' => $siswaId,
                        'rombel_id' => $rombel->id,
                        'tahun_ajaran' => now()->year . '/' . (now()->year + 1),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }
}
