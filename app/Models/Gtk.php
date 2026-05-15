<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gtk extends Model
{
    use SoftDeletes;
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
        'nama_bank_gaji',
        'no_rek_gaji',
        'nama_bank_tunjangan',
        'no_rek_tunjangan',
        'npwp',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function pendidikan()
    {
        return $this->hasMany(GtkPendidikan::class);
    }

    public function mengajar()
    {
        return $this->hasMany(Mengajar::class);
    }

    public function tugasTambahan()
    {
        return $this->hasOne(GtkTugasTambahan::class);
    }

    public function kehadiran()
    {
        return $this->hasMany(KehadiranGtk::class);
    }

    protected static function booted()
    {
        static::created(function ($gtk) {
            // 1. Sync to Sebaran Jam Mengajar (Principals and Teachers only)
            if ($gtk->jenis_gtk !== 'Tenaga Administrasi') {
                \App\Models\Mengajar::firstOrCreate([
                    'gtk_id' => $gtk->id,
                    'rombel_id' => null,
                    'mapel_id' => null,
                ]);
            }

            // 2. Ensure GtkPendidikan exists
            \App\Models\GtkPendidikan::firstOrCreate(['gtk_id' => $gtk->id]);

        });
    }
}
