<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiswaDisabilitas extends Model
{
    use HasFactory;

    protected $table = 'siswa_disabilitas';

    protected $fillable = [
        'id_rombel',
        'nama_rombel',
        'tuna_rungu_l',
        'tuna_rungu_p',
        'tuna_rungu_jml',
        'netra_l',
        'netra_p',
        'netra_jml',
        'wicara_l',
        'wicara_p',
        'wicara_jml',
        'daksa_l',
        'daksa_p',
        'daksa_jml',
        'grahita_l',
        'grahita_p',
        'grahita_jml',
        'lainnya_l',
        'lainnya_p',
        'lainnya_jml',
    ];

    protected $casts = [
        'tuna_rungu_l' => 'integer',
        'tuna_rungu_p' => 'integer',
        'tuna_rungu_jml' => 'integer',
        'netra_l' => 'integer',
        'netra_p' => 'integer',
        'netra_jml' => 'integer',
        'wicara_l' => 'integer',
        'wicara_p' => 'integer',
        'wicara_jml' => 'integer',
        'daksa_l' => 'integer',
        'daksa_p' => 'integer',
        'daksa_jml' => 'integer',
        'grahita_l' => 'integer',
        'grahita_p' => 'integer',
        'grahita_jml' => 'integer',
        'lainnya_l' => 'integer',
        'lainnya_p' => 'integer',
        'lainnya_jml' => 'integer',
    ];

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'id_rombel');
    }
}
