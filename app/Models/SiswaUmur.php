<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiswaUmur extends Model
{
    use HasFactory;

    protected $table = 'siswa_umur';

    protected $fillable = [
        'id_rombel',
        'nama_rombel',
        'umur_13_l',
        'umur_13_p',
        'umur_13_jml',
        'umur_14_l',
        'umur_14_p',
        'umur_14_jml',
        'umur_15_l',
        'umur_15_p',
        'umur_15_jml',
        'umur_16_l',
        'umur_16_p',
        'umur_16_jml',
        'umur_17_l',
        'umur_17_p',
        'umur_17_jml',
        'umur_18_l',
        'umur_18_p',
        'umur_18_jml',
        'umur_19_l',
        'umur_19_p',
        'umur_19_jml',
        'umur_20_l',
        'umur_20_p',
        'umur_20_jml',
        'umur_21_l',
        'umur_21_p',
        'umur_21_jml',
        'umur_22_l',
        'umur_22_p',
        'umur_22_jml',
        'umur_23_l',
        'umur_23_p',
        'umur_23_jml',
    ];

    protected $casts = [
        'umur_13_l' => 'integer',
        'umur_13_p' => 'integer',
        'umur_13_jml' => 'integer',
        'umur_14_l' => 'integer',
        'umur_14_p' => 'integer',
        'umur_14_jml' => 'integer',
        'umur_15_l' => 'integer',
        'umur_15_p' => 'integer',
        'umur_15_jml' => 'integer',
        'umur_16_l' => 'integer',
        'umur_16_p' => 'integer',
        'umur_16_jml' => 'integer',
        'umur_17_l' => 'integer',
        'umur_17_p' => 'integer',
        'umur_17_jml' => 'integer',
        'umur_18_l' => 'integer',
        'umur_18_p' => 'integer',
        'umur_18_jml' => 'integer',
        'umur_19_l' => 'integer',
        'umur_19_p' => 'integer',
        'umur_19_jml' => 'integer',
        'umur_20_l' => 'integer',
        'umur_20_p' => 'integer',
        'umur_20_jml' => 'integer',
        'umur_21_l' => 'integer',
        'umur_21_p' => 'integer',
        'umur_21_jml' => 'integer',
        'umur_22_l' => 'integer',
        'umur_22_p' => 'integer',
        'umur_22_jml' => 'integer',
        'umur_23_l' => 'integer',
        'umur_23_p' => 'integer',
        'umur_23_jml' => 'integer',
    ];

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'id_rombel');
    }
}
