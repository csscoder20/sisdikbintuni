<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Laporan extends Model
{
    use \App\Traits\HasActivityLog;
    protected $table = 'laporan';
    protected $fillable = [
        'sekolah_id',
        'tahun',
        'bulan',
        'is_identitas_sekolah_valid',
        'is_nominatif_gtk_valid',
        'is_nominatif_siswa_valid',
        'is_kondisi_sarpras_valid',
        'is_kondisi_gtk_valid',
        'is_kondisi_siswa_valid',
        'is_sebaran_jam_valid',
        'is_rekap_kehadiran_valid',
        'is_kelulusan_valid',
        'is_siswa_rombel_valid',
        'is_siswa_umur_valid',
        'is_siswa_agama_valid',
        'is_siswa_daerah_valid',
        'is_siswa_disabilitas_valid',
        'is_siswa_beasiswa_valid',
        'is_gtk_agama_valid',
        'is_gtk_daerah_valid',
        'is_gtk_status_valid',
        'is_gtk_umur_valid',
        'is_gtk_pendidikan_valid',
    ];

    protected $casts = [
        'is_identitas_sekolah_valid' => 'boolean',
        'is_nominatif_gtk_valid' => 'boolean',
        'is_nominatif_siswa_valid' => 'boolean',
        'is_kondisi_sarpras_valid' => 'boolean',
        'is_kondisi_gtk_valid' => 'boolean',
        'is_kondisi_siswa_valid' => 'boolean',
        'is_sebaran_jam_valid' => 'boolean',
        'is_rekap_kehadiran_valid' => 'boolean',
        'is_kelulusan_valid' => 'boolean',
        'is_siswa_rombel_valid' => 'boolean',
        'is_siswa_umur_valid' => 'boolean',
        'is_siswa_agama_valid' => 'boolean',
        'is_siswa_daerah_valid' => 'boolean',
        'is_siswa_disabilitas_valid' => 'boolean',
        'is_siswa_beasiswa_valid' => 'boolean',
        'is_gtk_agama_valid' => 'boolean',
        'is_gtk_daerah_valid' => 'boolean',
        'is_gtk_status_valid' => 'boolean',
        'is_gtk_umur_valid' => 'boolean',
        'is_gtk_pendidikan_valid' => 'boolean',
        'tanggal_submit' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function gedung()
    {
        return $this->hasMany(LaporanGedung::class);
    }

    public function siswa()
    {
        return $this->hasMany(LaporanSiswa::class);
    }

    public function gtk()
    {
        return $this->hasMany(LaporanGtk::class);
    }
}