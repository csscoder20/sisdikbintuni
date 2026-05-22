<?php

namespace App\Services;

use App\Models\Gtk;
use App\Models\Mengajar;
use App\Models\KehadiranGtk;
use Illuminate\Support\Collection;

/**
 * ReportService
 *
 * Provides helper methods to generate summaries for teaching hours (jam mengajar)
 * and attendance (kehadiran) for GTK (guru, tenaga kependidikan, etc.).
 *
 * The methods rely on existing relations defined in the models:
 *   - Gtk hasMany Mengajar (teaching assignments)
 *   - Gtk hasMany KehadiranGtk (attendance records per laporan)
 *   - Laporan contains month/year context for kehadiran.
 */
class ReportService
{
    /**
     * Get total teaching hours per GTK for a given semester.
     *
     * @param  int|null  $semester  1 for ganjil, 2 for genap, null for all
     * @param  int|null  $year      Academic year (e.g., 2022). Null for all years.
     * @return Collection  Collection of ['gtk_id' => int, 'nama' => string, 'total_jam' => int]
     */
    public function getTeachingHours(?int $semester = null, ?int $year = null): Collection
    {
        $query = Mengajar::query();
        if ($semester !== null) {
            $semesterLabel = $semester === 1 ? 'ganjil' : 'genap';
            $query->where('semester', $semesterLabel);
        }
        if ($year !== null) {
            // tahun_ajaran stored as "2022/2023"
            $query->where('tahun_ajaran', 'like', "$year/%");
        }
        return $query->with('gtk:id,nama')
            ->get()
            ->groupBy('gtk_id')
            ->map(function ($group) {
                $total = $group->sum('jumlah_jam');
                $gtk = $group->first()->gtk;
                return [
                    'gtk_id' => $gtk->id,
                    'nama' => $gtk->nama,
                    'total_jam' => $total,
                ];
            })
            ->values();
    }

    /**
     * Get attendance summary for a GTK for a specific month/year.
     *
     * @param  int  $gtkId
     * @param  int  $month  1-12
     * @param  int  $year
     * @return array  ['hadir'=>int,'sakit'=>int,'izin'=>int,'alfa'=>int,'hari_kerja'=>int]
     */
    public function getAttendance(int $gtkId, int $month, int $year): array
    {
        $record = KehadiranGtk::where('gtk_id', $gtkId)
            ->where('bulan', $month)
            ->where('tahun', $year)
            ->first();

        if (!$record) {
            return [
                'hadir' => 0,
                'sakit' => 0,
                'izin' => 0,
                'alfa' => 0,
                'hari_kerja' => 0,
            ];
        }

        return [
            'hadir' => $record->hadir ?? 0,
            'sakit' => $record->sakit ?? 0,
            'izin' => $record->izin ?? 0,
            'alfa' => $record->alfa ?? 0,
            'hari_kerja' => $record->hari_kerja ?? 0,
        ];
    }

    /**
     * Get attendance summary for all GTK for a given month/year.
     *
     * @param  int  $month
     * @param  int  $year
     * @return Collection  Collection of ['gtk_id'=>int,'nama'=>string,'hadir'=>int,...]
     */
    public function getAllAttendance(int $month, int $year): Collection
    {
        $records = KehadiranGtk::where('bulan', $month)
            ->where('tahun', $year)
            ->with('gtk:id,nama')
            ->get();

        return $records->map(function ($rec) {
            return [
                'gtk_id' => $rec->gtk_id,
                'nama' => $rec->gtk->nama ?? 'unknown',
                'hadir' => $rec->hadir ?? 0,
                'sakit' => $rec->sakit ?? 0,
                'izin' => $rec->izin ?? 0,
                'alfa' => $rec->alfa ?? 0,
                'hari_kerja' => $rec->hari_kerja ?? 0,
            ];
        });
    }
}
