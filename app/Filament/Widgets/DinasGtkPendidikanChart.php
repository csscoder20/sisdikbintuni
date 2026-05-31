<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;

class DinasGtkPendidikanChart extends ChartWidget
{
    protected ?string $heading = 'Grafik GTK Berdasarkan Pendidikan Terakhir di Setiap Sekolah';
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 2;
    protected ?string $maxHeight = null;

    protected function getData(): array
    {
        $data = DB::table('sekolah')
            ->leftJoin('gtk', function ($join) {
                $join->on('sekolah.id', '=', 'gtk.sekolah_id')
                    ->whereNull('gtk.deleted_at');
            })
            ->select(
                'sekolah.nama as sekolah_nama',
                DB::raw("SUM(CASE WHEN gtk.pendidikan_terakhir LIKE 'SMA%' OR gtk.pendidikan_terakhir LIKE 'SMK%' THEN 1 ELSE 0 END) as sma"),
                DB::raw("SUM(CASE WHEN gtk.pendidikan_terakhir LIKE 'D1%' OR gtk.pendidikan_terakhir LIKE 'D-1%' THEN 1 ELSE 0 END) as d1"),
                DB::raw("SUM(CASE WHEN gtk.pendidikan_terakhir LIKE 'D2%' OR gtk.pendidikan_terakhir LIKE 'D-2%' THEN 1 ELSE 0 END) as d2"),
                DB::raw("SUM(CASE WHEN gtk.pendidikan_terakhir LIKE 'D3%' OR gtk.pendidikan_terakhir LIKE 'D-3%' THEN 1 ELSE 0 END) as d3"),
                DB::raw("SUM(CASE WHEN gtk.pendidikan_terakhir LIKE 'S1%' OR gtk.pendidikan_terakhir LIKE 'S-1%' THEN 1 ELSE 0 END) as s1"),
                DB::raw("SUM(CASE WHEN gtk.pendidikan_terakhir LIKE 'S2%' OR gtk.pendidikan_terakhir LIKE 'S-2%' THEN 1 ELSE 0 END) as s2"),
                DB::raw("SUM(CASE WHEN gtk.pendidikan_terakhir LIKE 'S3%' OR gtk.pendidikan_terakhir LIKE 'S-3%' THEN 1 ELSE 0 END) as s3")
            )
            ->whereIn('sekolah.jenjang', ['SMA', 'SMK', 'sma', 'smk'])
            ->whereNull('sekolah.deleted_at')
            ->groupBy('sekolah.id', 'sekolah.nama')
            ->orderBy('sekolah.nama')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'SMA/SMK',
                    'data' => $data->pluck('sma')->toArray(),
                    'backgroundColor' => '#64748B',
                ],
                [
                    'label' => 'D1',
                    'data' => $data->pluck('d1')->toArray(),
                    'backgroundColor' => '#F59E0B',
                ],
                [
                    'label' => 'D2',
                    'data' => $data->pluck('d2')->toArray(),
                    'backgroundColor' => '#FBBF24',
                ],
                [
                    'label' => 'D3',
                    'data' => $data->pluck('d3')->toArray(),
                    'backgroundColor' => '#FCD34D',
                ],
                [
                    'label' => 'S1',
                    'data' => $data->pluck('s1')->toArray(),
                    'backgroundColor' => '#3B82F6',
                ],
                [
                    'label' => 'S2',
                    'data' => $data->pluck('s2')->toArray(),
                    'backgroundColor' => '#1D4ED8',
                ],
                [
                    'label' => 'S3',
                    'data' => $data->pluck('s3')->toArray(),
                    'backgroundColor' => '#1E40AF',
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
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'font' => [
                            'size' => 13,
                            'weight' => '600',
                        ],
                        'padding' => 15,
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'stacked' => true,
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                        ],
                        'maxRotation' => 45,
                        'minRotation' => 45,
                        'autoSkip' => false,
                        'autoSkipPadding' => 0,
                    ],
                ],
                'y' => [
                    'stacked' => true,
                    'beginAtZero' => true,
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                        ],
                    ],
                ],
            ],
            'layout' => [
                'padding' => [
                    'bottom' => 30,
                ],
            ],
            'maintainAspectRatio' => true,
        ];
    }
}
