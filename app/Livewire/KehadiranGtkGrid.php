<?php

namespace App\Livewire;

use App\Models\Gtk;
use App\Models\GtkKehadiran;
use App\Models\KehadiranGtk;
use App\Support\ValidationPeriod;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Filament\Notifications\Notification;

class KehadiranGtkGrid extends Component
{
    use WithPagination;

    public $bulan;
    public $tahun;
    public $days = [];
    public $attendanceData = []; // [gtk_id][day] => value (H, I, S, A)
    public $perPage = 10;

    public function isValidationPeriodLocked(): bool
    {
        return ValidationPeriod::isLockedForOperatorPanel();
    }

    protected function notifyValidationPeriodLocked(): void
    {
        Notification::make()
            ->title('Data tidak dapat disimpan.')
            ->body('Periode validasi sedang ditutup oleh Admin Dinas.')
            ->danger()
            ->send();
    }

    public function mount()
    {
        $this->bulan = (int) date('m');
        $this->tahun = (int) date('Y');
        $this->loadDays();
    }

    public function updatedBulan()
    {
        $this->resetPage();
        $this->loadDays();
    }

    public function updatedTahun()
    {
        $this->resetPage();
        $this->loadDays();
    }

    public function loadDays()
    {
        $date = Carbon::create($this->tahun, $this->bulan, 1);
        $daysInMonth = $date->daysInMonth;

        $this->days = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $currentDate = Carbon::create($this->tahun, $this->bulan, $i);
            $this->days[] = [
                'day' => $i,
                'is_sunday' => $currentDate->isSunday(),
            ];
        }
    }

    protected function getSchoolId(): ?int
    {
        return filament()->getTenant()?->id ?? session('dinas_selected_sekolah_id');
    }

    public function formatGtkName(Gtk $gtk): string
    {
        $pendidikan = $gtk->pendidikan->first();
        $nama = trim((string) $gtk->nama);
        $gelarDepan = trim((string) ($pendidikan?->gelar_depan ?? ''));
        $gelarBelakang = trim((string) ($pendidikan?->gelar_belakang ?? ''));

        return trim(($gelarDepan ? $gelarDepan . ' ' : '') . $nama . ($gelarBelakang ? ', ' . $gelarBelakang : ''));
    }

    protected function loadAttendance($gtks)
    {
        $records = GtkKehadiran::whereYear('tgl_presensi', $this->tahun)
            ->whereMonth('tgl_presensi', $this->bulan)
            ->whereIn('gtk_id', $gtks->pluck('id'))
            ->get();

        $this->attendanceData = [];
        foreach ($gtks as $gtk) {
            $gtkRecords = $records->where('gtk_id', $gtk->id);

            for ($i = 1; $i <= 31; $i++) {
                $record = $gtkRecords->filter(function ($r) use ($i) {
                    return $r->tgl_presensi->day === $i;
                })->first();

                $this->attendanceData[$gtk->id][$i] = $record ? $record->presensi : '';
            }
        }
    }



    public function toggleHoliday($day)
    {
        if ($this->isValidationPeriodLocked()) {
            $this->notifyValidationPeriodLocked();
            return;
        }

        // Reuse existing setHoliday logic (which was removed). Implement toggle behavior here.
        $date = Carbon::create($this->tahun, $this->bulan, $day);
        if ($date->isSunday()) return;

        $dateStr = $date->format('Y-m-d');
        $laporanId = $this->getActiveLaporanId();
        $gtks = Gtk::where('sekolah_id', $this->getSchoolId())->get();

        // Determine if any record already set as Libur for this day
        $isCurrentlyHoliday = GtkKehadiran::whereIn('gtk_id', $gtks->pluck('id'))
            ->where('tgl_presensi', $dateStr)
            ->where('presensi', 'L')
            ->exists();

        $newValue = $isCurrentlyHoliday ? null : 'L';

        foreach ($gtks as $gtk) {
            if ($newValue === null) {
                GtkKehadiran::where('gtk_id', $gtk->id)
                    ->where('tgl_presensi', $dateStr)
                    ->where('presensi', 'L')
                    ->delete();
            } else {
                GtkKehadiran::updateOrCreate(
                    ['gtk_id' => $gtk->id, 'tgl_presensi' => $dateStr],
                    ['presensi' => 'L', 'laporan_id' => $laporanId]
                );
            }
            $this->syncMonthlySummary($gtk->id);
            $this->attendanceData[$gtk->id][$day] = $newValue;
        }

        Notification::make()
            ->title($newValue === 'L' ? "Tanggal {$day} ditandai sebagai Libur" : "Libur pada tanggal {$day} dihapus")
            ->success()
            ->send();
    }

    public function markAllPresent($day)
    {
        if ($this->isValidationPeriodLocked()) {
            $this->notifyValidationPeriodLocked();
            return;
        }

        $date = Carbon::create($this->tahun, $this->bulan, $day);
        if ($date->isSunday()) return;

        $dateStr = $date->format('Y-m-d');
        $laporanId = $this->getActiveLaporanId();
        $gtks = Gtk::where('sekolah_id', $this->getSchoolId())->get();

        foreach ($gtks as $gtk) {
            GtkKehadiran::updateOrCreate(
                ['gtk_id' => $gtk->id, 'tgl_presensi' => $dateStr],
                ['presensi' => 'H', 'laporan_id' => $laporanId]
            );
            $this->syncMonthlySummary($gtk->id);
            $this->attendanceData[$gtk->id][$day] = 'H';
        }

        Notification::make()
            ->title("Semua GTK ditandai Hadir pada tanggal {$day}")
            ->success()
            ->send();
    }

    public function updateAttendance($gtkId, $day, $value)
    {
        if ($this->isValidationPeriodLocked()) {
            $this->notifyValidationPeriodLocked();
            return;
        }

        $value = strtoupper(trim($value));
        if (!in_array($value, ['H', 'I', 'S', 'A', 'L'])) {
            $value = null;
        }

        $date = Carbon::create($this->tahun, $this->bulan, $day)->format('Y-m-d');

        if ($value === null) {
            GtkKehadiran::where('gtk_id', $gtkId)
                ->where('tgl_presensi', $date)
                ->delete();
        } else {
            GtkKehadiran::updateOrCreate(
                [
                    'gtk_id' => $gtkId,
                    'tgl_presensi' => $date,
                ],
                [
                    'presensi' => $value,
                    'laporan_id' => $this->getActiveLaporanId(),
                ]
            );
        }

        // Sync with monthly summary table (KehadiranGtk)
        $this->syncMonthlySummary($gtkId);

        $this->attendanceData[$gtkId][$day] = $value;
    }
    protected function getActiveLaporanId(): ?int
    {
        $sekolahId = $this->getSchoolId();
        if (! $sekolahId) {
            return null;
        }

        $laporan = \App\Models\Laporan::firstOrCreate(
            [
                'sekolah_id' => $sekolahId,
                'bulan'      => $this->bulan,
                'tahun'      => $this->tahun,
            ]
        );

        return $laporan->id;
    }

    protected function syncMonthlySummary($gtkId)
    {
        $records = GtkKehadiran::whereYear('tgl_presensi', $this->tahun)
            ->whereMonth('tgl_presensi', $this->bulan)
            ->where('gtk_id', $gtkId)
            ->get();

        $hadir = $records->where('presensi', 'H')->count();
        $izin = $records->where('presensi', 'I')->count();
        $sakit = $records->where('presensi', 'S')->count();
        $alfa = $records->where('presensi', 'A')->count();
        $libur = $records->where('presensi', 'L')->count();
        $hariKerja = $hadir; // Per user request: only H counts as Hari Kerja

        KehadiranGtk::updateOrCreate(
            [
                'gtk_id' => $gtkId,
                'bulan' => $this->bulan,
                'tahun' => $this->tahun,
            ],
            [
                'laporan_id' => $this->getActiveLaporanId(),
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alfa' => $alfa,
                'hari_kerja' => $hariKerja,
            ]
        );
    }

    public function render()
    {
        $query = Gtk::where('sekolah_id', $this->getSchoolId())
            ->with('pendidikan')
            ->orderBy('id');

        $gtks = ($this->perPage === 'all')
            ? $query->paginate($query->count())
            : $query->paginate((int) $this->perPage);

        $this->loadAttendance($gtks->getCollection());

        return view('livewire.kehadiran-gtk-grid', [
            'gtks' => $gtks,
            'locked' => $this->isValidationPeriodLocked(),
        ]);
    }
}
