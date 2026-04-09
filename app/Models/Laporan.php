<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Laporan extends Model
{
    protected $table = 'laporan';
    protected $fillable = [
        'sekolah_id',
        'tahun',
        'bulan',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function gedung()
    {
        return $this->hasMany(LaporanGedung::class);
    }

    public function siswa()
    {
        return $this->hasMany(LaporanSiswa::class);
    }

    public function gtk()
    {
        return $this->hasMany(LaporanGtk::class);
    }
}