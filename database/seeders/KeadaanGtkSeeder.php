<?php

namespace Database\Seeders;

use App\Models\GtkAgama;
use App\Models\GtkDaerah;
use App\Models\GtkStatusKepegawaian;
use App\Models\GtkUmur;
use App\Models\GtkPendidikan;
use Illuminate\Database\Seeder;

class KeadaanGtkSeeder extends Seeder
{
    public function run(): void
    {
        // GTK Agama
        GtkAgama::create([
            'jenis_gtk' => 'Guru Kelas',
            'islam_l' => 45,
            'islam_p' => 38,
            'islam_jml' => 83,
            'kristen_protestan_l' => 15,
            'kristen_protestan_p' => 12,
            'kristen_protestan_jml' => 27,
            'katolik_l' => 5,
            'katolik_p' => 8,
            'katolik_jml' => 13,
            'hindu_l' => 2,
            'hindu_p' => 3,
            'hindu_jml' => 5,
            'budha_l' => 1,
            'budha_p' => 2,
            'budha_jml' => 3,
            'konghucu_l' => 0,
            'konghucu_p' => 0,
            'konghucu_jml' => 0,
        ]);

        GtkAgama::create([
            'jenis_gtk' => 'Guru Mata Pelajaran',
            'islam_l' => 65,
            'islam_p' => 72,
            'islam_jml' => 137,
            'kristen_protestan_l' => 18,
            'kristen_protestan_p' => 16,
            'kristen_protestan_jml' => 34,
            'katolik_l' => 8,
            'katolik_p' => 10,
            'katolik_jml' => 18,
            'hindu_l' => 3,
            'hindu_p' => 2,
            'hindu_jml' => 5,
            'budha_l' => 2,
            'budha_p' => 1,
            'budha_jml' => 3,
            'konghucu_l' => 1,
            'konghucu_p' => 0,
            'konghucu_jml' => 1,
        ]);

        GtkAgama::create([
            'jenis_gtk' => 'Tenaga Kependidikan',
            'islam_l' => 20,
            'islam_p' => 25,
            'islam_jml' => 45,
            'kristen_protestan_l' => 5,
            'kristen_protestan_p' => 6,
            'kristen_protestan_jml' => 11,
            'katolik_l' => 2,
            'katolik_p' => 3,
            'katolik_jml' => 5,
            'hindu_l' => 1,
            'hindu_p' => 0,
            'hindu_jml' => 1,
            'budha_l' => 0,
            'budha_p' => 1,
            'budha_jml' => 1,
            'konghucu_l' => 0,
            'konghucu_p' => 0,
            'konghucu_jml' => 0,
        ]);

        // GTK Daerah
        GtkDaerah::create([
            'jenis_gtk' => 'Guru Kelas',
            'papua_l' => 35,
            'papua_p' => 28,
            'papua_jml' => 63,
            'non_papua_l' => 30,
            'non_papua_p' => 25,
            'non_papua_jml' => 55,
        ]);

        GtkDaerah::create([
            'jenis_gtk' => 'Guru Mata Pelajaran',
            'papua_l' => 55,
            'papua_p' => 62,
            'papua_jml' => 117,
            'non_papua_l' => 36,
            'non_papua_p' => 26,
            'non_papua_jml' => 62,
        ]);

        GtkDaerah::create([
            'jenis_gtk' => 'Tenaga Kependidikan',
            'papua_l' => 18,
            'papua_p' => 20,
            'papua_jml' => 38,
            'non_papua_l' => 10,
            'non_papua_p' => 14,
            'non_papua_jml' => 24,
        ]);

        // GTK Status Kepegawaian
        GtkStatusKepegawaian::create([
            'jenis_gtk' => 'Guru Kelas',
            'pns' => 85,
            'pppk' => 18,
            'honorer_sekolah' => 15,
        ]);

        GtkStatusKepegawaian::create([
            'jenis_gtk' => 'Guru Mata Pelajaran',
            'pns' => 140,
            'pppk' => 25,
            'honorer_sekolah' => 14,
        ]);

        GtkStatusKepegawaian::create([
            'jenis_gtk' => 'Tenaga Kependidikan',
            'pns' => 35,
            'pppk' => 8,
            'honorer_sekolah' => 19,
        ]);

        // GTK Umur
        GtkUmur::create([
            'jenis_gtk' => 'Guru Kelas',
            'umur_13_l' => 0,
            'umur_13_p' => 0,
            'umur_13_jml' => 0,
            'umur_14_l' => 0,
            'umur_14_p' => 0,
            'umur_14_jml' => 0,
            'umur_15_l' => 0,
            'umur_15_p' => 0,
            'umur_15_jml' => 0,
            'umur_16_l' => 0,
            'umur_16_p' => 0,
            'umur_16_jml' => 0,
            'umur_17_l' => 0,
            'umur_17_p' => 0,
            'umur_17_jml' => 0,
            'umur_18_l' => 2,
            'umur_18_p' => 1,
            'umur_18_jml' => 3,
            'umur_19_l' => 3,
            'umur_19_p' => 5,
            'umur_19_jml' => 8,
            'umur_20_l' => 8,
            'umur_20_p' => 9,
            'umur_20_jml' => 17,
            'umur_21_l' => 15,
            'umur_21_p' => 12,
            'umur_21_jml' => 27,
            'umur_22_l' => 22,
            'umur_22_p' => 18,
            'umur_22_jml' => 40,
            'umur_23_l' => 12,
            'umur_23_p' => 10,
            'umur_23_jml' => 22,
        ]);

        GtkUmur::create([
            'jenis_gtk' => 'Guru Mata Pelajaran',
            'umur_13_l' => 0,
            'umur_13_p' => 0,
            'umur_13_jml' => 0,
            'umur_14_l' => 0,
            'umur_14_p' => 0,
            'umur_14_jml' => 0,
            'umur_15_l' => 0,
            'umur_15_p' => 0,
            'umur_15_jml' => 0,
            'umur_16_l' => 0,
            'umur_16_p' => 0,
            'umur_16_jml' => 0,
            'umur_17_l' => 0,
            'umur_17_p' => 0,
            'umur_17_jml' => 0,
            'umur_18_l' => 3,
            'umur_18_p' => 2,
            'umur_18_jml' => 5,
            'umur_19_l' => 5,
            'umur_19_p' => 8,
            'umur_19_jml' => 13,
            'umur_20_l' => 12,
            'umur_20_p' => 15,
            'umur_20_jml' => 27,
            'umur_21_l' => 22,
            'umur_21_p' => 25,
            'umur_21_jml' => 47,
            'umur_22_l' => 18,
            'umur_22_p' => 12,
            'umur_22_jml' => 30,
            'umur_23_l' => 8,
            'umur_23_p' => 5,
            'umur_23_jml' => 13,
        ]);

        GtkUmur::create([
            'jenis_gtk' => 'Tenaga Kependidikan',
            'umur_13_l' => 0,
            'umur_13_p' => 0,
            'umur_13_jml' => 0,
            'umur_14_l' => 0,
            'umur_14_p' => 0,
            'umur_14_jml' => 0,
            'umur_15_l' => 0,
            'umur_15_p' => 0,
            'umur_15_jml' => 0,
            'umur_16_l' => 0,
            'umur_16_p' => 0,
            'umur_16_jml' => 0,
            'umur_17_l' => 0,
            'umur_17_p' => 0,
            'umur_17_jml' => 0,
            'umur_18_l' => 1,
            'umur_18_p' => 2,
            'umur_18_jml' => 3,
            'umur_19_l' => 2,
            'umur_19_p' => 3,
            'umur_19_jml' => 5,
            'umur_20_l' => 4,
            'umur_20_p' => 5,
            'umur_20_jml' => 9,
            'umur_21_l' => 6,
            'umur_21_p' => 7,
            'umur_21_jml' => 13,
            'umur_22_l' => 4,
            'umur_22_p' => 3,
            'umur_22_jml' => 7,
            'umur_23_l' => 2,
            'umur_23_p' => 1,
            'umur_23_jml' => 3,
        ]);

        // GTK Pendidikan
        GtkPendidikan::create([
            'jenis_gtk' => 'Guru Kelas',
            'slta' => 20,
            'di' => 15,
            'dii' => 10,
            'diii' => 25,
            's1' => 80,
            's2' => 8,
            's3' => 0,
        ]);

        GtkPendidikan::create([
            'jenis_gtk' => 'Guru Mata Pelajaran',
            'slta' => 8,
            'di' => 5,
            'dii' => 4,
            'diii' => 18,
            's1' => 140,
            's2' => 25,
            's3' => 2,
        ]);

        GtkPendidikan::create([
            'jenis_gtk' => 'Tenaga Kependidikan',
            'slta' => 30,
            'di' => 8,
            'dii' => 3,
            'diii' => 10,
            's1' => 10,
            's2' => 1,
            's3' => 0,
        ]);
    }
}
