<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GtkPendidikan extends Model
{
    use \App\Traits\HasActivityLog;
    protected $table = 'gtk_pendidikan';

    protected $fillable = [
        'gtk_id',
        'thn_tamat_sd',
        'thn_tamat_smp',
        'thn_tamat_sma',
        'thn_tamat_d1',
        'jurusan_d1',
        'perguruan_tinggi_d1',
        'thn_tamat_d2',
        'jurusan_d2',
        'perguruan_tinggi_d2',
        'thn_tamat_d3',
        'jurusan_d3',
        'perguruan_tinggi_d3',
        'thn_tamat_s1',
        'jurusan_s1',
        'perguruan_tinggi_s1',
        'thn_tamat_s2',
        'jurusan_s2',
        'perguruan_tinggi_s2',
        'thn_tamat_s3',
        'jurusan_s3',
        'perguruan_tinggi_s3',
        'thn_akta4',
        'jurusan_akta4',
        'perguruan_tinggi_akta4',
        'gelar_akademik',
    ];

    public function gtk()
    {
        return $this->belongsTo(Gtk::class);
    }

    /**
     * Relasi untuk mengakses sekolah melalui GTK (Tenancy)
     */
    public function sekolah(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            Sekolah::class,
            Gtk::class,
            'id', // Foreign key on Gtk (Gtk.id)
            'id', // Foreign key on Sekolah (Sekolah.id)
            'gtk_id', // Local key on GtkPendidikan (GtkPendidikan.gtk_id)
            'sekolah_id' // Local key on Gtk (Gtk.sekolah_id)
        );
    }
}
