<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;

class DinasSarprasChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Kondisi Sarana Prasarana di Setiap Sekolah';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 2;
    protected ?string $maxHeight = '600px';

    protected function getData(): array
    {
        $data = DB::table('sekolah')
            ->leftJoin('laporan', 'sekolah.id', '=', 'laporan.sekolah_id')
            ->leftJoin('laporan_gedung', 'laporan.id', '=', 'laporan_gedung.laporan_id')
            ->select(
                'sekolah.nama as sekolah_nama',
                DB::raw('COALESCE(SUM(laporan_gedung.jumlah_baik), 0) as baik'),
                DB::raw('COALESCE(SUM(laporan_gedung.jumlah_rusak), 0) as rusak')
            )
            ->whereIn('sekolah.jenjang', ['SMA', 'SMK', 'sma', 'smk'])
            ->whereNull('sekolah.deleted_at')
            ->groupBy('sekolah.id', 'sekolah.nama')
            ->orderBy('sekolah.nama')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Kondisi Baik',
                    'data' => $data->pluck('baik')->toArray(),
                    'backgroundColor' => '#10B981',
                ],
                [
                    'label' => 'Kondisi Rusak',
                    'data' => $data->pluck('rusak')->toArray(),
                    'backgroundColor' => '#EF4444',
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
            'indexAxis' => 'y', // Membuat bar chart menjadi horizontal
            'scales' => [
                'x' => [
                    'stacked' => true,
                ],
                'y' => [
                    'stacked' => true,
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
