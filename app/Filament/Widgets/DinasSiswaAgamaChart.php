<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;

class DinasSiswaAgamaChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Jumlah Siswa Berdasarkan Agama di Setiap Sekolah';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 2;
    protected ?string $maxHeight = '600px';
    protected array | string | null $extraAttributes = ['class' => 'tall-horizontal-chart'];

    protected function getData(): array
    {
        $data = DB::table('sekolah')
            ->leftJoin('siswa', function ($join) {
                $join->on('sekolah.id', '=', 'siswa.sekolah_id')
                    ->whereNull('siswa.deleted_at');
            })
            ->select(
                'sekolah.nama as sekolah_nama',
                DB::raw("SUM(CASE WHEN LOWER(siswa.agama) = 'islam' THEN 1 ELSE 0 END) as islam"),
                DB::raw("SUM(CASE WHEN LOWER(siswa.agama) IN ('kristen protestan', 'kristen') THEN 1 ELSE 0 END) as kristen"),
                DB::raw("SUM(CASE WHEN LOWER(siswa.agama) = 'katolik' THEN 1 ELSE 0 END) as katolik"),
                DB::raw("SUM(CASE WHEN LOWER(siswa.agama) = 'hindu' THEN 1 ELSE 0 END) as hindu"),
                DB::raw("SUM(CASE WHEN LOWER(siswa.agama) = 'buddha' THEN 1 ELSE 0 END) as buddha"),
                DB::raw("SUM(CASE WHEN LOWER(siswa.agama) = 'konghucu' THEN 1 ELSE 0 END) as konghucu")
            )
            ->whereIn('sekolah.jenjang', ['SMA', 'SMK', 'sma', 'smk'])
            ->whereNull('sekolah.deleted_at')
            ->groupBy('sekolah.id', 'sekolah.nama')
            ->orderBy('sekolah.nama')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Islam',
                    'data' => $data->pluck('islam')->toArray(),
                    'backgroundColor' => '#10B981',
                ],
                [
                    'label' => 'Kristen',
                    'data' => $data->pluck('kristen')->toArray(),
                    'backgroundColor' => '#3B82F6',
                ],
                [
                    'label' => 'Katolik',
                    'data' => $data->pluck('katolik')->toArray(),
                    'backgroundColor' => '#EF4444',
                ],
                [
                    'label' => 'Hindu',
                    'data' => $data->pluck('hindu')->toArray(),
                    'backgroundColor' => '#F59E0B',
                ],
                [
                    'label' => 'Buddha',
                    'data' => $data->pluck('buddha')->toArray(),
                    'backgroundColor' => '#8B5CF6',
                ],
                [
                    'label' => 'Konghucu',
                    'data' => $data->pluck('konghucu')->toArray(),
                    'backgroundColor' => '#6B7280',
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
            'scales' => [
                'x' => ['stacked' => true],
                'y' => [
                    'stacked' => true,
                    'ticks' => [
                        'autoSkip' => false,
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
