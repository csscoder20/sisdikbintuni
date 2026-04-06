<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiswaKelasRombel extends Model
{
    use HasFactory;

    protected $table = 'siswa_kelas_rombel';

    protected $fillable = [
        'id_rombel',
        'nama_rombel',
        'awal_bulan_l',
        'awal_bulan_p',
        'awal_bulan_jml',
        'mutasi_l',
        'mutasi_p',
        'mutasi_jml',
        'akhir_bulan_l',
        'akhir_bulan_p',
        'akhir_bulan_jml',
        'pindah_sekolah_l',
        'pindah_sekolah_p',
        'pindah_sekolah_jml',
        'mengulang_l',
        'mengulang_p',
        'mengulang_jml',
    ];

    protected $casts = [
        'awal_bulan_l' => 'integer',
        'awal_bulan_p' => 'integer',
        'awal_bulan_jml' => 'integer',
        'mutasi_l' => 'integer',
        'mutasi_p' => 'integer',
        'mutasi_jml' => 'integer',
        'akhir_bulan_l' => 'integer',
        'akhir_bulan_p' => 'integer',
        'akhir_bulan_jml' => 'integer',
        'pindah_sekolah_l' => 'integer',
        'pindah_sekolah_p' => 'integer',
        'pindah_sekolah_jml' => 'integer',
        'mengulang_l' => 'integer',
        'mengulang_p' => 'integer',
        'mengulang_jml' => 'integer',
    ];

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'id_rombel');
    }
}
