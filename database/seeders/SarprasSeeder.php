<?php

namespace Database\Seeders;

use App\Models\Sarpras;
use Illuminate\Database\Seeder;

class SarprasSeeder extends Seeder
{
    public function run(): void
    {
        Sarpras::create([
            'nama_gedung_ruang' => 'Gedung Kelas I',
            'jumlah' => 50,
            'baik' => 48,
            'rusak' => 2,
            'status_kepemilikan' => 'Milik Sekolah',
            'keterangan' => 'Kondisi baik, perlu perbaikan 2 unit',
            'id_sekolah' => 1,
        ]);

        Sarpras::create([
            'nama_gedung_ruang' => 'Gedung Kelas II',
            'jumlah' => 50,
            'baik' => 50,
            'rusak' => 0,
            'status_kepemilikan' => 'Milik Sekolah',
            'keterangan' => 'Semua dalam kondisi baik',
            'id_sekolah' => 1,
        ]);

        Sarpras::create([
            'nama_gedung_ruang' => 'Ruang Perpustakaan',
            'jumlah' => 100,
            'baik' => 95,
            'rusak' => 5,
            'status_kepemilikan' => 'Milik Sekolah',
            'keterangan' => 'Buku dan rak perpustakaan',
            'id_sekolah' => 1,
        ]);

        Sarpras::create([
            'nama_gedung_ruang' => 'Lab Komputer',
            'jumlah' => 30,
            'baik' => 28,
            'rusak' => 2,
            'status_kepemilikan' => 'Milik Sekolah',
            'keterangan' => '2 komputer perlu diperbaiki',
            'id_sekolah' => 1,
        ]);

        Sarpras::create([
            'nama_gedung_ruang' => 'Ruang Guru',
            'jumlah' => 20,
            'baik' => 19,
            'rusak' => 1,
            'status_kepemilikan' => 'Milik Sekolah',
            'keterangan' => 'Meja dan kursi untuk guru',
            'id_sekolah' => 1,
        ]);

        Sarpras::create([
            'nama_gedung_ruang' => 'Aula Sekolah',
            'jumlah' => 200,
            'baik' => 180,
            'rusak' => 20,
            'status_kepemilikan' => 'Milik Sekolah',
            'keterangan' => 'Kursi dan meja lipat',
            'id_sekolah' => 1,
        ]);

        Sarpras::create([
            'nama_gedung_ruang' => 'Lab IPA',
            'jumlah' => 50,
            'baik' => 45,
            'rusak' => 5,
            'status_kepemilikan' => 'Milik Sekolah',
            'keterangan' => 'Peralatan praktikum IPA',
            'id_sekolah' => 1,
        ]);

        Sarpras::create([
            'nama_gedung_ruang' => 'Kantor Tata Usaha',
            'jumlah' => 15,
            'baik' => 15,
            'rusak' => 0,
            'status_kepemilikan' => 'Milik Sekolah',
            'keterangan' => 'Semua peralatan kantor dalam kondisi baik',
            'id_sekolah' => 1,
        ]);

        Sarpras::create([
            'nama_gedung_ruang' => 'Lapangan Olahraga',
            'jumlah' => 100,
            'baik' => 80,
            'rusak' => 20,
            'status_kepemilikan' => 'Milik Sekolah',
            'keterangan' => 'Peralatan olahraga, sebagian perlu diganti',
            'id_sekolah' => 1,
        ]);

        Sarpras::create([
            'nama_gedung_ruang' => 'UKS',
            'jumlah' => 25,
            'baik' => 24,
            'rusak' => 1,
            'status_kepemilikan' => 'Milik Sekolah',
            'keterangan' => 'Peralatan kesehatan',
            'id_sekolah' => 1,
        ]);
    }
}
