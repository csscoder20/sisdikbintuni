<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;
use App\Models\LaporanGedung;
use App\Models\Gtk;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\Laporan;
use App\Models\Sekolah;
use App\Models\KehadiranGtk;
use App\Models\Kelulusan;
use App\Models\Mengajar;
use App\Models\LaporanSiswa;
use App\Models\LaporanSiswaKategori;
use App\Models\LaporanGtk;
use App\Models\LaporanGtkKategori;
use Filament\Facades\Filament;

class OperatorDashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dasbor';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.laporan-bulanan';

    public static function canAccess(): bool
    {
        return auth()->check() && (auth()->user()->hasRole(['operator', 'super_admin', 'admin_dinas']));
    }


    public array $checklist = [
        'identitas_sekolah' => 'IDENTITAS SEKOLAH',
        'kondisi_sarpras' => 'KEADAAN GEDUNG SEKOLAH DAN RUMAH GURU',
        'kondisi_siswa' => 'KEADAAN SISWA',
        'kondisi_gtk' => 'KEADAAN GURU & TENAGA KEPENDIDIKAN',
        'nominatif_gtk' => 'NOMINATIF GURU & TENAGA KEPENDIDIKAN',
        'riwayat_pendidikan_gtk' => 'RIWAYAT PENDIDIKAN GTK',
        'nominatif_siswa' => 'NOMINATIF SISWA',
        'rekening_npwp_gtk' => 'REKENING & NPWP GTK',
        'sebaran_jam' => 'SEBARAN JAM MENGAJAR',
        'rekap_kehadiran' => 'ABSENSI (REKAP KEHADIRAN)',
        'kelulusan' => 'DATA KELULUSAN',
    ];

    public array $groups = [
        'Halaman 1' => ['identitas_sekolah', 'kondisi_sarpras'],
        'Halaman 2' => ['kondisi_siswa', 'kondisi_gtk'],
        'Halaman 3' => ['nominatif_gtk', 'riwayat_pendidikan_gtk'],
        'Halaman 4' => ['nominatif_siswa'],
        'Halaman 5' => ['rekening_npwp_gtk'],
        'Halaman 6' => ['sebaran_jam'],
        'Halaman 7' => ['rekap_kehadiran'],
        'Halaman 8' => ['kelulusan'],
    ];

    public array $checklistStatus = [];
    public array $selectedChecklist = [];
    public ?string $previewType = null;
    public ?int $selectedLaporanId = null;


    public function updatedSelectedChecklist()
    {
        // This will trigger re-render and update preview
    }

    public function getProgressProperty()
    {
        $total = count($this->checklist);
        $done = collect($this->checklistStatus)->filter()->count();

        return $total > 0 ? intval(($done / $total) * 100) : 0;
    }

    public function mount()
    {
        $schoolId = auth()->user()->sekolah?->id;
        $month = (int) date('m');
        $year = (int) date('Y');

        // Fetch the report for the current period
        $laporan = Laporan::where('sekolah_id', $schoolId)
            ->where('bulan', $month)
            ->where('tahun', $year)
            ->first();

        // Initialize status based on Laporan record columns
        foreach ($this->checklist as $key => $label) {
            $column = "is_{$key}_valid";
            $this->checklistStatus[$key] = $laporan ? ($laporan->$column ?? false) : false;
        }

        // Automatic check for identitas_sekolah (as requested, no manual button)
        $this->checklistStatus['identitas_sekolah'] =
            auth()->user()->sekolah?->alamat != null &&
            auth()->user()->sekolah?->nama != null;

        // Virtual status mapping for education and finance (tied to nominatif)
        $this->checklistStatus['riwayat_pendidikan_gtk'] = $this->checklistStatus['nominatif_gtk'] ?? false;
        $this->checklistStatus['rekening_npwp_gtk'] = $this->checklistStatus['nominatif_gtk'] ?? false;
        $this->checklistStatus['kondisi_gtk'] = $this->checklistStatus['kondisi_gtk'] || ($this->checklistStatus['nominatif_gtk'] ?? false);
        $this->checklistStatus['kondisi_siswa'] = $this->checklistStatus['kondisi_siswa'] || ($this->checklistStatus['nominatif_siswa'] ?? false);



        // Set default selected laporan
        if ($laporan) {
            $this->selectedLaporanId = $laporan->id;
        }
    }

    public function getValidatedLaporanList()
    {
        $schoolId = auth()->user()->sekolah?->id;
        if (!$schoolId) return [];

        return Laporan::where('sekolah_id', $schoolId)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get()
            ->map(function ($l) {
                return [
                    'id' => $l->id,
                    'label' => \Carbon\Carbon::createFromDate($l->tahun, $l->bulan, 1)->translatedFormat('F Y'),
                ];
            })->toArray();
    }

    public function setLaporanPreview($id)
    {
        $this->selectedLaporanId = $id;
        $laporan = Laporan::find($id);
        if ($laporan) {
            foreach ($this->checklist as $key => $label) {
                $column = "is_{$key}_valid";
                $this->checklistStatus[$key] = $laporan->$column ?? false;
            }

            // Sync virtual items
            $this->checklistStatus['riwayat_pendidikan_gtk'] = $this->checklistStatus['nominatif_gtk'] ?? false;
            $this->checklistStatus['rekening_npwp_gtk'] = $this->checklistStatus['nominatif_gtk'] ?? false;
            $this->checklistStatus['kondisi_gtk'] = $this->checklistStatus['kondisi_gtk'] || ($this->checklistStatus['nominatif_gtk'] ?? false);
            $this->checklistStatus['kondisi_siswa'] = $this->checklistStatus['kondisi_siswa'] || ($this->checklistStatus['nominatif_siswa'] ?? false);
        }
    }



    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-home';
    }

    public function getTitle(): string | Htmlable
    {
        return 'Dashboard';
    }

    public function getHeading(): string | Htmlable
    {
        return 'Dashboard ' . (auth()->user()->sekolah?->nama ?? 'Sekolah');
    }

    // Method untuk mendapatkan data preview sesuai tipe checklist
    public function getChecklistPreviewData($type)
    {
        $schoolId = auth()->user()->sekolah?->id;

        return match ($type) {
            'identitas_sekolah' => $this->getIdentitasSekolahData($schoolId),
            'kondisi_sarpras' => $this->getKondisiSarprasData($schoolId),
            'kondisi_siswa' => $this->getRekapSiswaData($schoolId),
            'kondisi_gtk' => $this->getRekapGtkData($schoolId),
            'nominatif_gtk' => $this->getNominatifGtkData($schoolId),
            'riwayat_pendidikan_gtk' => $this->getRiwayatPendidikanGtkData($schoolId),
            'nominatif_siswa' => $this->getNominatifSiswaData($schoolId),
            'rekening_npwp_gtk' => $this->getRekeningNpwpGtkData($schoolId),
            'sebaran_jam' => $this->getSebaranJamData($schoolId),
            'rekap_kehadiran' => $this->getRekapKehadiranData($schoolId),
            'kelulusan' => $this->getKelulusanData($schoolId),
            default => []
        };
    }

    private function getIdentitasSekolahData($schoolId)
    {
        $sekolah = Sekolah::find($schoolId);
        if (!$sekolah) return [];

        return [
            ['label' => 'Nama Sekolah', 'value' => $sekolah->nama],
            ['label' => 'Nomor Pokok Sekolah Nasional (NPSN)', 'value' => $sekolah->npsn ?? '-'],
            ['label' => 'Nomor Statistik Sekolah (NSS)', 'value' => $sekolah->nss ?? '-'],
            ['label' => 'NPWP Sekolah', 'value' => $sekolah->npwp ?? '-'],
            ['label' => 'Alamat', 'value' => $sekolah->alamat ?? '-'],
            ['label' => 'Desa/Kelurahan', 'value' => $sekolah->desa ?? '-'],
            ['label' => 'Kecamatan/Distrik', 'value' => $sekolah->kecamatan ?? '-'],
            ['label' => 'Kabupaten', 'value' => $sekolah->kabupaten ?? '-'],
            ['label' => 'Provinsi', 'value' => $sekolah->provinsi ?? '-'],
            ['label' => 'Tahun Pendirian Sekolah', 'value' => $sekolah->tahun_berdiri ?? '-'],
            ['label' => 'SK Pendirian, No. Tgl', 'value' => ($sekolah->nomor_sk_pendirian ?? '-') . ($sekolah->tanggal_sk_pendirian ? ', Tanggal ' . date('d F Y', strtotime($sekolah->tanggal_sk_pendirian)) : '')],
            ['label' => 'Penyelenggaraan', 'value' => '-', 'is_header' => true],
            ['label' => 'a. Nama Penyelenggara/Yayasan', 'value' => $sekolah->nama_yayasan ?? '-', 'is_sub' => true],
            ['label' => 'b. Alamat Badan Penyelenggara/Yayasan', 'value' => $sekolah->alamat_yayasan ?? '-', 'is_sub' => true],
            ['label' => 'c. SK Pendirian Yayasan, Nomor dan tanggal', 'value' => ($sekolah->nomor_sk_yayasan ?? '-') . ($sekolah->tanggal_sk_yayasan ? ', Tanggal ' . date('d F Y', strtotime($sekolah->tanggal_sk_yayasan)) : ''), 'is_sub' => true],
            ['label' => 'Gedung Sekolah', 'value' => '-'],
            ['label' => 'Akreditasi/Tipe', 'value' => $sekolah->akreditasi ?? '-'],
            ['label' => 'Status Tanah Sekolah', 'value' => $sekolah->status_tanah ? strtoupper($sekolah->status_tanah) : '-'],
            ['label' => 'Luas Tanah', 'value' => ($sekolah->luas_tanah ? number_format($sekolah->luas_tanah, 0, ',', '.') : '0') . ' m²'],
            ['label' => 'Email Sekolah', 'value' => $sekolah->email ?? '-']
        ];
    }

    private function getNominatifGtkData($schoolId)
    {
        $gtkList = Gtk::where('sekolah_id', $schoolId)->orderBy('nama', 'asc')->get();

        return $gtkList->map(function ($gtk) {
            return [
                'label' => $gtk->nama ?? 'Tidak tersedia',
                'details' => [
                    'NIP'                     => $gtk->nip ?? '-',
                    'NIK'                     => $gtk->nik ?? '-',
                    'No. Karpeg'              => $gtk->nokarpeg ?? '-',
                    'NUPTK'                   => $gtk->nuptk ?? '-',
                    'Tempat Lahir'            => $gtk->tempat_lahir ?? '-',
                    'Tanggal Lahir'           => $gtk->tanggal_lahir ? date('d-m-Y', strtotime($gtk->tanggal_lahir)) : '-',
                    'JK'                      => $gtk->jenis_kelamin ? (stripos($gtk->jenis_kelamin, 'Laki') !== false ? 'L' : 'P') : '-',
                    'Agama'                   => $gtk->agama ?? '-',
                    'Alamat'                  => $gtk->alamat ?? '-',
                    'Desa'                    => $gtk->desa ?? '-',
                    'Kecamatan'               => $gtk->kecamatan ?? '-',
                    'Kabupaten'               => $gtk->kabupaten ?? '-',
                    'Daerah Asal'             => $gtk->daerah_asal ?? '-',
                    'Jenis GTK'               => $gtk->jenis_gtk ?? '-',
                    'Status Kepegawaian'      => $gtk->status_kepegawaian ?? '-',
                    'TMT PNS'                 => $gtk->tmt_pns ?? '-',
                    'Pangkat / Gol.'          => $gtk->pangkat_gol_terakhir ?? '-',
                    'TMT Pangkat / Gol.'      => $gtk->tmt_pangkat_gol_terakhir ?? '-',
                ]
            ];
        })->toArray();
    }

    private function getRiwayatPendidikanGtkData($schoolId)
    {
        $gtkList = Gtk::with('pendidikan')->where('sekolah_id', $schoolId)->orderBy('nama', 'asc')->get();

        return $gtkList->map(function ($gtk) {
            $p = $gtk->pendidikan->first();
            
            $gd = $p->gelar_depan ?? '';
            if ($gd && !str_ends_with($gd, '.')) $gd .= '.';
            
            $gb = $p->gelar_belakang ?? '';
            if ($gb && !str_ends_with($gb, '.')) $gb .= '.';
            
            return [
                'label' => $gtk->nama ?? 'Tidak tersedia',
                'details' => [
                    'Gelar' => trim("$gd $gb") ?: '-',
                    'Tahun Tamat SD' => $p->thn_tamat_sd ?? '-',
                    'Tahun Tamat SMP' => $p->thn_tamat_smp ?? '-',
                    'Tahun Tamat SMA' => $p->thn_tamat_sma ?? '-',
                    'S1/D4' => ($p->jurusan_s1 ?? '-') . ' / ' . ($p->perguruan_tinggi_s1 ?? '-') . ' (' . ($p->thn_tamat_s1 ?? '-') . ')',
                    'S2' => ($p->jurusan_s2 ?? '-') . ' / ' . ($p->perguruan_tinggi_s2 ?? '-') . ' (' . ($p->thn_tamat_s2 ?? '-') . ')',
                    'S3' => ($p->jurusan_s3 ?? '-') . ' / ' . ($p->perguruan_tinggi_s3 ?? '-') . ' (' . ($p->thn_tamat_s3 ?? '-') . ')',
                ]
            ];
        })->toArray();
    }

    private function getRekeningNpwpGtkData($schoolId)
    {
        $gtkList = Gtk::where('sekolah_id', $schoolId)->orderBy('nama', 'asc')->get();

        return $gtkList->map(function ($gtk) {
            return [
                'label' => $gtk->nama ?? 'Tidak tersedia',
                'details' => [
                    'NIP' => $gtk->nip ?? '-',
                    'Bank Gaji' => $gtk->nama_bank_gaji ?? '-',
                    'No. Rekening Gaji' => $gtk->no_rek_gaji ?? '-',
                    'Bank Tunjangan' => $gtk->nama_bank_tunjangan ?? '-',
                    'No. Rekening Tunjangan' => $gtk->no_rek_tunjangan ?? '-',
                    'NPWP' => $gtk->npwp ?? '-',
                ]
            ];
        })->toArray();
    }

    private function getRekapSiswaData($schoolId)
    {
        $laporanId = $this->selectedLaporanId;
        if (!$laporanId) {
            $laporanId = Laporan::where('sekolah_id', $schoolId)
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->value('id');
        }

        return [
            'type' => 'rekap_siswa_matrix',
            'data' => [
                'perKelas' => $this->getSiswaPerKelasCollection($laporanId, $schoolId),
                'perUmur' => $this->getSiswaPerUmurCollection($laporanId, $schoolId),
                'perAgama' => $this->getSiswaPerAgamaCollection($laporanId, $schoolId),
                'perDaerah' => $this->getSiswaPDaerahCollection($laporanId, $schoolId),
                'disabilitas' => $this->getSiswaDisabilitasCollection($laporanId, $schoolId),
                'beasiswa' => $this->getSiswaBeasiswaCollection($laporanId, $schoolId),
            ]
        ];
    }

    private function getSiswaPerKelasCollection($laporanId, $schoolId)
    {
        $rombels = \App\Models\Rombel::where('sekolah_id', $schoolId)->get();
        if ($laporanId) {
            $laporanSiswas = \App\Models\LaporanSiswa::with(['rombel', 'rekap'])->where('laporan_id', $laporanId)->get()->keyBy('rombel_id');
            return $rombels->map(function ($rombel) use ($laporanSiswas) {
                $ls = $laporanSiswas->get($rombel->id);
                if ($ls) {
                    $rekaps = $ls->rekap->groupBy('kategori');
                    $getRekap = function ($cat) use ($rekaps) {
                        $item = $rekaps->get($cat)?->first();
                        return ['l' => $item?->laki_laki ?? 0, 'p' => $item?->perempuan ?? 0, 'jml' => $item?->total ?? 0];
                    };
                    $awal = $getRekap('awal_bulan');
                    $mutasi_masuk = $getRekap('mutasi_masuk');
                    $mutasi_keluar = $getRekap('mutasi_keluar');
                    $putus = $getRekap('putus_sekolah');
                    $mengulang = $getRekap('mengulang');
                    $akhir = $getRekap('akhir_bulan');
                    return [
                        'nama_rombel' => $rombel->nama,
                        'awal_bulan_l' => $awal['l'], 'awal_bulan_p' => $awal['p'], 'awal_bulan_jml' => $awal['jml'],
                        'mutasi_l' => $mutasi_masuk['l'], 'mutasi_p' => $mutasi_masuk['p'], 'mutasi_jml' => $mutasi_masuk['jml'],
                        'mutasi_keluar_l' => $mutasi_keluar['l'], 'mutasi_keluar_p' => $mutasi_keluar['p'], 'mutasi_keluar_jml' => $mutasi_keluar['jml'],
                        'putus_sekolah_l' => $putus['l'], 'putus_sekolah_p' => $putus['p'], 'putus_sekolah_jml' => $putus['jml'],
                        'mengulang_l' => $mengulang['l'], 'mengulang_p' => $mengulang['p'], 'mengulang_jml' => $mengulang['jml'],
                        'akhir_bulan_l' => $akhir['l'], 'akhir_bulan_p' => $akhir['p'], 'akhir_bulan_jml' => $akhir['jml'],
                    ];
                }
                return ['nama_rombel' => $rombel->nama, 'awal_bulan_l' => 0, 'awal_bulan_p' => 0, 'awal_bulan_jml' => 0, 'mutasi_l' => 0, 'mutasi_p' => 0, 'mutasi_jml' => 0, 'mutasi_keluar_l' => 0, 'mutasi_keluar_p' => 0, 'mutasi_keluar_jml' => 0, 'putus_sekolah_l' => 0, 'putus_sekolah_p' => 0, 'putus_sekolah_jml' => 0, 'mengulang_l' => 0, 'mengulang_p' => 0, 'mengulang_jml' => 0, 'akhir_bulan_l' => 0, 'akhir_bulan_p' => 0, 'akhir_bulan_jml' => 0];
            });
        }
        return $rombels->map(fn($r) => ['nama_rombel' => $r->nama, 'awal_bulan_l' => 0, 'awal_bulan_p' => 0, 'awal_bulan_jml' => 0, 'mutasi_l' => 0, 'mutasi_p' => 0, 'mutasi_jml' => 0, 'mutasi_keluar_l' => 0, 'mutasi_keluar_p' => 0, 'mutasi_keluar_jml' => 0, 'putus_sekolah_l' => 0, 'putus_sekolah_p' => 0, 'putus_sekolah_jml' => 0, 'mengulang_l' => 0, 'mengulang_p' => 0, 'mengulang_jml' => 0, 'akhir_bulan_l' => $r->siswa()->where('jenis_kelamin', 'LIKE', 'L%')->count(), 'akhir_bulan_p' => $r->siswa()->where('jenis_kelamin', 'LIKE', 'P%')->count(), 'akhir_bulan_jml' => $r->siswa()->count()]);
    }

    private function getSiswaPerUmurCollection($laporanId, $schoolId)
    {
        $rombels = \App\Models\Rombel::where('sekolah_id', $schoolId)->get();
        if ($laporanId) {
            $laporanSiswas = \App\Models\LaporanSiswa::where('laporan_id', $laporanId)->with('kategori')->get()->keyBy('rombel_id');
            return $rombels->map(function ($rombel) use ($laporanSiswas) {
                $item = ['nama_rombel' => $rombel->nama];
                for ($age = 13; $age <= 23; $age++) { $px = 'umur_' . $age; $item[$px . '_l'] = 0; $item[$px . '_p'] = 0; $item[$px . '_jml'] = 0; }
                $ls = $laporanSiswas->get($rombel->id);
                if ($ls) {
                    $umurKats = $ls->kategori->where('jenis_kategori', 'umur')->groupBy('sub_kategori');
                    foreach ($umurKats as $sub => $group) {
                        $age = (int) $sub;
                        if ($age >= 13 && $age <= 23) { $px = 'umur_' . $age; $item[$px . '_l'] = $group->sum('laki_laki'); $item[$px . '_p'] = $group->sum('perempuan'); $item[$px . '_jml'] = $group->sum('total'); }
                    }
                }
                return $item;
            });
        }
        return $rombels->map(function ($rombel) {
            $item = ['nama_rombel' => $rombel->nama];
            for ($age = 13; $age <= 23; $age++) { $px = 'umur_' . $age; $item[$px . '_l'] = 0; $item[$px . '_p'] = 0; $item[$px . '_jml'] = 0; }
            $siswas = $rombel->siswa()->whereNotNull('tanggal_lahir')->get();
            foreach ($siswas as $s) {
                $age = \Carbon\Carbon::parse($s->tanggal_lahir)->age;
                if ($age >= 13 && $age <= 23) { $px = 'umur_' . $age; if (str_starts_with(strtoupper($s->jenis_kelamin), 'L')) $item[$px . '_l']++; else $item[$px . '_p']++; $item[$px . '_jml']++; }
            }
            return $item;
        });
    }

    private function getSiswaPerAgamaCollection($laporanId, $schoolId)
    {
        $rombels = \App\Models\Rombel::where('sekolah_id', $schoolId)->get();
        if ($laporanId) {
            $laporanSiswas = \App\Models\LaporanSiswa::where('laporan_id', $laporanId)->with('kategori')->get()->keyBy('rombel_id');
            return $rombels->map(function ($rombel) use ($laporanSiswas) {
                $item = ['nama_rombel' => $rombel->nama];
                foreach (['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'khonghucu'] as $ag) { $item[$ag . '_l'] = 0; $item[$ag . '_p'] = 0; $item[$ag . '_jml'] = 0; }
                $ls = $laporanSiswas->get($rombel->id);
                if ($ls) {
                    $agamaKats = $ls->kategori->where('jenis_kategori', 'agama')->groupBy('sub_kategori');
                    foreach (['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'khonghucu'] as $ag) { $group = $agamaKats->get($ag); if ($group) { $item[$ag . '_l'] = $group->sum('laki_laki'); $item[$ag . '_p'] = $group->sum('perempuan'); $item[$ag . '_jml'] = $group->sum('total'); } }
                }
                return $item;
            });
        }
        return $rombels->map(function ($rombel) {
            $item = ['nama_rombel' => $rombel->nama];
            foreach (['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'khonghucu'] as $ag) { $item[$ag . '_l'] = 0; $item[$ag . '_p'] = 0; $item[$ag . '_jml'] = 0; }
            foreach ($rombel->siswa()->get() as $s) {
                $agama = strtolower($s->agama ?? '');
                $field = null;
                if (str_contains($agama, 'islam')) $field = 'islam';
                elseif (str_contains($agama, 'kristen') || str_contains($agama, 'protestan')) $field = 'kristen';
                elseif (str_contains($agama, 'katolik')) $field = 'katolik';
                elseif (str_contains($agama, 'hindu')) $field = 'hindu';
                elseif (str_contains($agama, 'budha') || str_contains($agama, 'buddha')) $field = 'buddha';
                elseif (str_contains($agama, 'konghucu') || str_contains($agama, 'khonghucu')) $field = 'khonghucu';
                if ($field) { if (str_starts_with(strtoupper($s->jenis_kelamin), 'L')) $item[$field . '_l']++; else $item[$field . '_p']++; $item[$field . '_jml']++; }
            }
            return $item;
        });
    }

    private function getSiswaPDaerahCollection($laporanId, $schoolId)
    {
        $rombels = \App\Models\Rombel::where('sekolah_id', $schoolId)->get();
        if ($laporanId) {
            $laporanSiswas = \App\Models\LaporanSiswa::where('laporan_id', $laporanId)->with('kategori')->get()->keyBy('rombel_id');
            return $rombels->map(function ($rombel) use ($laporanSiswas) {
                $item = ['nama_rombel' => $rombel->nama, 'papua_l' => 0, 'papua_p' => 0, 'papua_jml' => 0, 'non_papua_l' => 0, 'non_papua_p' => 0, 'non_papua_jml' => 0];
                $ls = $laporanSiswas->get($rombel->id);
                if ($ls) {
                    $daerahKats = $ls->kategori->where('jenis_kategori', 'asal_daerah')->groupBy('sub_kategori');
                    foreach (['papua', 'non_papua'] as $sub) { $group = $daerahKats->get($sub); if ($group) { $item[$sub . '_l'] = $group->sum('laki_laki'); $item[$sub . '_p'] = $group->sum('perempuan'); $item[$sub . '_jml'] = $group->sum('total'); } }
                }
                return $item;
            });
        }
        return $rombels->map(function ($rombel) {
            $item = ['nama_rombel' => $rombel->nama, 'papua_l' => 0, 'papua_p' => 0, 'papua_jml' => 0, 'non_papua_l' => 0, 'non_papua_p' => 0, 'non_papua_jml' => 0];
            foreach ($rombel->siswa()->get() as $s) {
                $daerah = strtolower($s->daerah_asal ?? '');
                $cat = (str_contains($daerah, 'papua') && !str_contains($daerah, 'non')) ? 'papua' : 'non_papua';
                if (str_starts_with(strtoupper($s->jenis_kelamin), 'L')) $item[$cat . '_l']++; else $item[$cat . '_p']++; $item[$cat . '_jml']++;
            }
            return $item;
        });
    }

    private function getSiswaDisabilitasCollection($laporanId, $schoolId)
    {
        $categories = ['tidak' => 'Tidak', 'tuna_netra' => 'Tuna Netra', 'tuna_rungu' => 'Tuna Rungu', 'tuna_wicara' => 'Tuna Wicara', 'tuna_daksa' => 'Tuna Daksa', 'tuna_grahita' => 'Tuna Grahita', 'tuna_lainnya' => 'Tuna Lainnya'];
        if ($laporanId) {
            $counts = \App\Models\LaporanSiswaKategori::where('jenis_kategori', 'disabilitas')->whereHas('laporanSiswa', fn($q) => $q->where('laporan_id', $laporanId))->get()->groupBy('sub_kategori');
            return collect($categories)->map(fn($label, $key) => ['jenis_disabilitas' => $label, 'laki_laki' => ($counts->get($key) ?: collect())->sum('laki_laki'), 'perempuan' => ($counts->get($key) ?: collect())->sum('perempuan'), 'total' => ($counts->get($key) ?: collect())->sum('total')])->values();
        }
        return collect($categories)->map(function ($label, $key) use ($schoolId) {
            $l = Siswa::where('sekolah_id', $schoolId)->where('disabilitas', $key)->where('jenis_kelamin', 'LIKE', 'L%')->count();
            $p = Siswa::where('sekolah_id', $schoolId)->where('disabilitas', $key)->where('jenis_kelamin', 'LIKE', 'P%')->count();
            return ['jenis_disabilitas' => $label, 'laki_laki' => $l, 'perempuan' => $p, 'total' => $l + $p];
        })->values();
    }

    private function getSiswaBeasiswaCollection($laporanId, $schoolId)
    {
        $categories = ['tidak' => 'Tidak', 'beasiswa_pemerintah_pusat' => 'Beasiswa Pemerintah Pusat', 'beasiswa_pemerintah_daerah' => 'Beasiswa Pemerintah Daerah', 'beasisswa_swasta' => 'Beasiswa Swasta', 'beasiswa_khusus' => 'Beasiswa Khusus', 'beasiswa_afirmasi' => 'Beasiswa Afirmasi', 'beasiswa_lainnya' => 'Beasiswa Lainnya'];
        if ($laporanId) {
            $counts = \App\Models\LaporanSiswaKategori::where('jenis_kategori', 'beasiswa')->whereHas('laporanSiswa', fn($q) => $q->where('laporan_id', $laporanId))->get()->groupBy('sub_kategori');
            return collect($categories)->map(fn($label, $key) => ['jenis_beasiswa' => $label, 'penerima_l' => ($counts->get($key) ?: collect())->sum('laki_laki'), 'penerima_p' => ($counts->get($key) ?: collect())->sum('perempuan'), 'penerima_jml' => ($counts->get($key) ?: collect())->sum('total'), 'keterangan' => ''])->values();
        }
        return collect($categories)->map(function ($label, $key) use ($schoolId) {
            $l = Siswa::where('sekolah_id', $schoolId)->where('beasiswa', $key)->where('jenis_kelamin', 'LIKE', 'L%')->count();
            $p = Siswa::where('sekolah_id', $schoolId)->where('beasiswa', $key)->where('jenis_kelamin', 'LIKE', 'P%')->count();
            return ['jenis_beasiswa' => $label, 'penerima_l' => $l, 'penerima_p' => $p, 'penerima_jml' => $l + $p, 'keterangan' => ''];
        })->values();
    }

    private function getRekapGtkData($schoolId)
    {
        $laporanId = $this->selectedLaporanId;
        if (!$laporanId) {
            $laporanId = Laporan::where('sekolah_id', $schoolId)
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->value('id');
        }

        return [
            'type' => 'rekap_gtk_matrix',
            'data' => [
                'agama' => $this->getGtkAgamaCollection($laporanId, $schoolId),
                'daerah' => $this->getGtkDaerahCollection($laporanId, $schoolId),
                'status' => $this->getGtkStatusCollection($laporanId, $schoolId),
                'umur' => $this->getGtkUmurCollection($laporanId, $schoolId),
                'pendidikan' => $this->getGtkPendidikanCollection($laporanId, $schoolId),
                'jenis' => $this->getGtkByJenisCollection($laporanId, $schoolId),
            ]
        ];
    }

    private function getGtkAgamaCollection($laporanId, $schoolId)
    {
        if (!$laporanId) return collect(['kepala_sekolah', 'guru', 'tenaga_administrasi'])->map(fn($t) => (object) ['jenis_gtk' => $this->getJenisGtkLabel($t), 'islam_l' => 0, 'islam_p' => 0, 'islam_jml' => 0, 'kristen_protestan_l' => 0, 'kristen_protestan_p' => 0, 'kristen_protestan_jml' => 0, 'katolik_l' => 0, 'katolik_p' => 0, 'katolik_jml' => 0, 'hindu_l' => 0, 'hindu_p' => 0, 'hindu_jml' => 0, 'budha_l' => 0, 'budha_p' => 0, 'budha_jml' => 0, 'konghucu_l' => 0, 'konghucu_p' => 0, 'konghucu_jml' => 0]);
        return \App\Models\LaporanGtk::with('kategori')->where('laporan_id', $laporanId)->orderByRaw("CASE jenis_gtk WHEN 'kepala_sekolah' THEN 1 WHEN 'guru' THEN 2 WHEN 'tenaga_administrasi' THEN 3 ELSE 4 END")->get()->map(function ($lg) {
            $row = (object) ['jenis_gtk' => $this->getJenisGtkLabel($lg->jenis_gtk)];
            foreach (['islam', 'kristen_protestan', 'katolik', 'hindu', 'budha', 'konghucu'] as $ag) {
                $row->{$ag . '_l'} = ($lg->kategori->first(fn($k) => $k->jenis_kategori === 'agama' && $k->sub_kategori === "{$ag}_l")?->jumlah ?? 0);
                $row->{$ag . '_p'} = ($lg->kategori->first(fn($k) => $k->jenis_kategori === 'agama' && $k->sub_kategori === "{$ag}_p")?->jumlah ?? 0);
                $row->{$ag . '_jml'} = ($lg->kategori->first(fn($k) => $k->jenis_kategori === 'agama' && $k->sub_kategori === "{$ag}_jml")?->jumlah ?: $row->{$ag . '_l'} + $row->{$ag . '_p'});
            }
            return $row;
        });
    }

    private function getGtkDaerahCollection($laporanId, $schoolId)
    {
        if (!$laporanId) return collect(['kepala_sekolah', 'guru', 'tenaga_administrasi'])->map(fn($t) => (object) ['jenis_gtk' => $this->getJenisGtkLabel($t), 'papua_l' => 0, 'papua_p' => 0, 'papua_jml' => 0, 'non_papua_l' => 0, 'non_papua_p' => 0, 'non_papua_jml' => 0]);
        return \App\Models\LaporanGtk::with('kategori')->where('laporan_id', $laporanId)->orderByRaw("CASE jenis_gtk WHEN 'kepala_sekolah' THEN 1 WHEN 'guru' THEN 2 WHEN 'tenaga_administrasi' THEN 3 ELSE 4 END")->get()->map(function ($lg) {
            $row = (object) ['jenis_gtk' => $this->getJenisGtkLabel($lg->jenis_gtk)];
            foreach (['papua', 'non_papua'] as $d) {
                $row->{$d . '_l'} = ($lg->kategori->first(fn($k) => $k->jenis_kategori === 'daerah' && $k->sub_kategori === "{$d}_l")?->jumlah ?? 0);
                $row->{$d . '_p'} = ($lg->kategori->first(fn($k) => $k->jenis_kategori === 'daerah' && $k->sub_kategori === "{$d}_p")?->jumlah ?? 0);
                $row->{$d . '_jml'} = ($lg->kategori->first(fn($k) => $k->jenis_kategori === 'daerah' && $k->sub_kategori === "{$d}_jml")?->jumlah ?: $row->{$d . '_l'} + $row->{$d . '_p'});
            }
            return $row;
        });
    }

    private function getGtkStatusCollection($laporanId, $schoolId)
    {
        $cols = ['gol_i_a', 'gol_i_b', 'gol_i_c', 'gol_i_d', 'gol_ii_a', 'gol_ii_b', 'gol_ii_c', 'gol_ii_d', 'gol_iii_a', 'gol_iii_b', 'gol_iii_c', 'gol_iii_d', 'gol_iv_a', 'gol_iv_b', 'gol_iv_c', 'gol_iv_d', 'gol_iv_e', 'pppk', 'honorer_sekolah'];
        if (!$laporanId) return collect(['kepala_sekolah', 'guru', 'tenaga_administrasi'])->map(fn($t) => (object) array_merge(['jenis_gtk' => $this->getJenisGtkLabel($t)], array_fill_keys($cols, 0)));
        return \App\Models\LaporanGtk::with('kategori')->where('laporan_id', $laporanId)->orderByRaw("CASE jenis_gtk WHEN 'kepala_sekolah' THEN 1 WHEN 'guru' THEN 2 WHEN 'tenaga_administrasi' THEN 3 ELSE 4 END")->get()->map(function ($lg) use ($cols) {
            $row = (object) ['jenis_gtk' => $this->getJenisGtkLabel($lg->jenis_gtk)];
            foreach ($cols as $c) { $row->{$c} = ($lg->kategori->first(fn($k) => $k->jenis_kategori === 'status_kepegawaian' && $k->sub_kategori === $c)?->jumlah ?? 0); }
            return $row;
        });
    }

    private function getGtkUmurCollection($laporanId, $schoolId)
    {
        $ranges = ['umur_20_29', 'umur_30_39', 'umur_40_49', 'umur_50_59', 'umur_60_ke_atas'];
        if (!$laporanId) return collect(['kepala_sekolah', 'guru', 'tenaga_administrasi'])->map(fn($t) => (object) array_merge(['jenis_gtk' => $this->getJenisGtkLabel($t)], array_fill_keys(array_map(fn($r) => [$r . '_l', $r . '_p', $r . '_jml'], $ranges), 0))); // simplified
        return \App\Models\LaporanGtk::with('kategori')->where('laporan_id', $laporanId)->orderByRaw("CASE jenis_gtk WHEN 'kepala_sekolah' THEN 1 WHEN 'guru' THEN 2 WHEN 'tenaga_administrasi' THEN 3 ELSE 4 END")->get()->map(function ($lg) use ($ranges) {
            $row = (object) ['jenis_gtk' => $this->getJenisGtkLabel($lg->jenis_gtk)];
            foreach ($ranges as $r) {
                $row->{$r . '_l'} = ($lg->kategori->first(fn($k) => $k->jenis_kategori === 'umur' && $k->sub_kategori === "{$r}_l")?->jumlah ?? 0);
                $row->{$r . '_p'} = ($lg->kategori->first(fn($k) => $k->jenis_kategori === 'umur' && $k->sub_kategori === "{$r}_p")?->jumlah ?? 0);
                $row->{$r . '_jml'} = ($lg->kategori->first(fn($k) => $k->jenis_kategori === 'umur' && $k->sub_kategori === "{$r}_jml")?->jumlah ?: $row->{$r . '_l'} + $row->{$r . '_p'});
            }
            return $row;
        });
    }

    private function getGtkPendidikanCollection($laporanId, $schoolId)
    {
        $cols = ['slta', 'di', 'dii', 'diii', 's1', 's2', 's3'];
        if (!$laporanId) return collect(['kepala_sekolah', 'guru', 'tenaga_administrasi'])->map(fn($t) => (object) array_merge(['jenis_gtk' => $this->getJenisGtkLabel($t)], array_fill_keys($cols, 0)));
        return \App\Models\LaporanGtk::with('kategori')->where('laporan_id', $laporanId)->orderByRaw("CASE jenis_gtk WHEN 'kepala_sekolah' THEN 1 WHEN 'guru' THEN 2 WHEN 'tenaga_administrasi' THEN 3 ELSE 4 END")->get()->map(function ($lg) use ($cols) {
            $row = (object) ['jenis_gtk' => $this->getJenisGtkLabel($lg->jenis_gtk)];
            foreach ($cols as $c) { $row->{$c} = ($lg->kategori->first(fn($k) => $k->jenis_kategori === 'pendidikan' && $k->sub_kategori === $c)?->jumlah ?? 0); }
            return $row;
        });
    }

    private function getGtkByJenisCollection($laporanId, $schoolId)
    {
        if (!$laporanId) return collect(['kepala_sekolah', 'guru', 'tenaga_administrasi'])->map(fn($t) => (object) ['jenis_gtk' => $this->getJenisGtkLabel($t), 'laki_laki' => 0, 'perempuan' => 0, 'total' => 0]);
        return \App\Models\LaporanGtk::with('kategori')->where('laporan_id', $laporanId)->orderByRaw("CASE jenis_gtk WHEN 'kepala_sekolah' THEN 1 WHEN 'guru' THEN 2 WHEN 'tenaga_administrasi' THEN 3 ELSE 4 END")->get()->map(function ($lg) {
            $row = (object) ['jenis_gtk' => $this->getJenisGtkLabel($lg->jenis_gtk)];
            $row->laki_laki = $lg->kategori->where('jenis_kategori', 'agama')->where('sub_kategori', 'LIKE', '%_l')->sum('jumlah');
            $row->perempuan = $lg->kategori->where('jenis_kategori', 'agama')->where('sub_kategori', 'LIKE', '%_p')->sum('jumlah');
            $row->total = $row->laki_laki + $row->perempuan;
            return $row;
        });
    }

    private function getJenisGtkLabel($type)
    {
        return match ($type) { 'kepala_sekolah' => 'Kepala Sekolah', 'tenaga_administrasi' => 'Tenaga Administrasi', 'guru' => 'Guru', default => ucfirst($type) };
    }

    private function getNominatifSiswaData($schoolId)
    {
        $siswaList = Siswa::where('sekolah_id', $schoolId)->orderBy('nama', 'asc')->get();

        return $siswaList->map(function ($siswa) {
            return [
                'label' => $siswa->nama ?? 'Tidak tersedia',
                'details' => [
                    'NISN'          => $siswa->nisn ?? '-',
                    'NIK'           => $siswa->nik ?? '-',
                    'Tempat Lahir'  => $siswa->tempat_lahir ?? '-',
                    'Tanggal Lahir' => $siswa->tanggal_lahir ? date('d-m-Y', strtotime($siswa->tanggal_lahir)) : '-',
                    'JK'            => $siswa->jenis_kelamin ? (stripos($siswa->jenis_kelamin, 'Laki') !== false ? 'L' : 'P') : '-',
                    'Agama'         => $siswa->agama ?? '-',
                    'Alamat'        => $siswa->alamat ?? '-',
                    'Desa'          => $siswa->desa ?? '-',
                    'Kecamatan'     => $siswa->kecamatan ?? '-',
                    'Kabupaten'     => $siswa->kabupaten ?? '-',
                    'Status'        => $siswa->status ?? '-',
                    'Rombel'        => $siswa->rombel->pluck('nama')->implode(', ') ?: '-',
                ]
            ];
        })->toArray();
    }

    private function getKondisiSarprasData($schoolId)
    {
        $laporanId = $this->selectedLaporanId;
        
        $query = LaporanGedung::query();
        if ($laporanId) {
            $query->where('laporan_id', $laporanId);
        } else {
            $query->whereHas('laporan', function($q) use ($schoolId) {
                $q->where('sekolah_id', $schoolId);
            });
        }
        
        $sarpasList = $query->get();

        // Fallback to latest available sarpras data for the school if selected report has none
        if ($sarpasList->isEmpty()) {
            $sarpasList = LaporanGedung::whereHas('laporan', function($q) use ($schoolId) {
                $q->where('sekolah_id', $schoolId);
            })->orderBy('laporan_id', 'desc')->get();
        }

        return $sarpasList->map(function ($sarpras) {
            return [
                'label' => $sarpras->nama_ruang ?? 'Tidak tersedia',
                'details' => [
                    'Jumlah' => $sarpras->jumlah_total ?? 0,
                    'Tingkat Kerusakan_Baik' => $sarpras->jumlah_baik ?? 0,
                    'Tingkat Kerusakan_Rusak' => $sarpras->jumlah_rusak ?? 0,
                    'Status Kepemilikan_Milik' => strtolower($sarpras->status_kepemilikan) === 'milik' ? '✓' : '-',
                    'Status Kepemilikan_Bukan Milik' => strtolower($sarpras->status_kepemilikan) !== 'milik' ? '✓' : '-',
                ]
            ];
        })->toArray();
    }

    private function getKondisiGtkData($schoolId, $type)
    {
        $gtkList = Gtk::where('sekolah_id', $schoolId)->get();

        return $gtkList->map(function ($gtk) use ($type) {
            $details = [];

            if ($type === 'gtk_status') {
                $details = [
                    'NIP' => $gtk->nip ?? '-',
                    'Status Kepegawaian' => $gtk->status_kepegawaian ?? '-',
                    'Pangkat Gol Terakhir' => $gtk->pangkat_gol_terakhir ?? '-',
                ];
            } elseif ($type === 'gtk_umur') {
                $details = [
                    'NIP' => $gtk->nip ?? '-',
                    'Tempat Lahir' => $gtk->tempat_lahir ?? '-',
                    'Tanggal Lahir' => $gtk->tanggal_lahir ? date('d-m-Y', strtotime($gtk->tanggal_lahir)) : '-',
                    'Umur' => $gtk->tanggal_lahir ? \Carbon\Carbon::parse($gtk->tanggal_lahir)->age : '-',
                ];
            } elseif ($type === 'gtk_pendidikan') {
                $details = [
                    'NIP' => $gtk->nip ?? '-',
                    'NIK' => $gtk->nik ?? '-',
                    'Pendidikan Terakhir' => $gtk->pendidikan_terakhir ?? '-',
                ];
            } elseif ($type === 'gtk_daerah') {
                $details = [
                    'NIP' => $gtk->nip ?? '-',
                    'NIK' => $gtk->nik ?? '-',
                    'Jenis Kelamin' => $gtk->jenis_kelamin ?? '-',
                    'Daerah Asal' => $gtk->daerah_asal ?? '-',
                ];
            } else { // default or gtk_agama
                $details = [
                    'NIP' => $gtk->nip ?? '-',
                    'NIK' => $gtk->nik ?? '-',
                    'Jenis Kelamin' => $gtk->jenis_kelamin ?? '-',
                    'Agama' => $gtk->agama ?? '-',
                ];
            }

            return [
                'label' => $gtk->nama ?? 'Tidak tersedia',
                'details' => $details
            ];
        })->toArray();
    }

    private function getKondisiSiswaData($schoolId, $type)
    {
        $siswaList = Siswa::where('sekolah_id', $schoolId)->get();

        return $siswaList->map(function ($siswa) use ($type) {
            $details = [];

            if ($type === 'siswa_rombel') {
                $details = [
                    'NISN' => $siswa->nisn ?? '-',
                    'NIK' => $siswa->nik ?? '-',
                    'Jenis Kelamin' => $siswa->jenis_kelamin ?? '-',
                    'Rombel' => $siswa->rombel->pluck('nama')->first() ?: '-',
                ];
            } elseif ($type === 'siswa_umur') {
                $details = [
                    'NISN' => $siswa->nisn ?? '-',
                    'Tempat Lahir' => $siswa->tempat_lahir ?? '-',
                    'Tanggal Lahir' => $siswa->tanggal_lahir ? date('d-m-Y', strtotime($siswa->tanggal_lahir)) : '-',
                    'Umur' => $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->age : '-',
                ];
            } elseif ($type === 'siswa_daerah') {
                $details = [
                    'NISN' => $siswa->nisn ?? '-',
                    'Jenis Kelamin' => $siswa->jenis_kelamin ?? '-',
                    'Alamat' => $siswa->alamat ?? '-',
                ];
            } elseif ($type === 'siswa_disabilitas') {
                $details = [
                    'NISN' => $siswa->nisn ?? '-',
                    'NIK' => $siswa->nik ?? '-',
                    'Jenis Kelamin' => $siswa->jenis_kelamin ?? '-',
                    'Disabilitas' => $siswa->disabilitas ?? '-',
                ];
            } elseif ($type === 'siswa_beasiswa') {
                $details = [
                    'NISN' => $siswa->nisn ?? '-',
                    'NIK' => $siswa->nik ?? '-',
                    'Jenis Kelamin' => $siswa->jenis_kelamin ?? '-',
                    'Beasiswa' => $siswa->beasiswa ?? '-',
                ];
            } else { // agama, default
                $details = [
                    'NISN' => $siswa->nisn ?? '-',
                    'NIK' => $siswa->nik ?? '-',
                    'Jenis Kelamin' => $siswa->jenis_kelamin ?? '-',
                    'Agama' => $siswa->agama ?? '-',
                ];
            }

            return [
                'label' => $siswa->nama ?? 'Tidak tersedia',
                'details' => $details
            ];
        })->toArray();
    }

    private function getSebaranJamData($schoolId)
    {
        $mengajars = Mengajar::whereHas('gtk', function($q) use ($schoolId) {
            $q->where('sekolah_id', $schoolId);
        })->get();

        return $mengajars->map(function ($m) {
            return [
                'label' => $m->gtk->nama ?? 'Tidak tersedia',
                'details' => [
                    'Rombel' => $m->rombel->nama ?? '-',
                    'Mata Pelajaran' => $m->mapel->nama_mapel ?? '-',
                    'Jam' => $m->jumlah_jam ?? 0,
                    'Semester' => ucfirst($m->semester ?? '-'),
                    'Tahun Ajaran' => $m->tahun_ajaran ?? '-',
                ]
            ];
        })->toArray();
    }

    private function getRekapKehadiranData($schoolId)
    {
        $kehadiran = KehadiranGtk::whereHas('gtk', function($q) use ($schoolId) {
            $q->where('sekolah_id', $schoolId);
        })->get();

        return $kehadiran->map(function ($k) {
            return [
                'label' => $k->gtk->nama ?? 'Tidak tersedia',
                'details' => [
                    'Hari Kerja' => $k->hari_kerja ?? 0,
                    'Sakit' => $k->sakit ?? 0,
                    'Izin' => $k->izin ?? 0,
                    'Alpa' => $k->alfa ?? 0,
                ]
            ];
        })->toArray();
    }

    private function getKelulusanData($schoolId)
    {
        $kelulusans = Kelulusan::where('sekolah_id', $schoolId)->get();

        return $kelulusans->map(function ($k) {
            return [
                'label' => "Periode {$k->tahun}",
                'details' => [
                    'Tahun Lulus' => $k->tahun,
                    'Peserta Ujian' => $k->jumlah_peserta_ujian ?? 0,
                    'Lulus' => $k->jumlah_lulus ?? 0,
                    'Persentase Kelulusan' => ($k->persentase_kelulusan ?? 0) . '%',
                    'Lanjut PT' => $k->jumlah_lanjut_pt ?? 0,
                ]
            ];
        })->toArray();
    }

    public function setPreviewType($type)
    {
        $this->previewType = $type;
    }

    public function closePreview()
    {
        $this->previewType = null;
    }
}
