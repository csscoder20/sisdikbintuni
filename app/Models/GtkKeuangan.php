<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GtkKeuangan extends Model
{
    use \App\Traits\HasActivityLog;
    protected $table = 'gtk_keuangan';
    protected $fillable = [
        'gtk_id',
        'nomor_rekening',
        'nama_bank',
        'npwp',
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
            'gtk_id', // Local key on GtkKeuangan (GtkKeuangan.gtk_id)
            'sekolah_id' // Local key on Gtk (Gtk.sekolah_id)
        );
    }
}
