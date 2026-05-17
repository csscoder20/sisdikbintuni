<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\LaporanGedung;
use App\Models\Siswa;
use App\Models\Gtk;
use App\Models\GtkPendidikan;
use App\Models\Mengajar;
use App\Models\KehadiranGtk;
use App\Models\Kelulusan;
use App\Models\Rombel;
use App\Models\LaporanKeuangan;

class Sekolah extends Model
{
    use SoftDeletes;
    use \App\Traits\HasActivityLog;
    protected $table = 'sekolah';

    protected $fillable = [
        'nama',
        'npsn',
        'nss',
        'npwp',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'tahun_berdiri',
        'nomor_sk_pendirian',
        'tanggal_sk_pendirian',
        'nama_yayasan',
        'alamat_yayasan',
        'nomor_sk_yayasan',
        'tanggal_sk_yayasan',
        'akreditasi',
        'jenjang',
        'status_tanah',
        'luas_tanah',
        'email',
        'foto',
        'logo',
        'nama_rekening_bop',
        'nomor_rekening_bop',
        'nama_bank_bop',
        'nama_rekening_bosp',
        'nomor_rekening_bosp',
        'nama_bank_bosp',
        'latitude',
        'longitude',
    ];

    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }

    public function rombel()
    {
        return $this->hasMany(Rombel::class);
    }

    public function gtk()
    {
        return $this->hasMany(Gtk::class);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function operator()
    {
        return $this->hasMany(OperatorSekolah::class);
    }

    public function getRouteKeyName()
    {
        return 'npsn';
    }

    /**
     * Cek apakah sekolah sudah melakukan validasi data untuk periode tertentu
     */
    public function hasValidLaporan(?int $month = null, ?int $year = null): bool
    {
        $month ??= (int) date('m');
        $year ??= (int) date('Y');

        return $this->laporan()
            ->where('bulan', $month)
            ->where('tahun', $year)
            ->exists();
    }

    /**
     * Mendapatkan statistik progres validasi data
     */
    public function getValidationProgress(?int $month = null, ?int $year = null): array
    {
        $month ??= (int) date('m');
        $year ??= (int) date('Y');

        $laporan = $this->laporan()->where('bulan', $month)->where('tahun', $year)->first();

        $checklist = [
            'identitas_sekolah' => 'is_identitas_sekolah_valid',
            'kondisi_sarpras' => 'is_kondisi_sarpras_valid',
            'mapel' => 'is_mapel_valid',
            'rombel' => 'is_siswa_rombel_valid',
            'keuangan' => 'is_keuangan_valid',
            'nominatif_siswa' => 'is_nominatif_siswa_valid',
            'nominatif_gtk' => 'is_nominatif_gtk_valid',
            'riwayat_pendidikan_gtk' => 'is_gtk_pendidikan_valid',
            'rekening_npwp_gtk' => 'is_rekening_npwp_valid',
            'sebaran_jam' => 'is_sebaran_jam_valid',
            'rekap_kehadiran' => 'is_rekap_kehadiran_valid',
        ];

        $done = 0;
        foreach ($checklist as $key => $column) {
            if ($laporan) {
                if ($laporan->$column) {
                    $done++;
                }
            } else {
                if ($this->checkSectionValidity($key)) {
                    $done++;
                }
            }
        }

        return [
            'done' => $done,
            'total' => count($checklist),
            'percentage' => intval(($done / count($checklist)) * 100),
        ];
    }

    /**
     * Cek validitas data secara dinamis per bagian (Sesuai 11 Langkah Wizard Operator)
     */
    public function checkSectionValidity(string $key): bool
    {
        return match ($key) {
            'identitas_sekolah' => $this->isIdentitasValid(),
            'kondisi_sarpras' => LaporanGedung::whereHas('laporan', fn($q) => $q->where('sekolah_id', $this->id))->exists(),
            'mapel' => Mengajar::whereHas('gtk', fn($q) => $q->where('sekolah_id', $this->id))->whereNotNull('mapel_id')->exists(),
            'rombel' => \App\Models\Rombel::where('sekolah_id', $this->id)->exists(),
            'keuangan' => \App\Models\LaporanKeuangan::whereHas('laporan', fn($q) => $q->where('sekolah_id', $this->id))->exists(),
            'nominatif_siswa' => $this->isSiswaDataComplete(),
            'nominatif_gtk' => $this->isGtkDataComplete(),
            'riwayat_pendidikan_gtk' => $this->isPendidikanGtkComplete(),
            'rekening_npwp_gtk' => $this->isRekeningGtkComplete(),
            'sebaran_jam' => $this->isSebaranJamComplete(),
            'rekap_kehadiran' => $this->isKehadiranComplete(),
            default => false,
        };
    }

    /**
     * Cek kelengkapan data Siswa (NIK, NISN, dsb)
     */
    public function isSiswaDataComplete(): bool
    {
        $siswas = $this->siswa;
        if ($siswas->isEmpty()) return false;

        $required = ['nisn', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'alamat', 'daerah_asal'];
        foreach ($siswas as $s) {
            foreach ($required as $field) {
                if (empty($s->$field)) return false;
            }
        }
        return true;
    }

    /**
     * Cek kelengkapan data GTK (NIK, Jenis GTK, dsb)
     */
    public function isGtkDataComplete(): bool
    {
        $gtks = $this->gtk;
        if ($gtks->isEmpty()) return false;

        $required = ['nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'alamat', 'daerah_asal', 'jenis_gtk', 'status_kepegawaian', 'pendidikan_terakhir'];
        foreach ($gtks as $g) {
            foreach ($required as $field) {
                if (empty($g->$field)) return false;
            }
        }
        return true;
    }

    /**
     * Cek kelengkapan Riwayat Pendidikan GTK
     */
    public function isPendidikanGtkComplete(): bool
    {
        $gtks = $this->gtk;
        if ($gtks->isEmpty()) return false;

        foreach ($gtks as $g) {
            if (!$g->pendidikan()->exists()) return false;
            
            $p = $g->pendidikan->first();
            if (empty($p->thn_tamat_s1) || empty($p->jurusan_s1) || empty($p->perguruan_tinggi_s1)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Cek kelengkapan Rekening & NPWP GTK
     */
    public function isRekeningGtkComplete(): bool
    {
        $gtks = $this->gtk;
        if ($gtks->isEmpty()) return false;

        foreach ($gtks as $g) {
            if (empty($g->no_rek_gaji) || empty($g->nama_bank_gaji) || empty($g->npwp)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Cek kelengkapan Sebaran Jam Mengajar
     */
    public function isSebaranJamComplete(): bool
    {
        return Mengajar::whereHas('gtk', fn($q) => $q->where('sekolah_id', $this->id))->exists();
    }

    /**
     * Cek kelengkapan Kehadiran GTK
     */
    public function isKehadiranComplete(): bool
    {
        $gtks = $this->gtk;
        if ($gtks->isEmpty()) return false;

        foreach ($gtks as $g) {
            if (!KehadiranGtk::where('gtk_id', $g->id)->exists()) return false;
        }
        return true;
    }

    /**
     * Cek kelengkapan identitas sekolah
     */
    protected function isIdentitasValid(): bool
    {
        $required = [
            'nama', 'npsn', 'nss', 'npwp', 'email', 'jenjang', 'akreditasi', 'tahun_berdiri',
            'nomor_sk_pendirian', 'tanggal_sk_pendirian', 'provinsi', 'kabupaten', 'kecamatan',
            'desa', 'alamat', 'status_tanah', 'luas_tanah', 'nama_yayasan', 'alamat_yayasan', 'nomor_sk_yayasan'
        ];
        foreach ($required as $field) {
            if (empty($this->$field)) return false;
        }
        return true;
    }
}
