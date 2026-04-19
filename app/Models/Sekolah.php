<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use \App\Traits\HasActivityLog;
    protected $table = 'sekolah';

    protected $fillable = [
        'nama',
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
        'tanggal_sk_pendirian',
        'nama_yayasan',
        'alamat_yayasan',
        'nomor_sk_yayasan',
        'tanggal_sk_yayasan',
        'akreditasi',
        'jenjang',
        'status_tanah',
        'luas_tanah',
        'email',
    ];

    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }

    public function rombel()
    {
        return $this->hasMany(Rombel::class);
    }

    public function gtk()
    {
        return $this->hasMany(Gtk::class);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function operator()
    {
        return $this->hasMany(OperatorSekolah::class);
    }

    public function getRouteKeyName()
    {
        return 'npsn';
    }
}
