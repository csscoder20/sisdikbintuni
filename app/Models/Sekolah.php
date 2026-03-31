<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sekolah extends Model
{
    use HasFactory;

    protected $table = 'tbl_sekolah';

    protected $fillable = [
        'nama_sekolah',
        'npsn',
        'nss',
        'npwp',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'tahun_berdiri',
        'nomor_sk_pendirian',
        'tgl_sk_pendirian',
        'status_sekolah',
        'nama_penyelenggara_yayasan',
        'alamat_penyelenggara_yayasan',
        'sk_pendirian_yayasan',
        'gedung_sekolah',
        'akreditasi_sekolah',
        'status_tanah_sekolah',
        'luas_tanah_sekolah',
        'email_sekolah'
    ];

    public function gedungRuang()
    {
        return $this->hasMany(GedungRuang::class, 'id_sekolah');
    }

    public function rombel()
    {
        return $this->hasMany(Rombel::class, 'id_sekolah');
    }
}
