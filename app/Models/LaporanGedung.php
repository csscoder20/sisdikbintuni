<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanGedung extends Model
{
    use SoftDeletes;
    use \App\Traits\HasActivityLog;
    protected $table = 'laporan_gedung';

    protected $fillable = [
        'laporan_id',
        'nama_ruang',
        'jumlah_total',
        'jumlah_baik',
        'jumlah_rusak',
        'status_kepemilikan',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    /**
     * Relasi untuk mengakses sekolah melalui laporan (Tenancy)
     */
    public function sekolah(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            Sekolah::class,
            Laporan::class,
            'id',
            'id',
            'laporan_id',
            'sekolah_id'
        );
    }
}
