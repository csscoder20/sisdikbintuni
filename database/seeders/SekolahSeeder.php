<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sekolah;

class SekolahSeeder extends Seeder
{
    public function run()
    {
        $fixCoord = function ($value) {
            if (empty($value) || $value == '.000000000000') {
                return null;
            }

            return round(((float) $value) / 10000000000, 6);
        };
        
        $data = [
                    [
                        'nama' => 'SMA NEGERI KAITARO',
                        'npsn' => '69879192',
                        'jenjang' => 'sma',
                        'alamat' => 'SARA',
                        'akreditasi' => 'C',
                        'latitude' => $fixCoord(-1968177000000),
                        'longitude' => $fixCoord(133116068000000),
                    ],
                    [
                        'nama' => 'SMKN 1 BINTUNI',
                        'npsn' => '60403490',
                        'jenjang' => 'smk',
                        'alamat' => 'JL. RAYA BINTUNI - MANIMERI',
                        'akreditasi' => 'Belum Terakreditasi',
                        'latitude' => $fixCoord(-2081700000000),
                        'longitude' => $fixCoord(133675000000000),
                    ],
                    [
                        'nama' => 'SMAN MERDEY',
                        'npsn' => '60403660',
                        'jenjang' => 'sma',
                        'alamat' => 'MERDEY',
                        'akreditasi' => 'Belum Terakreditasi',
                        'latitude' => $fixCoord(-1583300000000),
                        'longitude' => $fixCoord(133333000000000),
                    ],
                    [
                        'nama' => 'SMAS YPK BINTUNI',
                        'npsn' => '60401962',
                        'jenjang' => 'sma',
                        'alamat' => 'JL. RAYA BINTUNI',
                        'akreditasi' => 'B',
                        'latitude' => $fixCoord(-2123100000000),
                        'longitude' => $fixCoord(133552200000000),
                    ],
                    [
                        'nama' => 'SMAN 1 BINTUNI',
                        'npsn' => '60401961',
                        'jenjang' => 'sma',
                        'alamat' => 'Jl. Raya Bintuni, KM. 5 Wesiri - Bintuni Barat',
                        'akreditasi' => 'A',
                        'latitude' => $fixCoord(-2089900000000),
                        'longitude' => $fixCoord(133496900000000),
                    ],
                    [
                        'nama' => 'SMA NEGERI MAYERGA',
                        'npsn' => '69879197',
                        'jenjang' => 'sma',
                        'alamat' => 'MAYERGA',
                        'akreditasi' => 'C',
                        'latitude' => $fixCoord(-1776700000000),
                        'longitude' => $fixCoord(132746100000000),
                    ],
                    [
                        'nama' => 'SMA Negeri Tembuni',
                        'npsn' => '69935461',
                        'jenjang' => 'sma',
                        'alamat' => 'Kampung Tembuni Rt. 01 Rw. 01',
                        'akreditasi' => 'C',
                        'latitude' => $fixCoord(-2056854100000),
                        'longitude' => $fixCoord(133284697800000),
                    ],
                    [
                        'nama' => 'SMAN PERSIAPAN BABO',
                        'npsn' => '60403488',
                        'jenjang' => 'sma',
                        'alamat' => 'kasira',
                        'akreditasi' => 'B',
                        'latitude' => $fixCoord(-2533300000000),
                        'longitude' => $fixCoord(133441800000000),
                    ],
                    [
                        'nama' => 'SMAS YPPK ST ARNOLDUS YANSEN',
                        'npsn' => '60403663',
                        'jenjang' => 'sma',
                        'alamat' => 'Kampung Wesiri, Km 4 Distrik Bintuni',
                        'akreditasi' => 'Belum Terakreditasi',
                        'latitude' => $fixCoord(-2094600000000),
                        'longitude' => $fixCoord(133505900000000),
                    ],
                    [
                        'nama' => 'SMAS SANEWESYEN BINTUNI',
                        'npsn' => '60401960',
                        'jenjang' => 'sma',
                        'alamat' => 'JL. RAYA BINTUNI',
                        'akreditasi' => 'B',
                        'latitude' => $fixCoord(-2121600000000),
                        'longitude' => $fixCoord(133538800000000),
                    ],
                    [
                        'nama' => 'SMA NEGERI KAMUNDAN',
                        'npsn' => '69879194',
                        'jenjang' => 'sma',
                        'alamat' => 'Kampung Kalitami Distrik Kamundan',
                        'akreditasi' => 'C',
                        'latitude' => $fixCoord(-2176869000000),
                        'longitude' => $fixCoord(132681889000000),
                    ],
                    [
                        'nama' => 'SMAN Saengga',
                        'npsn' => '69879191',
                        'jenjang' => 'sma',
                        'alamat' => 'Saengga',
                        'akreditasi' => 'Belum Terakreditasi',
                        'latitude' => $fixCoord(-2474747000000),
                        'longitude' => $fixCoord(133100682000000),
                    ],
                    [
                        'nama' => 'SMAS MUHAMMADIYAH BINTUNI',
                        'npsn' => '60401959',
                        'jenjang' => 'sma',
                        'alamat' => 'JL. RAYA BINTUNI RT. 01 RW. 02',
                        'akreditasi' => 'Belum Terakreditasi',
                        'latitude' => $fixCoord(-2114000000000),
                        'longitude' => $fixCoord(133527800000000),
                    ],
                    [
                        'nama' => 'SMAN TOFOI',
                        'npsn' => '60403662',
                        'jenjang' => 'sma',
                        'alamat' => 'TOFOI',
                        'akreditasi' => 'B',
                        'latitude' => $fixCoord(-2539000000000),
                        'longitude' => $fixCoord(133250600000000),
                    ],
                    [
                        'nama' => 'SMA NEGERI 2 BINTUNI',
                        'npsn' => '70049979',
                        'jenjang' => 'sma',
                        'alamat' => 'jl. Raya Bintuni - Kampung Idut',
                        'akreditasi' => 'Belum Terakreditasi',
                        'latitude' => $fixCoord(-2099600000000),
                        'longitude' => $fixCoord(133650800000000),
                    ],
                    [
                        'nama' => 'SMAN MEYADO',
                        'npsn' => '60403661',
                        'jenjang' => 'sma',
                        'alamat' => 'Jln.Raya Pasir Putih Meyado',
                        'akreditasi' => 'B',
                        'latitude' => $fixCoord(-1910300000000),
                        'longitude' => $fixCoord(133144800000000),
                    ],
                    [
                        'nama' => 'SMA NEGERI ARANDAY',
                        'npsn' => '60403489',
                        'jenjang' => 'sma',
                        'alamat' => 'KAMPUNG TOMU',
                        'akreditasi' => 'B',
                        'latitude' => $fixCoord(-2217928100000),
                        'longitude' => $fixCoord(132969644900000),
                    ],
                    [
                        'nama' => 'SMA HARMONI SCHOOL TERPADU BINTUNI',
                        'npsn' => '70025727',
                        'jenjang' => 'sma',
                        'alamat' => 'Jl. Kampung Lama',
                        'akreditasi' => 'Belum Terakreditasi',
                        'latitude' => $fixCoord(-2128200000000),
                        'longitude' => $fixCoord(133548400000000),
                    ],
                    [
                        'nama' => 'SMA NEGERI KURI',
                        'npsn' => '70050919',
                        'jenjang' => 'sma',
                        'alamat' => 'KAMPUNG SARBE',
                        'akreditasi' => 'Belum Terakreditasi',
                        'latitude' => null,
                        'longitude' => null,
                    ],
                ];

        foreach ($data as $item) {
            Sekolah::create([
                'nama' => $item['nama'],
                'npsn' => $item['npsn'],
                'jenjang' => $item['jenjang'],
                'kabupaten' => 'Teluk Bintuni',
                'provinsi' => 'Papua Barat',
                'alamat' => $item['alamat'],
                'akreditasi' => $item['akreditasi'],
                'latitude' => $item['latitude'],
                'longitude' => $item['longitude'],
            ]);
        }
    }
}