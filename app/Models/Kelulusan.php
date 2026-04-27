<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelulusan extends Model
{
    use SoftDeletes;
    use \App\Traits\HasActivityLog;
    protected $table = 'kelulusan';

    protected $fillable = [
        'sekolah_id',
        'tahun',
        'jumlah_peserta_ujian',
        'jumlah_lulus',
        'persentase_kelulusan',
        'jumlah_lanjut_pt',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }
}
