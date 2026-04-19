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
        'siswa_rombel' => 'Siswa per Rombel',
        'siswa_umur' => 'Siswa per Umur',
        'siswa_agama' => 'Siswa per Agama',
        'siswa_daerah' => 'Siswa per Daerah Asal',
        'siswa_disabilitas' => 'Data Disabilitas Siswa',
        'siswa_beasiswa' => 'Data Beasiswa Siswa',
        'gtk_agama' => 'GTK per Agama',
        'gtk_daerah' => 'GTK per Daerah Asal',
        'gtk_status' => 'GTK per Status',
        'gtk_umur' => 'GTK per Umur',
        'gtk_pendidikan' => 'GTK per Pendidikan Terakhir',
        'kondisi_sarpras' => 'Kondisi Gedung/Ruang',
        'sebaran_jam' => 'Sebaran Jam Mengajar',
        'rekap_kehadiran' => 'Rekap Kehadiran GTK',
        'kelulusan' => 'Data Kelulusan 5 Tahun Terakhir',
        'identitas_sekolah' => 'Identitas Sekolah'
    ];

    public array $groups = [
        'Keadaan Siswa' => ['siswa_rombel', 'siswa_umur', 'siswa_agama', 'siswa_daerah', 'siswa_disabilitas', 'siswa_beasiswa'],
        'Keadaan GTK' => ['gtk_agama', 'gtk_daerah', 'gtk_status', 'gtk_umur', 'gtk_pendidikan'],
        'Kondisi Gedung/Ruang' => ['kondisi_sarpras'],
        'Sebaran Jam Mengajar' => ['sebaran_jam'],
        'Kehadiran GTK' => ['rekap_kehadiran'],
        'Kelulusan' => ['kelulusan'],
        'Identitas Sekolah' => ['identitas_sekolah'],
    ];

    public array $checklistStatus = [];
    public array $selectedChecklist = [];
    public ?string $previewType = null;
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

        $this->chartData = $this->getChartData($schoolId);
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
            'Kondisi Gedung/Ruang' => [
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

        // Group granular checklist items to their respective preview data fetchers
        if (str_starts_with($type, 'siswa_')) {
            return $this->getKondisiSiswaData($schoolId, $type);
        }

        if (str_starts_with($type, 'gtk_')) {
            return $this->getKondisiGtkData($schoolId, $type);
        }

        return match ($type) {
            'identitas_sekolah' => $this->getIdentitasSekolahData($schoolId),
            'kondisi_sarpras' => $this->getKondisiSarprasData($schoolId),
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
            ['label' => 'NPSN', 'value' => $sekolah->npsn ?? '-'],
            ['label' => 'Alamat', 'value' => $sekolah->alamat ?? '-'],
            ['label' => 'Desa', 'value' => $sekolah->desa ?? '-'],
            ['label' => 'Kecamatan', 'value' => $sekolah->kecamatan ?? '-'],
            ['label' => 'Kabupaten', 'value' => $sekolah->kabupaten ?? '-'],
            ['label' => 'Provinsi', 'value' => $sekolah->provinsi ?? '-'],
            ['label' => 'Email', 'value' => $sekolah->email ?? '-']
        ];
    }

    private function getNominatifGtkData($schoolId)
    {
        $gtkList = Gtk::where('sekolah_id', $schoolId)->get();

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
                    'Jenis Kelamin'           => $gtk->jenis_kelamin ?? '-',
                    'Agama'                   => $gtk->agama ?? '-',
                    'Alamat'                  => $gtk->alamat ?? '-',
                    'Desa'                    => $gtk->desa ?? '-',
                    'Kecamatan'               => $gtk->kecamatan ?? '-',
                    'Kabupaten'               => $gtk->kabupaten ?? '-',
                    'Pendidikan Terakhir'     => $gtk->pendidikan_terakhir ?? '-',
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

    private function getNominatifSiswaData($schoolId)
    {
        $siswaList = Siswa::where('sekolah_id', $schoolId)->get();

        return $siswaList->map(function ($siswa) {
            return [
                'label' => $siswa->nama ?? 'Tidak tersedia',
                'details' => [
                    'NISN'          => $siswa->nisn ?? '-',
                    'NIK'           => $siswa->nik ?? '-',
                    'Tempat Lahir'  => $siswa->tempat_lahir ?? '-',
                    'Tanggal Lahir' => $siswa->tanggal_lahir ? date('d-m-Y', strtotime($siswa->tanggal_lahir)) : '-',
                    'Jenis Kelamin' => $siswa->jenis_kelamin ?? '-',
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
        $sarpasList = LaporanGedung::whereHas('laporan', function($q) use ($schoolId) {
            $q->where('sekolah_id', $schoolId);
        })->get();

        return $sarpasList->map(function ($sarpras) {
            return [
                'label' => $sarpras->nama_ruang ?? 'Tidak tersedia',
                'details' => [
                    'Jumlah' => $sarpras->jumlah_total ?? 0,
                    'Kondisi Baik' => $sarpras->jumlah_baik ?? 0,
                    'Rusak' => $sarpras->jumlah_rusak ?? 0,
                    'Kepemilikan' => $sarpras->status_kepemilikan ?? '-',
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
