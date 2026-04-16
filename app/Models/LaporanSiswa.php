<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class LaporanSiswa extends Model
{
    use \App\Traits\HasActivityLog;
    protected $table = 'laporan_siswa';
    protected $fillable = [
        'laporan_id',
        'rombel_id',
    ];

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class);
    }

    public function rombel(): BelongsTo
    {
        return $this->belongsTo(Rombel::class);
    }

    public function rekap(): HasMany
    {
        return $this->hasMany(LaporanSiswaRekap::class);
    }

    public function kategori(): HasMany
    {
        return $this->hasMany(LaporanSiswaKategori::class);
    }

    /**
     * Relasi untuk mengakses sekolah melalui laporan
     * HasOneThrough memungkinkan akses: LaporanSiswa -> Laporan -> Sekolah
     */
    public function sekolah(): HasOneThrough
    {
        // Parameters: related, through, foreignKey, secondLocalKey, localKey, secondForeignKey
        return $this->hasOneThrough(
            Sekolah::class,    // Target model
            Laporan::class,    // Intermediate model
            'id',              // Foreign key on intermediate model (Laporan.id)
            'id',              // Foreign key on target model (Sekolah.id)
            'laporan_id',      // Local key on current model (LaporanSiswa.laporan_id)
            'sekolah_id'       // Local key on intermediate model (Laporan.sekolah_id)
        );
    }

    /**
     * Alternatif: Menggunakan accessor untuk mendapatkan sekolah
     */
    public function getSekolahAttribute()
    {
        return $this->laporan?->sekolah;
    }
}
