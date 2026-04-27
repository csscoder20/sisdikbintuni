<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mapel extends Model
{
    use SoftDeletes;
    use \App\Traits\HasActivityLog;
    protected $table = 'mapel';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'jjp',
        'jenjang',
        'tingkat',
    ];

    public function mengajars()
    {
        return $this->hasMany(Mengajar::class);
    }
}
