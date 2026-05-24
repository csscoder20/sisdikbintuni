<?php

namespace App\Filament\Widgets;

use App\Models\Gtk;

class GtkStatusKepegawaianChart extends ChartWidget
{
    protected ?string $heading = 'Jumlah GTK Berdasarkan Status Kepegawaian';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $statuses = ['PNS', 'CPNS', 'PPPK', 'GTY/PTY', 'Kontrak', 'Honorer Sekolah'];

        $guruData = [];
        $tendikData = [];

        foreach ($statuses as $status) {
            $guruData[] = Gtk::whereIn('jenis_gtk', ['Guru', 'Kepala Sekolah'])->where('status_kepegawaian', $status)->count();
            $tendikData[] = Gtk::where('jenis_gtk', 'Tenaga Administrasi')->where('status_kepegawaian', $status)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Guru & Kepsek',
                    'data' => $guruData,
                    'backgroundColor' => '#3b82f6', // Biru
                ],
                [
                    'label' => 'Tenaga Administrasi',
                    'data' => $tendikData,
                    'backgroundColor' => '#10b981', // Hijau
                ],
            ],
            'labels' => $statuses,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
