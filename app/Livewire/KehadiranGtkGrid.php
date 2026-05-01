<?php

namespace App\Livewire;

use App\Models\Gtk;
use App\Models\GtkKehadiran;
use App\Models\KehadiranGtk;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;

class KehadiranGtkGrid extends Component
{
    use WithPagination;

    public $bulan;
    public $tahun;
    public $days = [];
    public $attendanceData = []; // [gtk_id][day] => value (H, I, S, A)
    public $perPage = 10;

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
                $record = $gtkRecords->filter(function($r) use ($i) {
                    return $r->tgl_presensi->day === $i;
                })->first();
                
                $this->attendanceData[$gtk->id][$i] = $record ? $record->presensi : '';
            }
        }
    }

    public function updateAttendance($gtkId, $day, $value)
    {
        $value = strtoupper(trim($value));
        if (!in_array($value, ['H', 'I', 'S', 'A'])) {
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

    protected function getActiveLaporanId()
    {
        $laporan = \App\Models\Laporan::where('sekolah_id', filament()->getTenant()?->id)
            ->where('bulan', $this->bulan)
            ->where('tahun', $this->tahun)
            ->first();
            
        return $laporan ? $laporan->id : null;
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
        $hariKerja = $hadir + $izin + $sakit + $alfa;

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
        $gtks = Gtk::where('sekolah_id', filament()->getTenant()?->id)
            ->orderBy('id')
            ->paginate($this->perPage);
        
        $this->loadAttendance($gtks->getCollection());

        return view('livewire.kehadiran-gtk-grid', [
            'gtks' => $gtks,
        ]);
    }
}
