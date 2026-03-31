<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gtk extends Model
{
    use HasFactory;

    protected $table = 'tbl_gtk';

    protected $fillable = [
        'nik',
        'nip',
        'nuptk',
        'nama_gtk',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_gtk',
        'jenkel',
        'agama',
        'kategori_papua',
        'pendidikan_terakhir',
        'status_kepegawaian',
        'golongan_pegawai',
        'tmt_pegawai',
        'tgl_penempatan_sk_terakhir',
        'npwp',
        'no_rekening',
        'id_sekolah'
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah');
    }

    protected $casts = [
        'tgl_lahir' => 'date',
        'tmt_pegawai' => 'date',
        'tgl_penempatan_sk_terakhir' => 'date'
    ];

    public function riwayatPendidikan()
    {
        return $this->hasOne(GtkRiwayatPendidikan::class, 'id_gtk');
    }
}
