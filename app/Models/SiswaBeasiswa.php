<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiswaBeasiswa extends Model
{
    use HasFactory;

    protected $table = 'siswa_beasiswa';

    protected $fillable = [
        'jenis_beasiswa',
        'penerima_l',
        'penerima_p',
        'penerima_jml',
        'keterangan',
    ];

    protected $casts = [
        'penerima_l' => 'integer',
        'penerima_p' => 'integer',
        'penerima_jml' => 'integer',
    ];
}
