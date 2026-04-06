<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use App\Models\User;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Gtk;
use App\Models\GtkRiwayatPendidikan;
use App\Models\Sarpras;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Special Schools and Operators
        $schoolsData = [
            ['nama' => 'SMA Negeri 1 Bintuni', 'email' => 'sman1bintuni@example.com', 'npsn' => '10103456', 'alamat' => 'Jl. Sudirman No. 123, Bintuni'],
        ];

        foreach ($schoolsData as $data) {
            // Create Operator for this school first
            $user = User::factory()->create([
                'name' => 'Operator ' . $data['nama'],
                'email' => strtolower(str_replace(' ', '', $data['nama'])) . '@operator.com',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'is_verified' => true,
            ]);

            // Create the school with the operator user_id
            $sekolah = Sekolah::factory()->create([
                'nama_sekolah' => $data['nama'],
                'email_sekolah' => $data['email'],
                'npsn' => $data['npsn'],
                'alamat' => $data['alamat'],
                'user_id' => $user->id,
                'kecamatan' => 'Bintuni',
                'kabupaten' => 'Teluk Bintuni',
                'provinsi' => 'Papua Barat',
                'tahun_berdiri' => 1985,
                'status_sekolah' => 'Negeri',
                'akreditasi_sekolah' => 'A'
            ]);

            // Create 3 Rombels
            $rombels = [];
            $tingkatanList = ['X', 'XI', 'XII'];
            $programList = ['IPA', 'IPS'];

            foreach ($tingkatanList as $tingkat) {
                foreach ($programList as $program) {
                    $rombels[] = Rombel::create([
                        'nama_rombel' => $tingkat . ' ' . $program,
                        'id_sekolah' => $sekolah->id,
                    ]);
                }
            }

            // Create Siswa in each rombel
            foreach ($rombels as $rombel) {
                for ($i = 0; $i < 35; $i++) {
                    Siswa::factory()->create([
                        'id_rombel' => $rombel->id,
                    ]);
                }
            }

            // Create GTK (Guru dan Tenaga Kependidikan)
            $gtkJenisData = [
                ['nama' => 'Drs. Budi Santoso', 'nip' => '197501011999031001', 'jenis_gtk' => 'Guru', 'gender' => 'L'],
                ['nama' => 'Siti Nurhaliza, S.Pd', 'nip' => '198203152008012001', 'jenis_gtk' => 'Guru', 'gender' => 'P'],
                ['nama' => 'Dr. Ahmad Wijaya, M.Pd', 'nip' => '198712311991031002', 'jenis_gtk' => 'Guru', 'gender' => 'L'],
                ['nama' => 'Eka Putri Lestari, S.Pd', 'nip' => '199005142015022001', 'jenis_gtk' => 'Guru', 'gender' => 'P'],
                ['nama' => 'Riza Permana, S.S', 'nip' => '199203301018011001', 'jenis_gtk' => 'Tenaga Kependidikan', 'gender' => 'L'],
                ['nama' => 'Dwi Citra Manalu, S.Pd', 'nip' => '199407171020031001', 'jenis_gtk' => 'Guru', 'gender' => 'P'],
                ['nama' => 'Hendrik Wijaya', 'nip' => '196812051988031001', 'jenis_gtk' => 'Kepsek', 'gender' => 'L'],
                ['nama' => 'Suryanto, S.Pd', 'nip' => '197503201999031003', 'jenis_gtk' => 'Tenaga Kependidikan', 'gender' => 'L'],
            ];

            foreach ($gtkJenisData as $gtkData) {
                Gtk::create([
                    'nik' => '1234567890' . rand(1000, 9999),
                    'nip' => $gtkData['nip'],
                    'nuptk' => '98' . rand(100000000, 999999999),
                    'nama_gtk' => $gtkData['nama'],
                    'tempat_lahir' => 'Bandung',
                    'tgl_lahir' => now()->subYears(rand(30, 55)),
                    'jenis_gtk' => $gtkData['jenis_gtk'],
                    'jenkel' => $gtkData['gender'],
                    'agama' => 'Islam',
                    'kategori_papua' => 'Non-Papua',
                    'pendidikan_terakhir' => 'S1',
                    'status_kepegawaian' => 'PNS',
                    'golongan_pegawai' => 'III/a',
                    'tmt_pegawai' => now()->subYears(rand(5, 20)),
                    'npwp' => '12.' . rand(1000000000000, 9999999999999) . '.000',
                    'id_sekolah' => $sekolah->id,
                ]);
            }

            // Create Sarpras (Sarana Prasarana) - Peralatan Sekolah
            $sarprases = [
                ['nama' => 'Ruang Kelas X IPA', 'jumlah' => 50, 'baik' => 48, 'rusak' => 2, 'kategori' => 'Kursi dan Meja'],
                ['nama' => 'Ruang Kelas X IPS', 'jumlah' => 50, 'baik' => 50, 'rusak' => 0, 'kategori' => 'Kursi dan Meja'],
                ['nama' => 'Ruang Kelas XI IPA', 'jumlah' => 50, 'baik' => 45, 'rusak' => 5, 'kategori' => 'Kursi dan Meja'],
                ['nama' => 'Ruang Kelas XI IPS', 'jumlah' => 50, 'baik' => 48, 'rusak' => 2, 'kategori' => 'Kursi dan Meja'],
                ['nama' => 'Ruang Kelas XII IPA', 'jumlah' => 50, 'baik' => 49, 'rusak' => 1, 'kategori' => 'Kursi dan Meja'],
                ['nama' => 'Ruang Kelas XII IPS', 'jumlah' => 50, 'baik' => 50, 'rusak' => 0, 'kategori' => 'Kursi dan Meja'],
                ['nama' => 'Lab Komputer', 'jumlah' => 32, 'baik' => 30, 'rusak' => 2, 'kategori' => 'Komputer'],
                ['nama' => 'Lab Biologi', 'jumlah' => 128, 'baik' => 120, 'rusak' => 8, 'kategori' => 'Peralatan Lab'],
                ['nama' => 'Lab Fisika', 'jumlah' => 85, 'baik' => 80, 'rusak' => 5, 'kategori' => 'Peralatan Lab'],
                ['nama' => 'Perpustakaan', 'jumlah' => 2500, 'baik' => 2400, 'rusak' => 100, 'kategori' => 'Buku dan Rak'],
                ['nama' => 'Ruang Guru', 'jumlah' => 30, 'baik' => 28, 'rusak' => 2, 'kategori' => 'Meja dan Kursi'],
                ['nama' => 'Kantor Kepala', 'jumlah' => 15, 'baik' => 15, 'rusak' => 0, 'kategori' => 'Meja dan Kursi'],
                ['nama' => 'Aula Sekolah', 'jumlah' => 300, 'baik' => 280, 'rusak' => 20, 'kategori' => 'Kursi dan Meja Lipat'],
                ['nama' => 'Lapangan Olahraga', 'jumlah' => 1, 'baik' => 1, 'rusak' => 0, 'kategori' => 'Fasilitas Olahraga'],
                ['nama' => 'Dapur Sekolah', 'jumlah' => 8, 'baik' => 7, 'rusak' => 1, 'kategori' => 'Peralatan Dapur'],
            ];

            foreach ($sarprases as $sarprasData) {
                Sarpras::create([
                    'nama_gedung_ruang' => $sarprasData['nama'],
                    'jumlah' => $sarprasData['jumlah'],
                    'baik' => $sarprasData['baik'],
                    'rusak' => $sarprasData['rusak'],
                    'status_kepemilikan' => 'Milik Sekolah',
                    'keterangan' => $sarprasData['kategori'],
                    'id_sekolah' => $sekolah->id,
                ]);
            }

            // Create Gedung & Ruang
            // GedungRuang model tidak ada, skip this part

            // Create Riwayat Pendidikan for GTK
            $gtks = Gtk::where('id_sekolah', $sekolah->id)->take(5)->get();
            foreach ($gtks as $gtk) {
                GtkRiwayatPendidikan::factory()->create([
                    'id_gtk' => $gtk->id,
                ]);
            }
        }
    }
}
