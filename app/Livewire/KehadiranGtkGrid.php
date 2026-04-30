<?php

namespace App\Livewire;

use App\Models\Gtk;
use App\Models\KehadiranGtk;
use Livewire\Component;
use Illuminate\Support\Carbon;

class KehadiranGtkGrid extends Component
{
    public $bulan;
    public $tahun;
    public $days = [];
    public $attendanceData = []; // [gtk_id][day] => value

    public function mount()
    {
        $this->bulan = (int) date('m');
        $this->tahun = (int) date('Y');
        $this->loadDays();
        $this->loadAttendance();
    }

    public function updatedBulan()
    {
        $this->loadDays();
        $this->loadAttendance();
    }

    public function updatedTahun()
    {
        $this->loadDays();
        $this->loadAttendance();
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

    public function loadAttendance()
    {
        $gtks = Gtk::where('sekolah_id', filament()->getTenant()?->id)->get();
        $records = KehadiranGtk::where('bulan', $this->bulan)
            ->where('tahun', $this->tahun)
            ->whereIn('gtk_id', $gtks->pluck('id'))
            ->get();

        $this->attendanceData = [];
        foreach ($gtks as $gtk) {
            $record = $records->where('gtk_id', $gtk->id)->first();
            $data = $record ? ($record->data_harian ?? []) : [];
            
            for ($i = 1; $i <= 31; $i++) {
                $this->attendanceData[$gtk->id][$i] = $data[$i] ?? '';
            }
        }
    }

    public function updateAttendance($gtkId, $day, $value)
    {
        // Sanitize value (0 or 1)
        $value = ($value === '1' || $value === 1) ? 1 : (($value === '0' || $value === 0) ? 0 : null);
        
        $record = KehadiranGtk::updateOrCreate(
            [
                'gtk_id' => $gtkId,
                'bulan' => $this->bulan,
                'tahun' => $this->tahun,
            ],
            [
                'laporan_id' => null, // Placeholder or link to active report if needed
            ]
        );

        $data = $record->data_harian ?? [];
        if ($value === null) {
            unset($data[$day]);
        } else {
            $data[$day] = $value;
        }

        // Calculate totals
        $hadir = collect($data)->filter(fn($v) => $v === 1)->count();
        $tidakHadir = collect($data)->filter(fn($v) => $v === 0)->count();
        
        $record->data_harian = $data;
        $record->hadir = $hadir;
        // Optionally update alfa for the remainder? 
        // But let's just keep it simple as requested.
        $record->save();

        $this->attendanceData[$gtkId][$day] = $value;
    }

    public function render()
    {
        return view('livewire.kehadiran-gtk-grid', [
            'gtks' => Gtk::where('sekolah_id', filament()->getTenant()?->id)->orderBy('nama')->get(),
        ]);
    }
}
