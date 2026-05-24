<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;

class DinasGtkStatusChart extends ChartWidget
{
    protected ?string $heading = 'Grafik GTK Berdasarkan Status Kepegawaian di Setiap Sekolah';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 2;
    protected ?string $maxHeight = '600px';

    protected function getData(): array
    {
        $data = DB::table('sekolah')
            ->leftJoin('gtk', function ($join) {
                $join->on('sekolah.id', '=', 'gtk.sekolah_id')
                    ->whereNull('gtk.deleted_at');
            })
            ->select(
                'sekolah.nama as sekolah_nama',
                DB::raw("SUM(CASE WHEN gtk.status_kepegawaian = 'PNS' THEN 1 ELSE 0 END) as pns"),
                DB::raw("SUM(CASE WHEN gtk.status_kepegawaian = 'CPNS' THEN 1 ELSE 0 END) as cpns"),
                DB::raw("SUM(CASE WHEN gtk.status_kepegawaian = 'PPPK' THEN 1 ELSE 0 END) as pppk"),
                DB::raw("SUM(CASE WHEN gtk.status_kepegawaian = 'GTY/PTY' THEN 1 ELSE 0 END) as gty_pty"),
                DB::raw("SUM(CASE WHEN gtk.status_kepegawaian = 'Kontrak' THEN 1 ELSE 0 END) as kontrak"),
                DB::raw("SUM(CASE WHEN gtk.status_kepegawaian = 'Honorer Sekolah' THEN 1 ELSE 0 END) as honorer")
            )
            ->whereIn('sekolah.jenjang', ['SMA', 'SMK', 'sma', 'smk'])
            ->whereNull('sekolah.deleted_at')
            ->groupBy('sekolah.id', 'sekolah.nama')
            ->orderBy('sekolah.nama')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'PNS',
                    'data' => $data->pluck('pns')->toArray(),
                    'backgroundColor' => '#3B82F6',
                ],
                [
                    'label' => 'CPNS',
                    'data' => $data->pluck('cpns')->toArray(),
                    'backgroundColor' => '#10B981',
                ],
                [
                    'label' => 'PPPK',
                    'data' => $data->pluck('pppk')->toArray(),
                    'backgroundColor' => '#F59E0B',
                ],
                [
                    'label' => 'GTY/PTY',
                    'data' => $data->pluck('gty_pty')->toArray(),
                    'backgroundColor' => '#EF4444',
                ],
                [
                    'label' => 'Kontrak',
                    'data' => $data->pluck('kontrak')->toArray(),
                    'backgroundColor' => '#8B5CF6',
                ],
                [
                    'label' => 'Honorer Sekolah',
                    'data' => $data->pluck('honorer')->toArray(),
                    'backgroundColor' => '#64748B',
                ],
            ],
            'labels' => $data->pluck('sekolah_nama')->map(fn($nama) => strtoupper($nama))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'scales' => ['x' => ['stacked' => true], 'y' => ['stacked' => true]],
            'maintainAspectRatio' => false,
        ];
    }
}
