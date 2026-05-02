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
use Filament\Facades\Filament;

class OperatorDashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dasbor';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.laporan-bulanan';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
    }

    public array $checklist = [
        'identitas_sekolah' => 'A. IDENTITAS SEKOLAH',
        'kondisi_sarpras' => 'B. KEADAAN GEDUNG SEKOLAH DAN RUMAH GURU',
        'kondisi_siswa' => 'C. KEADAAN SISWA',
        'kondisi_gtk' => 'D. KEADAAN GURU & TENAGA KEPENDIDIKAN',
        'nominatif_gtk' => 'E. NOMINATIF GURU & TENAGA KEPENDIDIKAN',
        'riwayat_pendidikan_gtk' => 'F. RIWAYAT PENDIDIKAN GTK',
        'nominatif_siswa' => 'G. NOMINATIF SISWA',
        'rekening_npwp_gtk' => 'H. REKENING & NPWP GTK',
        'sebaran_jam' => 'I. SEBARAN JAM MENGAJAR',
        'rekap_kehadiran' => 'J. ABSENSI (REKAP KEHADIRAN)',
        'kelulusan' => 'K. DATA KELULUSAN',
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
    public array $chartData = [];

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

        $this->chartData = $this->getChartData($schoolId);

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
        }
    }

    public function getChartData($schoolId)
    {
        if (!$schoolId) return [];

        $siswa = Siswa::where('sekolah_id', $schoolId)->get();
        $gtk = Gtk::where('sekolah_id', $schoolId)->get();

        return [
            'Keadaan Siswa' => [
                'rombel' => Siswa::where('siswa.sekolah_id', $schoolId)
                    ->join('siswa_rombel', 'siswa.id', '=', 'siswa_rombel.siswa_id')
                    ->join('rombel', 'siswa_rombel.rombel_id', '=', 'rombel.id')
                    ->select('rombel.nama', \DB::raw('count(*) as total'))
                    ->groupBy('rombel.nama')
                    ->pluck('total', 'nama')->toArray(),
                'umur' => $siswa->map(fn($s) => $s->tanggal_lahir ? \Carbon\Carbon::parse($s->tanggal_lahir)->age : null)
                    ->filter()->countBy()->sortKeys()->toArray(),
                'agama' => $siswa->countBy('agama')->toArray(),
                'daerah' => $siswa->countBy('daerah_asal')->toArray(), // Assuming daerah refers to daerah_asal
                'disabilitas' => $siswa->countBy('disabilitas')->toArray(),
                'beasiswa' => $siswa->countBy('beasiswa')->toArray(),
            ],
            'Keadaan GTK' => [
                'umur_pie' => $gtk->map(function($g) {
                        if (!$g->tanggal_lahir) return 'Tidak diketahui';
                        $age = \Carbon\Carbon::parse($g->tanggal_lahir)->age;
                        if ($age < 30) return '< 30';
                        if ($age < 40) return '30-39';
                        if ($age < 50) return '40-49';
                        if ($age < 60) return '50-59';
                        return '60+';
                    })->countBy()->toArray(),
                'daerah' => $gtk->countBy('daerah_asal')->toArray(),
                'status' => $gtk->countBy('status_kepegawaian')->toArray(),
                'umur_bar' => $gtk->map(fn($g) => $g->tanggal_lahir ? \Carbon\Carbon::parse($g->tanggal_lahir)->age : null)
                    ->filter()->countBy()->sortKeys()->toArray(),
                'pendidikan' => $gtk->countBy('pendidikan_terakhir')->toArray(),
            ],
            'B. KEADAAN GEDUNG SEKOLAH DAN RUMAH GURU' => [
                'kondisi' => [
                    'Baik' => (int) LaporanGedung::whereHas('laporan', fn($q) => $q->where('sekolah_id', $schoolId))->sum('jumlah_baik'),
                    'Rusak' => (int) LaporanGedung::whereHas('laporan', fn($q) => $q->where('sekolah_id', $schoolId))->sum('jumlah_rusak'),
                ]
            ],
            'Kehadiran GTK' => [
                'rekap' => [
                    'Sakit' => (int) KehadiranGtk::whereHas('gtk', fn($q) => $q->where('sekolah_id', $schoolId))->sum('sakit'),
                    'Izin' => (int) KehadiranGtk::whereHas('gtk', fn($q) => $q->where('sekolah_id', $schoolId))->sum('izin'),
                    'Alpa' => (int) KehadiranGtk::whereHas('gtk', fn($q) => $q->where('sekolah_id', $schoolId))->sum('alfa'),
                ]
            ],
            'Kelulusan' => [
                'tren' => Kelulusan::where('sekolah_id', $schoolId)
                    ->orderBy('tahun', 'desc')
                    ->take(5)
                    ->get()
                    ->reverse()
                    ->pluck('persentase_kelulusan', 'tahun')->toArray()
            ]
        ];
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
            return [
                'label' => $gtk->nama ?? 'Tidak tersedia',
                'details' => [
                    'Gelar' => ($p->gelar_depan ? $p->gelar_depan . ' ' : '') . ($p->gelar_belakang ? ', ' . $p->gelar_belakang : '-'),
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
        $gtkList = Gtk::with('keuangan')->where('sekolah_id', $schoolId)->orderBy('nama', 'asc')->get();

        return $gtkList->map(function ($gtk) {
            $k = $gtk->keuangan;
            return [
                'label' => $gtk->nama ?? 'Tidak tersedia',
                'details' => [
                    'NIP' => $gtk->nip ?? '-',
                    'Nama Bank' => $k->nama_bank ?? '-',
                    'Nomor Rekening' => $k->nomor_rekening ?? '-',
                    'NPWP' => $k->npwp ?? '-',
                ]
            ];
        })->toArray();
    }

    private function getRekapSiswaData($schoolId)
    {
        // Combined rekap data for Halaman 2
        return [
            'type' => 'rekap_summary',
            'sections' => [
                'Siswa per Rombel' => $this->getChartData($schoolId)['Keadaan Siswa']['rombel'],
                'Siswa per Agama' => $this->getChartData($schoolId)['Keadaan Siswa']['agama'],
                'Siswa per Daerah' => $this->getChartData($schoolId)['Keadaan Siswa']['daerah'],
            ]
        ];
    }

    private function getRekapGtkData($schoolId)
    {
        // Combined rekap data for Halaman 2
        return [
            'type' => 'rekap_summary',
            'sections' => [
                'GTK per Status' => $this->getChartData($schoolId)['Keadaan GTK']['status'],
                'GTK per Pendidikan' => $this->getChartData($schoolId)['Keadaan GTK']['pendidikan'],
                'GTK per Daerah' => $this->getChartData($schoolId)['Keadaan GTK']['daerah'],
            ]
        ];
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
