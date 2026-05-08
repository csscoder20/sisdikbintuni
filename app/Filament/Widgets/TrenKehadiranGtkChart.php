<?php

namespace App\Filament\Widgets;

use App\Models\KehadiranGtk;
use App\Models\Laporan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TrenKehadiranGtkChart extends ChartWidget
{
    protected ?string $heading = 'Tren Rata-rata Kehadiran GTK Tingkat Kabupaten';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = (int) $date->format('m');
            $year = (int) $date->format('Y');

            $laporanIds = Laporan::where('bulan', $month)->where('tahun', $year)->pluck('id');
            
            if ($laporanIds->isEmpty()) {
                $avg = 0;
            } else {
                $totalHadir = KehadiranGtk::whereIn('laporan_id', $laporanIds)->sum('hadir');
                $totalHariKerja = KehadiranGtk::whereIn('laporan_id', $laporanIds)->sum('hari_kerja');
                
                $avg = $totalHariKerja > 0 ? ($totalHadir / $totalHariKerja) * 100 : 0;
            }

            $labels[] = $date->translatedFormat('M Y');
            $data[] = round($avg, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Persentase Kehadiran (%)',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}