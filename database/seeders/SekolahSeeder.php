<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sekolah;

class SekolahSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama' => 'SMA NEGERI KAITARO', 'npsn' => '69879192', 'jenjang' => 'sma'],
            ['nama' => 'SMKN 1 BINTUNI', 'npsn' => '60403490', 'jenjang' => 'smk'],
            ['nama' => 'SMAN MERDEY', 'npsn' => '60403660', 'jenjang' => 'sma'],
            ['nama' => 'SMAS YPK BINTUNI', 'npsn' => '60401962', 'jenjang' => 'sma'],
            ['nama' => 'SMAN 1 BINTUNI', 'npsn' => '60401961', 'jenjang' => 'sma'],
            ['nama' => 'SMA NEGERI MAYERGA', 'npsn' => '69879197', 'jenjang' => 'sma'],
            ['nama' => 'SMA Negeri Tembuni', 'npsn' => '69935461', 'jenjang' => 'sma'],
            ['nama' => 'SMAN PERSIAPAN BABO', 'npsn' => '60403488', 'jenjang' => 'sma'],
            ['nama' => 'SMAS YPPK ST ARNOLDUS YANSEN', 'npsn' => '60403663', 'jenjang' => 'sma'],
            ['nama' => 'SMAS SANEWESYEN BINTUNI', 'npsn' => '60401960', 'jenjang' => 'sma'],
            ['nama' => 'SMA NEGERI KAMUNDAN', 'npsn' => '69879194', 'jenjang' => 'sma'],
            ['nama' => 'SMAN Saengga', 'npsn' => '69879191', 'jenjang' => 'sma'],
            ['nama' => 'SMAS MUHAMMADIYAH BINTUNI', 'npsn' => '60401959', 'jenjang' => 'sma'],
            ['nama' => 'SMAN TOFOI', 'npsn' => '60403662', 'jenjang' => 'sma'],
            ['nama' => 'SMA NEGERI 2 BINTUNI', 'npsn' => '70049979', 'jenjang' => 'sma'],
            ['nama' => 'SMAN MEYADO', 'npsn' => '60403661', 'jenjang' => 'sma'],
            ['nama' => 'SMA NEGERI ARANDAY', 'npsn' => '60403489', 'jenjang' => 'sma'],
            ['nama' => 'SMA HARMONI SCHOOL TERPADU BINTUNI', 'npsn' => '70025727', 'jenjang' => 'sma'],
            ['nama' => 'SMA NEGERI KURI', 'npsn' => '70050919', 'jenjang' => 'sma'],
        ];

        foreach ($data as $item) {
            Sekolah::create([
                'nama' => $item['nama'],
                'npsn' => $item['npsn'],
                'jenjang' => $item['jenjang'],
                'kabupaten' => 'Teluk Bintuni',
                'provinsi' => 'Papua Barat',
                'status_tanah' => 'shm',
            ]);
        }
    }
}