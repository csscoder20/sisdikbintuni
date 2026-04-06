<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SiswaKelasRombel;
use App\Models\SiswaUmur;
use App\Models\SiswaAgama;
use App\Models\SiswaDaerah;
use App\Models\SiswaDisabilitas;
use App\Models\SiswaBeasiswa;

class KeadaanSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Siswa Per Kelas/Rombel
        SiswaKelasRombel::create([
            'nama_rombel' => 'X IPA 1',
            'awal_bulan_l' => 15,
            'awal_bulan_p' => 14,
            'awal_bulan_jml' => 29,
            'mutasi_l' => 1,
            'mutasi_p' => 0,
            'mutasi_jml' => 1,
            'akhir_bulan_l' => 16,
            'akhir_bulan_p' => 14,
            'akhir_bulan_jml' => 30,
            'pindah_sekolah_l' => 0,
            'pindah_sekolah_p' => 1,
            'pindah_sekolah_jml' => 1,
            'mengulang_l' => 0,
            'mengulang_p' => 0,
            'mengulang_jml' => 0,
        ]);

        SiswaKelasRombel::create([
            'nama_rombel' => 'X IPA 2',
            'awal_bulan_l' => 14,
            'awal_bulan_p' => 15,
            'awal_bulan_jml' => 29,
            'mutasi_l' => 0,
            'mutasi_p' => 1,
            'mutasi_jml' => 1,
            'akhir_bulan_l' => 14,
            'akhir_bulan_p' => 16,
            'akhir_bulan_jml' => 30,
            'pindah_sekolah_l' => 0,
            'pindah_sekolah_p' => 0,
            'pindah_sekolah_jml' => 0,
            'mengulang_l' => 1,
            'mengulang_p' => 0,
            'mengulang_jml' => 1,
        ]);

        SiswaKelasRombel::create([
            'nama_rombel' => 'X IPS 1',
            'awal_bulan_l' => 10,
            'awal_bulan_p' => 20,
            'awal_bulan_jml' => 30,
            'mutasi_l' => 0,
            'mutasi_p' => 0,
            'mutasi_jml' => 0,
            'akhir_bulan_l' => 10,
            'akhir_bulan_p' => 20,
            'akhir_bulan_jml' => 30,
            'pindah_sekolah_l' => 0,
            'pindah_sekolah_p' => 0,
            'pindah_sekolah_jml' => 0,
            'mengulang_l' => 0,
            'mengulang_p' => 0,
            'mengulang_jml' => 0,
        ]);

        // Data Siswa Per Umur
        SiswaUmur::create([
            'nama_rombel' => 'X IPA 1',
            'umur_13_l' => 2,
            'umur_13_p' => 3,
            'umur_13_jml' => 5,
            'umur_14_l' => 8,
            'umur_14_p' => 7,
            'umur_14_jml' => 15,
            'umur_15_l' => 5,
            'umur_15_p' => 4,
            'umur_15_jml' => 9,
            'umur_16_l' => 1,
            'umur_16_p' => 0,
            'umur_16_jml' => 1,
            'umur_17_l' => 0,
            'umur_17_p' => 0,
            'umur_17_jml' => 0,
            'umur_18_l' => 0,
            'umur_18_p' => 0,
            'umur_18_jml' => 0,
            'umur_19_l' => 0,
            'umur_19_p' => 0,
            'umur_19_jml' => 0,
            'umur_20_l' => 0,
            'umur_20_p' => 0,
            'umur_20_jml' => 0,
            'umur_21_l' => 0,
            'umur_21_p' => 0,
            'umur_21_jml' => 0,
            'umur_22_l' => 0,
            'umur_22_p' => 0,
            'umur_22_jml' => 0,
            'umur_23_l' => 0,
            'umur_23_p' => 0,
            'umur_23_jml' => 0,
        ]);

        SiswaUmur::create([
            'nama_rombel' => 'X IPA 2',
            'umur_13_l' => 1,
            'umur_13_p' => 2,
            'umur_13_jml' => 3,
            'umur_14_l' => 9,
            'umur_14_p' => 8,
            'umur_14_jml' => 17,
            'umur_15_l' => 4,
            'umur_15_p' => 6,
            'umur_15_jml' => 10,
            'umur_16_l' => 0,
            'umur_16_p' => 0,
            'umur_16_jml' => 0,
            'umur_17_l' => 0,
            'umur_17_p' => 0,
            'umur_17_jml' => 0,
            'umur_18_l' => 0,
            'umur_18_p' => 0,
            'umur_18_jml' => 0,
            'umur_19_l' => 0,
            'umur_19_p' => 0,
            'umur_19_jml' => 0,
            'umur_20_l' => 0,
            'umur_20_p' => 0,
            'umur_20_jml' => 0,
            'umur_21_l' => 0,
            'umur_21_p' => 0,
            'umur_21_jml' => 0,
            'umur_22_l' => 0,
            'umur_22_p' => 0,
            'umur_22_jml' => 0,
            'umur_23_l' => 0,
            'umur_23_p' => 0,
            'umur_23_jml' => 0,
        ]);

        // Data Siswa Per Agama
        SiswaAgama::create([
            'nama_rombel' => 'X IPA 1',
            'islam_l' => 10,
            'islam_p' => 9,
            'islam_jml' => 19,
            'kristen_protestan_l' => 3,
            'kristen_protestan_p' => 2,
            'kristen_protestan_jml' => 5,
            'katolik_l' => 1,
            'katolik_p' => 1,
            'katolik_jml' => 2,
            'hindu_l' => 1,
            'hindu_p' => 1,
            'hindu_jml' => 2,
            'budha_l' => 0,
            'budha_p' => 1,
            'budha_jml' => 1,
            'konghucu_l' => 0,
            'konghucu_p' => 0,
            'konghucu_jml' => 0,
        ]);

        SiswaAgama::create([
            'nama_rombel' => 'X IPA 2',
            'islam_l' => 11,
            'islam_p' => 10,
            'islam_jml' => 21,
            'kristen_protestan_l' => 2,
            'kristen_protestan_p' => 3,
            'kristen_protestan_jml' => 5,
            'katolik_l' => 1,
            'katolik_p' => 2,
            'katolik_jml' => 3,
            'hindu_l' => 0,
            'hindu_p' => 1,
            'hindu_jml' => 1,
            'budha_l' => 0,
            'budha_p' => 0,
            'budha_jml' => 0,
            'konghucu_l' => 0,
            'konghucu_p' => 0,
            'konghucu_jml' => 0,
        ]);

        // Data Siswa Per Daerah
        SiswaDaerah::create([
            'nama_rombel' => 'X IPA 1',
            'papua_l' => 5,
            'papua_p' => 6,
            'papua_jml' => 11,
            'non_papua_l' => 11,
            'non_papua_p' => 8,
            'non_papua_jml' => 19,
        ]);

        SiswaDaerah::create([
            'nama_rombel' => 'X IPA 2',
            'papua_l' => 4,
            'papua_p' => 7,
            'papua_jml' => 11,
            'non_papua_l' => 10,
            'non_papua_p' => 9,
            'non_papua_jml' => 19,
        ]);

        // Data Siswa Disabilitas
        SiswaDisabilitas::create([
            'nama_rombel' => 'X IPA 1',
            'tuna_rungu_l' => 0,
            'tuna_rungu_p' => 1,
            'tuna_rungu_jml' => 1,
            'netra_l' => 1,
            'netra_p' => 0,
            'netra_jml' => 1,
            'wicara_l' => 0,
            'wicara_p' => 0,
            'wicara_jml' => 0,
            'daksa_l' => 1,
            'daksa_p' => 0,
            'daksa_jml' => 1,
            'grahita_l' => 0,
            'grahita_p' => 1,
            'grahita_jml' => 1,
            'lainnya_l' => 1,
            'lainnya_p' => 0,
            'lainnya_jml' => 1,
        ]);

        SiswaDisabilitas::create([
            'nama_rombel' => 'X IPA 2',
            'tuna_rungu_l' => 0,
            'tuna_rungu_p' => 0,
            'tuna_rungu_jml' => 0,
            'netra_l' => 0,
            'netra_p' => 1,
            'netra_jml' => 1,
            'wicara_l' => 1,
            'wicara_p' => 0,
            'wicara_jml' => 1,
            'daksa_l' => 0,
            'daksa_p' => 0,
            'daksa_jml' => 0,
            'grahita_l' => 0,
            'grahita_p' => 1,
            'grahita_jml' => 1,
            'lainnya_l' => 0,
            'lainnya_p' => 0,
            'lainnya_jml' => 0,
        ]);

        // Data Siswa Penerima Beasiswa
        SiswaBeasiswa::create([
            'jenis_beasiswa' => 'Beasiswa Penuh',
            'penerima_l' => 8,
            'penerima_p' => 7,
            'penerima_jml' => 15,
            'keterangan' => 'Siswa berprestasi',
        ]);

        SiswaBeasiswa::create([
            'jenis_beasiswa' => 'Beasiswa Parsial',
            'penerima_l' => 5,
            'penerima_p' => 6,
            'penerima_jml' => 11,
            'keterangan' => 'Siswa kurang mampu',
        ]);

        SiswaBeasiswa::create([
            'jenis_beasiswa' => 'Beasiswa Afirmasi',
            'penerima_l' => 3,
            'penerima_p' => 4,
            'penerima_jml' => 7,
            'keterangan' => 'Siswa dari daerah 3T',
        ]);
    }
}
