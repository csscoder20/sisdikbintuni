<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GtkRiwayatPendidikan extends Model
{
    use HasFactory;

    protected $table = 'tbl_gtk_riwayat_pendidikan';

    protected $fillable = [
        'id_gtk',
        'thn_tamat_sd',
        'thn_tamat_smp',
        'thn_tamat_sma',
        'thn_tamat_d1',
        'jurusan_d1',
        'thn_tamat_d2',
        'jurusan_d2',
        'thn_tamat_d3',
        'jurusan_d3',
        'thn_tamat_s1',
        'jurusan_s1',
        'thn_tamat_s2',
        'jurusan_s2',
        'thn_tamat_s3',
        'jurusan_s3',
        'thn_akta_1',
        'jurusan_akta_1',
        'thn_akta_2',
        'jurusan_akta_2',
        'thn_akta_3',
        'jurusan_akta_3',
        'thn_akta_4',
        'jurusan_akta_4',
        'nama_perguruan_tinggi',
        'gelar_akademik'
    ];

    public function gtk()
    {
        return $this->belongsTo(Gtk::class, 'id_gtk');
    }
}
