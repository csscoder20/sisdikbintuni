<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gtk extends Model
{
    use \App\Traits\HasActivityLog;
    protected $table = 'gtk';
    protected $fillable = [
        'nama',
        'nip',
        'nik',
        'nokarpeg',
        'nuptk',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'pendidikan_terakhir',
        'daerah_asal',
        'jenis_gtk',
        'status_kepegawaian',
        'tmt_pns',
        'pangkat_gol_terakhir',
        'tmt_pangkat_gol_terakhir',
        'sekolah_id',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function pendidikan()
    {
        return $this->hasMany(GtkPendidikan::class);
    }

    public function keuangan()
    {
        return $this->hasOne(GtkKeuangan::class);
    }
}
