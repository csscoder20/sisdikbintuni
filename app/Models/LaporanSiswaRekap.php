<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanSiswaRekap extends Model
{
    protected $table = 'laporan_siswa_rekap';

    protected $fillable = [
        'laporan_siswa_id',
        'kategori',
        'laki_laki',
        'perempuan',
        'total',
    ];

    public function laporanSiswa()
    {
        return $this->belongsTo(LaporanSiswa::class);
    }
}
