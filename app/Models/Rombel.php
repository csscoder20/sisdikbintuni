<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rombel extends Model
{
    use SoftDeletes;
    use \App\Traits\HasActivityLog;
    protected $table = 'rombel';

    protected $fillable = [
        'sekolah_id',
        'nama',
        'tingkat',
    ];

    // Relasi
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'siswa_rombel')
            ->withPivot('tahun_ajaran')
            ->withTimestamps();
    }

    public function mengajar()
    {
        return $this->hasMany(Mengajar::class);
    }
}