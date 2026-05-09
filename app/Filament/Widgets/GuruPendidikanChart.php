<?php

namespace App\Filament\Widgets;

use App\Models\Gtk;
use Filament\Widgets\ChartWidget;

class GuruPendidikanChart extends ChartWidget
{
    protected ?string $heading = 'Jumlah Guru Berdasarkan Pendidikan Terakhir';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $pendidikan = ['D3', 'S1', 'S2', 'S3'];
        $data = [];

        foreach ($pendidikan as $p) {
            $data[] = Gtk::whereIn('jenis_gtk', ['Guru', 'Kepala Sekolah'])
                ->where(function($query) use ($p) {
                    $query->where('pendidikan_terakhir', 'like', $p . '%')
                        ->orWhere('pendidikan_terakhir', 'like', substr($p, 0, 1) . '-' . substr($p, 1) . '%');
                })
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Guru',
                    'data' => $data,
                    'backgroundColor' => [
                        '#f59e0b', // Amber
                        '#3b82f6', // Biru
                        '#10b981', // Hijau
                        '#8b5cf6', // Ungu
                    ],
                ],
            ],
            'labels' => $pendidikan,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}