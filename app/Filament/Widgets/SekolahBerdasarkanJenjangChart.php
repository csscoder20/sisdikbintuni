<?php

namespace App\Filament\Widgets;

use App\Models\Sekolah;
use Filament\Widgets\ChartWidget;

class SekolahBerdasarkanJenjangChart extends ChartWidget
{
    protected ?string $heading = 'Jumlah Sekolah Berdasarkan Jenjang';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $smaCount = Sekolah::where(function($q) {
            $q->where('jenjang', 'sma')->orWhere('nama', 'ilike', '%sma%');
        })->count();

        $smkCount = Sekolah::where(function($q) {
            $q->where('jenjang', 'smk')->orWhere('nama', 'ilike', '%smk%');
        })->count();

        return [
            'datasets' => [
                [
                    'label' => 'Total Sekolah',
                    'data' => [$smaCount, $smkCount],
                    'backgroundColor' => [
                        '#f97316', // Orange
                        '#10b981', // Green
                    ],
                ],
            ],
            'labels' => ['SMA', 'SMK'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}