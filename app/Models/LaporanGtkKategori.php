<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanGtkKategori extends Model
{
    use SoftDeletes;
    protected $table = 'laporan_gtk_kategori';

    protected $fillable = [
        'laporan_gtk_id',
        'jenis_kategori',
        'sub_kategori',
        'jumlah',
    ];

    public function laporanGtk()
    {
        return $this->belongsTo(LaporanGtk::class);
    }
}
