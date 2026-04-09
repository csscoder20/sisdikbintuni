<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Sekolah;
use App\Models\OperatorSekolah;

class OperatorSekolahSeeder extends Seeder
{
    public function run()
    {
        // Ambil user operator
        $op1 = User::where('email', 'operator1@sisdik.com')->first();
        $op2 = User::where('email', 'operator2@sisdik.com')->first();
        $op3 = User::where('email', 'operator3@sisdik.com')->first();

        // Ambil sekolah (berdasarkan urutan)
        $sekolah1 = Sekolah::where('npsn', '69879192')->first(); // SMA NEGERI KAITARO
        $sekolah2 = Sekolah::where('npsn', '60403490')->first(); // SMKN 1 BINTUNI
        $sekolah3 = Sekolah::where('npsn', '60403660')->first(); // SMAN MERDEY

        // Insert relasi
        OperatorSekolah::create([
            'user_id' => $op1->id,
            'sekolah_id' => $sekolah1->id,
            'status' => 'approved',
        ]);

        OperatorSekolah::create([
            'user_id' => $op2->id,
            'sekolah_id' => $sekolah2->id,
            'status' => 'approved',
        ]);

        OperatorSekolah::create([
            'user_id' => $op3->id,
            'sekolah_id' => $sekolah3->id,
            'status' => 'approved',
        ]);
    }
}