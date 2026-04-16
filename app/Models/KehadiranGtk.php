<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KehadiranGtk extends Model
{
    use \App\Traits\HasActivityLog;
    protected $table = 'kehadiran_gtk';
    
    protected $fillable = [
        'gtk_id',
        'laporan_id',
        'sakit',
        'izin',
        'alfa',
        'hari_kerja',
    ];

    public function gtk()
    {
        return $this->belongsTo(Gtk::class);
    }

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
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
            'gtk_id', // Local key on KehadiranGtk (KehadiranGtk.gtk_id)
            'sekolah_id' // Local key on Gtk (Gtk.sekolah_id)
        );
    }
}
