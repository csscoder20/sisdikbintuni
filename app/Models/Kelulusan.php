<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelulusan extends Model
{
    protected $table = 'kelulusan';

    protected $fillable = [
        'sekolah_id',
        'tahun',
        'jumlah_lulus',
        'persentase_kelulusan',
        'jumlah_lanjut_pt',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }
}
