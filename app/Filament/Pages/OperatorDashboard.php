<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Panel;
use Illuminate\Contracts\Support\Htmlable;
use App\Models\LaporanGedung;
use App\Models\Gtk;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\Sekolah;
use Filament\Facades\Filament;

class OperatorDashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.laporan-bulanan';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
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
            auth()->user()->sekolah?->nama != null;

        $this->checklistStatus['nominatif_gtk'] =
            Gtk::where('sekolah_id', $schoolId)->count() > 0;

        $this->checklistStatus['nominatif_siswa'] =
            Siswa::where('sekolah_id', $schoolId)->count() > 0;

        $this->checklistStatus['kondisi_sarpras'] =
            LaporanGedung::whereHas('laporan', function ($q) use ($schoolId) {
                $q->where('sekolah_id', $schoolId);
            })->count() > 0;

        $this->checklistStatus['kondisi_gtk'] =
            $this->checklistStatus['nominatif_gtk'];

        $this->checklistStatus['kondisi_siswa'] =
            $this->checklistStatus['nominatif_siswa'];

        $this->checklistStatus['sebaran_jam'] =
            $this->checklistStatus['nominatif_gtk'];

        $this->checklistStatus['rekap_kehadiran'] =
            $this->checklistStatus['nominatif_gtk'];

        $this->checklistStatus['kelulusan'] =
            $this->checklistStatus['nominatif_siswa'];
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
        $gtkList = Gtk::where('sekolah_id', $schoolId)->limit(100)->get();

        return $gtkList->map(function ($gtk) {
            return [
                'label' => $gtk->nama ?? 'N/A',
                'details' => [
                    'NIP' => $gtk->nip ?? '-',
                    'NIK' => $gtk->nik ?? '-',
                    'Tempat/Tgl Lahir' => ($gtk->tempat_lahir ?? '-') . ' / ' . ($gtk->tanggal_lahir ? date('d-m-Y', strtotime($gtk->tanggal_lahir)) : '-'),
                    'Jenis Kelamin' => $gtk->jenis_kelamin ?? '-',
                    'Agama' => $gtk->agama ?? '-',
                    'Status' => $gtk->status_gtk ?? '-'
                ]
            ];
        })->toArray();
    }

    private function getNominatifSiswaData($schoolId)
    {
        $siswaList = Siswa::where('sekolah_id', $schoolId)->limit(100)->get();

        return $siswaList->map(function ($siswa) {
            return [
                'label' => $siswa->nama ?? 'N/A',
                'details' => [
                    'NISN' => $siswa->nisn ?? '-',
                    'NIK' => $siswa->nik ?? '-',
                    'Tempat/Tgl Lahir' => ($siswa->tempat_lahir ?? '-') . ' / ' . ($siswa->tanggal_lahir ? date('d-m-Y', strtotime($siswa->tanggal_lahir)) : '-'),
                    'Jenis Kelamin' => $siswa->jenis_kelamin ?? '-',
                    'Agama' => $siswa->agama ?? '-',
                    'Rombel' => $siswa->rombel->pluck('nama')->implode(', ') ?: '-'
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
                'label' => $sarpras->nama_ruang ?? 'N/A',
                'details' => [
                    'Jumlah' => $sarpras->jumlah_total ?? 0,
                    'Kondisi Baik' => $sarpras->jumlah_baik ?? 0,
                    'Rusak' => $sarpras->jumlah_rusak ?? 0,
                    'Kepemilikan' => $sarpras->status_kepemilikan ?? '-',
                ]
            ];
        })->toArray();
    }

    private function getKondisiGtkData($schoolId)
    {
        return $this->getNominatifGtkData($schoolId);
    }

    private function getKondisiSiswaData($schoolId)
    {
        return $this->getNominatifSiswaData($schoolId);
    }

    private function getSebaranJamData($schoolId)
    {
        $gtkList = Gtk::where('sekolah_id', $schoolId)->limit(50)->get();

        return $gtkList->map(function ($gtk) {
            return [
                'label' => $gtk->nama ?? 'N/A',
                'details' => [
                    'Tugas' => 'Mengajar',
                    'Keterangan' => 'Data sebaran jam menyusul'
                ]
            ];
        })->toArray();
    }

    private function getRekapKehadiranData($schoolId)
    {
        return $this->getNominatifGtkData($schoolId);
    }

    private function getKelulusanData($schoolId)
    {
        $rombels = Rombel::where('sekolah_id', $schoolId)->get();

        return $rombels->map(function ($rombel) {
            return [
                'label' => $rombel->nama ?? 'N/A',
                'details' => [
                    'Tahun' => date('Y'),
                    'Tingkat' => $rombel->tingkat ?? '-',
                    'Status' => 'Data kelulusan diproses'
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
