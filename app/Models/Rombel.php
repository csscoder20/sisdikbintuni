<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rombel extends Model
{
    use HasFactory;

    protected $table = 'tbl_rombel';

    protected $fillable = [
        'nama_rombel',
        'id_sekolah'
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_rombel');
    }
}
