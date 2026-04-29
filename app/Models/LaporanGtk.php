<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanGtk extends Model
{
    use SoftDeletes;
    use \App\Traits\HasActivityLog;
    protected $table = 'laporan_gtk';
    protected $fillable = [
        'laporan_id',
        'jenis_gtk',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function kategori()
    {
        return $this->hasMany(LaporanGtkKategori::class);
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
