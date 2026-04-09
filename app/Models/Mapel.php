<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mapel extends Model
{
    protected $table = 'mapel';

    protected $fillable = [
        'sekolah_id',
        'kode_mapel',
        'nama_mapel',
        'jjp',
        'jenjang',
        'tingkat',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class);
    }
}
