<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mengajar extends Model
{
    use SoftDeletes;
    protected $table = 'gtk_mengajar';

    protected $fillable = [
        'gtk_id',
        'rombel_id',
        'mapel_id',
        'jumlah_jam',
        'semester',
        'tahun_ajaran',
        'laporan_id',
    ];

    public function gtk()
    {
        return $this->belongsTo(Gtk::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function teachingEntries()
    {
        return $this->hasMany(self::class, 'gtk_id', 'gtk_id')
            ->where(function ($query) {
                $query
                    ->whereNotNull('rombel_id')
                    ->orWhereNotNull('mapel_id');
            });
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
            'gtk_id', // Local key on Mengajar (Mengajar.gtk_id)
            'sekolah_id' // Local key on Gtk (Gtk.sekolah_id)
        );
    }
}
