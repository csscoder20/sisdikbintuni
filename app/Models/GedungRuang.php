<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GedungRuang extends Model
{
    use HasFactory;

    protected $table = 'tbl_gedung_ruang';

    protected $fillable = [
        'nama_gedung_ruang',
        'jumlah',
        'kondisi_baik',
        'kondisi_rusak',
        'status_kepemilikan',
        'id_sekolah'
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }
}
