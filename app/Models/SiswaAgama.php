<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiswaAgama extends Model
{
    use HasFactory;

    protected $table = 'siswa_agama';

    protected $fillable = [
        'id_rombel',
        'nama_rombel',
        'islam_l',
        'islam_p',
        'islam_jml',
        'kristen_protestan_l',
        'kristen_protestan_p',
        'kristen_protestan_jml',
        'katolik_l',
        'katolik_p',
        'katolik_jml',
        'hindu_l',
        'hindu_p',
        'hindu_jml',
        'budha_l',
        'budha_p',
        'budha_jml',
        'konghucu_l',
        'konghucu_p',
        'konghucu_jml',
    ];

    protected $casts = [
        'islam_l' => 'integer',
        'islam_p' => 'integer',
        'islam_jml' => 'integer',
        'kristen_protestan_l' => 'integer',
        'kristen_protestan_p' => 'integer',
        'kristen_protestan_jml' => 'integer',
        'katolik_l' => 'integer',
        'katolik_p' => 'integer',
        'katolik_jml' => 'integer',
        'hindu_l' => 'integer',
        'hindu_p' => 'integer',
        'hindu_jml' => 'integer',
        'budha_l' => 'integer',
        'budha_p' => 'integer',
        'budha_jml' => 'integer',
        'konghucu_l' => 'integer',
        'konghucu_p' => 'integer',
        'konghucu_jml' => 'integer',
    ];

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'id_rombel');
    }
}
