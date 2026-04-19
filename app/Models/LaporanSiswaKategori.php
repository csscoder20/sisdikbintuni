<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanSiswaKategori extends Model
{
    protected $table = 'laporan_siswa_kategori';

    protected $fillable = [
        'laporan_siswa_id',  // Perhatikan: ini foreign key ke laporan_siswa
        'jenis_kategori',
        'sub_kategori',
        'laki_laki',
        'perempuan',
        'total',
    ];

    // Relasi ke LaporanSiswa (harus menggunakan nama yang tepat)
    public function laporanSiswa(): BelongsTo
    {
        return $this->belongsTo(LaporanSiswa::class, 'laporan_siswa_id');
    }

    // Alternatif: jika ingin menggunakan nama yang lebih pendek
    public function laporan(): BelongsTo
    {
        return $this->belongsTo(LaporanSiswa::class, 'laporan_siswa_id');
    }
}
