<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;

class DinasSiswaDaerahChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Jumlah Siswa Berdasarkan Daerah Asal di Setiap Sekolah';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 2;
    protected ?string $maxHeight = null;

    protected function getData(): array
    {
        $data = DB::table('sekolah')
            ->leftJoin('siswa', function ($join) {
                $join->on('sekolah.id', '=', 'siswa.sekolah_id')
                    ->whereNull('siswa.deleted_at');
            })
            ->select(
                'sekolah.nama as sekolah_nama',
                DB::raw("SUM(CASE WHEN LOWER(siswa.daerah_asal) = 'papua' THEN 1 ELSE 0 END) as papua"),
                DB::raw("SUM(CASE WHEN LOWER(siswa.daerah_asal) = 'non papua' THEN 1 ELSE 0 END) as non_papua")
            )
            ->whereIn('sekolah.jenjang', ['SMA', 'SMK', 'sma', 'smk'])
            ->whereNull('sekolah.deleted_at')
            ->groupBy('sekolah.id', 'sekolah.nama')
            ->orderBy('sekolah.nama')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Papua',
                    'data' => $data->pluck('papua')->toArray(),
                    'backgroundColor' => '#F59E0B', // Amber
                ],
                [
                    'label' => 'Non Papua',
                    'data' => $data->pluck('non_papua')->toArray(),
                    'backgroundColor' => '#10B981', // Emerald
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
