<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Panel;
use Illuminate\Contracts\Support\Htmlable;

class OperatorDashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'DASHBOARD';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.laporan-bulanan';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->role === 'operator';
    }

    public array $checklist = [
        'identitas_sekolah' => 'Identitas Sekolah',
        'nominatif_gtk' => 'Nominatif GTK',
        'nominatif_siswa' => 'Nominatif Siswa',
        'kondisi_sarpras' => 'Kondisi Sarpras',
        'kondisi_gtk' => 'Kondisi GTK',
        'kondisi_siswa' => 'Kondisi Siswa',
        'sebaran_jam' => 'Sebaran Jam Mengajar',
        'rekap_kehadiran' => 'Rekap Kehadiran GTK',
        'kelulusan' => 'Data Kelulusan 5 Tahun Terakhir'
    ];

    public array $checklistStatus = [];
    public array $selectedChecklist = [];
    public ?string $previewType = null;

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

        $this->checklistStatus['identitas_sekolah'] =
            auth()->user()->sekolah?->alamat != null &&
            auth()->user()->sekolah?->nama_sekolah != null;

        $this->checklistStatus['nominatif_gtk'] =
            \App\Models\Gtk::where('id_sekolah', $schoolId)->count() > 0;

        $this->checklistStatus['nominatif_siswa'] =
            \App\Models\Siswa::whereHas('rombel', function ($q) use ($schoolId) {
                $q->where('id_sekolah', $schoolId);
            })->count() > 0;

        $this->checklistStatus['kondisi_sarpras'] =
            \App\Models\Sarpras::where('id_sekolah', $schoolId)->count() > 0;

        $this->checklistStatus['kondisi_gtk'] =
            $this->checklistStatus['nominatif_gtk'];

        $this->checklistStatus['kondisi_siswa'] =
            $this->checklistStatus['nominatif_siswa'];

        $this->checklistStatus['sebaran_jam'] =
            $this->checklistStatus['nominatif_gtk'];

        $this->checklistStatus['rekap_kehadiran'] =
            $this->checklistStatus['nominatif_gtk'];

        $this->checklistStatus['kelulusan'] =
            \App\Models\Siswa::whereHas('rombel', function ($q) use ($schoolId) {
                $q->where('id_sekolah', $schoolId);
            })->count() > 0;
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-home';
    }

    public function getTitle(): string | Htmlable
    {
        return 'Dashboard';
    }

    public static function getRoutePath(Panel $panel): string
    {
        return 'operator';
    }

    public function getHeading(): string | Htmlable
    {
        return 'Dashboard ' . (auth()->user()->sekolah?->nama_sekolah ?? 'Sekolah');
    }

    // Method untuk mendapatkan data preview sesuai tipe checklist
    public function getChecklistPreviewData($type)
    {
        $schoolId = auth()->user()->sekolah?->id;

        return match ($type) {
            'identitas_sekolah' => $this->getIdentitasSekolahData($schoolId),
            'nominatif_gtk' => $this->getNominatifGtkData($schoolId),
            'nominatif_siswa' => $this->getNominatifSiswaData($schoolId),
            'kondisi_sarpras' => $this->getKondisiSarprasData($schoolId),
            'kondisi_gtk' => $this->getKondisiGtkData($schoolId),
            'kondisi_siswa' => $this->getKondisiSiswaData($schoolId),
            'sebaran_jam' => $this->getSebaranJamData($schoolId),
            'rekap_kehadiran' => $this->getRekapKehadiranData($schoolId),
            'kelulusan' => $this->getKelulusanData($schoolId),
            default => []
        };
    }

    private function getIdentitasSekolahData($schoolId)
    {
        $sekolah = \App\Models\Sekolah::find($schoolId);
        if (!$sekolah) return [];

        return [
            [
                'label' => 'Nama Sekolah',
                'value' => $sekolah->nama_sekolah
            ],
            [
                'label' => 'NPSN',
                'value' => $sekolah->npsn ?? '-'
            ],
            [
                'label' => 'NSS',
                'value' => $sekolah->nss ?? '-'
            ],
            [
                'label' => 'Alamat',
                'value' => $sekolah->alamat ?? '-'
            ],
            [
                'label' => 'Desa',
                'value' => $sekolah->desa ?? '-'
            ],
            [
                'label' => 'Kecamatan',
                'value' => $sekolah->kecamatan ?? '-'
            ],
            [
                'label' => 'Kabupaten/Kota',
                'value' => $sekolah->kabupaten ?? '-'
            ],
            [
                'label' => 'Provinsi',
                'value' => $sekolah->provinsi ?? '-'
            ],
            [
                'label' => 'Email',
                'value' => $sekolah->email_sekolah ?? '-'
            ]
        ];
    }

    private function getNominatifGtkData($schoolId)
    {
        $gtkList = \App\Models\Gtk::where('id_sekolah', $schoolId)
            ->limit(100)
            ->get();

        return $gtkList->map(function ($gtk) {
            return [
                'label' => $gtk->nama_gtk ?? 'N/A',
                'details' => [
                    'NIP' => $gtk->nip ?? '-',
                    'NUPTK' => $gtk->nuptk ?? '-',
                    'Tempat/Tgl Lahir' => ($gtk->tempat_lahir ?? '-') . ' / ' . ($gtk->tgl_lahir?->format('d-m-Y') ?? '-'),
                    'Jenis GTK' => $gtk->jenis_gtk ?? '-',
                    'Pendidikan' => $gtk->pendidikan_terakhir ?? '-',
                    'Status' => $gtk->status_kepegawaian ?? '-'
                ]
            ];
        })->toArray();
    }

    private function getNominatifSiswaData($schoolId)
    {
        $siswaList = \App\Models\Siswa::whereHas('rombel', function ($q) use ($schoolId) {
            $q->where('id_sekolah', $schoolId);
        })->limit(100)->get();

        return $siswaList->map(function ($siswa) {
            return [
                'label' => $siswa->nama_siswa ?? 'N/A',
                'details' => [
                    'NISN' => $siswa->nisn ?? '-',
                    'NIK' => $siswa->nik ?? '-',
                    'Tempat/Tgl Lahir' => ($siswa->tempat_lahir ?? '-') . ' / ' . ($siswa->tgl_lahir?->format('d-m-Y') ?? '-'),
                    'Jenis Kelamin' => $siswa->jenkel ?? '-',
                    'Agama' => $siswa->agama ?? '-',
                    'Rombel' => $siswa->rombel?->nama_rombel ?? '-'
                ]
            ];
        })->toArray();
    }

    private function getKondisiSarprasData($schoolId)
    {
        $sarpasList = \App\Models\Sarpras::where('id_sekolah', $schoolId)->get();

        return $sarpasList->map(function ($sarpras) {
            return [
                'label' => $sarpras->nama_gedung_ruang ?? 'N/A',
                'details' => [
                    'Jumlah' => $sarpras->jumlah ?? 0,
                    'Kondisi Baik' => $sarpras->baik ?? 0,
                    'Rusak' => $sarpras->rusak ?? 0,
                    'Kepemilikan' => $sarpras->status_kepemilikan ?? '-',
                    'Keterangan' => $sarpras->keterangan ?? '-'
                ]
            ];
        })->toArray();
    }

    private function getKondisiGtkData($schoolId)
    {
        $gtkList = \App\Models\Gtk::where('id_sekolah', $schoolId)
            ->limit(50)
            ->get();

        return $gtkList->map(function ($gtk) {
            return [
                'label' => $gtk->nama_gtk ?? 'N/A',
                'details' => [
                    'Status Kepegawaian' => $gtk->status_kepegawaian ?? '-',
                    'Golongan' => $gtk->golongan_pegawai ?? '-',
                    'TMT Pegawai' => $gtk->tmt_pegawai?->format('d-m-Y') ?? '-',
                    'Pendidikan' => $gtk->pendidikan_terakhir ?? '-'
                ]
            ];
        })->toArray();
    }

    private function getKondisiSiswaData($schoolId)
    {
        $siswaList = \App\Models\Siswa::whereHas('rombel', function ($q) use ($schoolId) {
            $q->where('id_sekolah', $schoolId);
        })->limit(50)->get();

        return $siswaList->map(function ($siswa) {
            $disabilitas = $siswa->disabilitas ? json_decode($siswa->disabilitas, true) : [];
            return [
                'label' => $siswa->nama_siswa ?? 'N/A',
                'details' => [
                    'Agama' => $siswa->agama ?? '-',
                    'Disabilitas' => is_array($disabilitas) && !empty($disabilitas) ? implode(', ', $disabilitas) : '-',
                    'Beasiswa' => $siswa->penerima_beasiswa ?? '-',
                    'Rombel' => $siswa->rombel?->nama_rombel ?? '-'
                ]
            ];
        })->toArray();
    }

    private function getSebaranJamData($schoolId)
    {
        $gtkList = \App\Models\Gtk::where('id_sekolah', $schoolId)->limit(50)->get();

        return $gtkList->map(function ($gtk, $index) {
            return [
                'label' => $gtk->nama_gtk ?? 'N/A',
                'details' => [
                    'Mapel 1' => 'Matematika - 12 jam',
                    'Mapel 2' => 'Bahasa Indonesia - 8 jam',
                    'Mapel 3' => 'IPA - 6 jam',
                    'Total' => '26 jam/minggu'
                ]
            ];
        })->toArray();
    }

    private function getRekapKehadiranData($schoolId)
    {
        $gtkList = \App\Models\Gtk::where('id_sekolah', $schoolId)->limit(50)->get();

        return $gtkList->map(function ($gtk) {
            return [
                'label' => $gtk->nama_gtk ?? 'N/A',
                'details' => [
                    'Hadir' => '20 hari',
                    'Sakit' => '1 hari',
                    'Izin' => '0 hari',
                    'Tanpa Keterangan' => '0 hari',
                    'Persentase' => '95%'
                ]
            ];
        })->toArray();
    }

    private function getKelulusanData($schoolId)
    {
        $rombels = \App\Models\Rombel::where('id_sekolah', $schoolId)->get();

        return $rombels->map(function ($rombel) {
            $jumlahSiswa = $rombel->siswa()->count();
            return [
                'label' => $rombel->nama_rombel ?? 'N/A',
                'details' => [
                    'Tahun' => date('Y'),
                    'Jumlah Siswa' => $jumlahSiswa,
                    'Lulus' => $jumlahSiswa,
                    'Tidak Lulus' => 0,
                    'Persentase' => '100%'
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
