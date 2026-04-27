<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;
    use \App\Traits\HasActivityLog;
    protected $table = 'siswa';
    protected $fillable = [
        'nama',
        'nisn',
        'nokk',
        'nik',
        'nobpjs',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'daerah_asal',
        'nama_ayah',
        'nama_ibu',
        'nama_wali',
        'disabilitas',
        'beasiswa',
        'status',
        'sekolah_id',
        'kelas_rombel',
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