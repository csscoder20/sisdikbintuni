<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GtkKehadiran extends Model
{
    protected $table = 'gtk_kehadiran';

    protected $fillable = [
        'gtk_id',
        'laporan_id',
        'tgl_presensi',
        'presensi',
    ];

    protected $casts = [
        'tgl_presensi' => 'date',
    ];

    public function gtk(): BelongsTo
    {
        return $this->belongsTo(Gtk::class);
    }

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class);
    }
}
