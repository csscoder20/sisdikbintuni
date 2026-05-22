<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Database\Seeders\Concerns\RealisticDummyData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GtkSeeder extends Seeder
{
    use RealisticDummyData;

    public function run(): void
    {
        $statusKepegawaian = ['PNS', 'PPPK', 'GTY/PTY', 'Kontrak', 'Honorer Sekolah'];
        $pendidikanGuru = ['S1', 'S1', 'S1', 'S2'];
        $pendidikanTu = ['S1', 'S1', 'S2'];
        $pangkat = ['III/a', 'III/b', 'III/c', 'III/d', 'IV/a', 'IV/b'];
        $banks = ['Bank Papua', 'BRI', 'BNI', 'Mandiri'];
        $now = now();

                foreach (Sekolah::all() as $schoolIndex => $sekolah) {
            $records = [];
            $kepalaSeed = ($schoolIndex + 1) * 10000;
            $kepalaGender = $schoolIndex % 3 === 0 ? 'P' : 'L';
            $kepalaBirthDate = $this->adultBirthDate($kepalaSeed, 42, 58);

            $records[] = $this->gtkRecord(
                $sekolah,
                $kepalaSeed,
                $kepalaGender,
                'Kepala Sekolah',
                'PNS',
                'S2',
                $this->pick(['IV/a', 'IV/b', 'IV/c'], $kepalaSeed),
                $kepalaBirthDate,
                $banks,
                $now
            );

            // Increase variation: base 60, add school index * 20 and a small random offset (0‑20)
            $baseGuru = 60;
            $jumlahGuru = $baseGuru + ($schoolIndex * 20) % 80 + rand(0, 20);
            for ($i = 0; $i < $jumlahGuru; $i++) {
                $seed = ($schoolIndex + 1) * 10000 + 100 + $i;
                $gender = $seed % 2 === 0 ? 'L' : 'P';
                $status = $this->pick($statusKepegawaian, $seed);
                $birthDate = $this->adultBirthDate($seed, 24, 59);
                $records[] = $this->gtkRecord(
                    $sekolah,
                    $seed,
                    $gender,
                    'Guru',
                    $status,
                    $this->pick($pendidikanGuru, $seed),
                    in_array($status, ['PNS', 'PPPK'], true) ? $this->pick($pangkat, $seed) : null,
                    $birthDate,
                    $banks,
                    $now
                );
            }

            // Increase variation for Tenaga Administrasi similarly
            $baseTu = 8;
            $jumlahTu = $baseTu + ($schoolIndex * 8) % 12 + rand(0, 5);
            for ($i = 0; $i < $jumlahTu; $i++) {
                $seed = ($schoolIndex + 1) * 10000 + 500 + $i;
                $gender = $seed % 2 === 0 ? 'L' : 'P';
                $status = $this->pick(['PNS', 'PPPK', 'Kontrak', 'Honorer Sekolah'], $seed);
                $birthDate = $this->adultBirthDate($seed, 23, 55);
                $records[] = $this->gtkRecord(
                    $sekolah,
                    $seed,
                    $gender,
                    'Tenaga Administrasi',
                    $status,
                    $this->pick($pendidikanTu, $seed),
                    in_array($status, ['PNS', 'PPPK'], true) ? $this->pick($pangkat, $seed) : null,
                    $birthDate,
                    $banks,
                    $now
                );
            }

            DB::table('gtk')->insert($records);
        }
    }

    private function gtkRecord(
        object $sekolah,
        int $seed,
        string $gender,
        string $jenisGtk,
        string $statusKepegawaian,
        string $pendidikan,
        ?string $pangkat,
        \Carbon\Carbon $birthDate,
        array $banks,
        mixed $now,
    ): array {
        $isPnsLike = in_array($statusKepegawaian, ['PNS', 'PPPK'], true);
        $tmt = $isPnsLike ? now()->subYears(1 + ($seed % 25))->subMonths($seed % 12) : null;
        $bankGaji = $this->pick($banks, $seed);
        $bankTunjangan = $this->pick($banks, $seed + 3);

        return [
            'sekolah_id' => $sekolah->id,
            'nama' => $this->personName($gender, $seed, $seed % 10 < 7),
            'nik' => $this->nik($birthDate, $gender, $seed),
            'nip' => $isPnsLike ? $this->nip($birthDate, $gender, $seed) : null,
            'nokarpeg' => $isPnsLike ? 'KARPEG' . str_pad((string) (100000 + $seed), 9, '0', STR_PAD_LEFT) : null,
            'nuptk' => str_pad((string) (1000000000000000 + $seed), 16, '0', STR_PAD_LEFT),
            'jenis_kelamin' => $gender === 'L' ? 'Laki-laki' : 'Perempuan',
            'tempat_lahir' => $this->pick($this->birthPlaces, $seed + 13),
            'tanggal_lahir' => $birthDate->toDateString(),
            'alamat' => $this->address($sekolah, $seed),
            'desa' => $sekolah->desa,
            'kecamatan' => $sekolah->kecamatan,
            'kabupaten' => $sekolah->kabupaten,
            'provinsi' => $sekolah->provinsi,
            'agama' => $this->weightedPick([
                'Kristen' => 38,
                'Islam' => 33,
                'Katolik' => 20,
                'Hindu' => 5,
                'Buddha' => 3,
                'Konghucu' => 1,
            ], $seed),
            'pendidikan_terakhir' => $pendidikan,
            'daerah_asal' => $seed % 10 < 7 ? 'Papua' : 'Non Papua',
            'jenis_gtk' => $jenisGtk,
            'status_kepegawaian' => $statusKepegawaian,
            'tmt_pns' => $tmt?->toDateString(),
            'pangkat_gol_terakhir' => $pangkat,
            'tmt_pangkat_gol_terakhir' => $tmt?->copy()->addYears(4)->toDateString(),
            'nama_bank_gaji' => $bankGaji,
            'no_rek_gaji' => str_pad((string) (7000000000 + ($seed * 37) % 2000000000), 10, '0', STR_PAD_LEFT),
            'nama_bank_tunjangan' => $bankTunjangan,
            'no_rek_tunjangan' => str_pad((string) (8000000000 + ($seed * 41) % 1000000000), 10, '0', STR_PAD_LEFT),
            'npwp' => str_pad((string) (100000000000000 + $seed), 15, '0', STR_PAD_LEFT),
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
