<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'tbl_siswa';

    protected $fillable = [
        'nik',
        'nisn',
        'no_bpjs',
        'nama_siswa',
        'tempat_lahir',
        'tgl_lahir',
        'jenkel',
        'agama',
        'kategori_papua',
        'disabilitas',
        'penerima_beasiswa',
        'id_rombel',
        'nama_ayah',
        'nama_ibu',
        'nama_wali'
    ];

    protected $casts = [
        'tgl_lahir' => 'date'
    ];

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'id_rombel');
    }
}
