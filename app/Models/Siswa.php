<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use \App\Traits\HasActivityLog;
    protected $table = 'siswa';
    protected $fillable = [
        'nama',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        // 'tahun_masuk',
        // 'tahun_keluar',
        'status',
        'sekolah_id',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function rombel()
    {
        return $this->belongsToMany(Rombel::class, 'siswa_rombel')
            ->withPivot('tahun_ajaran')
            ->withTimestamps();
    }
}