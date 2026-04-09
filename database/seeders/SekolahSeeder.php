<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sekolah;

class SekolahSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama' => 'SMA NEGERI KAITARO', 'npsn' => '69879192'],
            ['nama' => 'SMKN 1 BINTUNI', 'npsn' => '60403490'],
            ['nama' => 'SMAN MERDEY', 'npsn' => '60403660'],
            ['nama' => 'SMAS YPK BINTUNI', 'npsn' => '60401962'],
            ['nama' => 'SMAN 1 BINTUNI', 'npsn' => '60401961'],
            ['nama' => 'SMA NEGERI MAYERGA', 'npsn' => '69879197'],
            ['nama' => 'SMA Negeri Tembuni', 'npsn' => '69935461'],
            ['nama' => 'SMAN PERSIAPAN BABO', 'npsn' => '60403488'],
            ['nama' => 'SMAS YPPK ST ARNOLDUS YANSEN', 'npsn' => '60403663'],
            ['nama' => 'SMAS SANEWESYEN BINTUNI', 'npsn' => '60401960'],
            ['nama' => 'SMA NEGERI KAMUNDAN', 'npsn' => '69879194'],
            ['nama' => 'SMAN Saengga', 'npsn' => '69879191'],
            ['nama' => 'SMAS MUHAMMADIYAH BINTUNI', 'npsn' => '60401959'],
            ['nama' => 'SMAN TOFOI', 'npsn' => '60403662'],
            ['nama' => 'SMA NEGERI 2 BINTUNI', 'npsn' => '70049979'],
            ['nama' => 'SMAN MEYADO', 'npsn' => '60403661'],
            ['nama' => 'SMA NEGERI ARANDAY', 'npsn' => '60403489'],
            ['nama' => 'SMA HARMONI SCHOOL TERPADU BINTUNI', 'npsn' => '70025727'],
            ['nama' => 'SMA NEGERI KURI', 'npsn' => '70050919'],
        ];

        foreach ($data as $item) {
            Sekolah::create([
                'nama' => $item['nama'],
                'npsn' => $item['npsn'],
                'kabupaten' => 'Teluk Bintuni',
                'provinsi' => 'Papua Barat',
                'status_tanah' => 'shm', // default aman
            ]);
        }
    }
}