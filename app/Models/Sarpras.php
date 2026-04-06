<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sarpras extends Model
{
    protected $table = 'tbl_sarpras';

    protected $fillable = [
        'nama_gedung_ruang',
        'jumlah',
        'baik',
        'rusak',
        'status_kepemilikan',
        'keterangan',
        'id_sekolah',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'baik' => 'integer',
        'rusak' => 'integer',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }
}
