<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DinasSiswaChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Jumlah Siswa (Laki-laki & Perempuan) di Setiap Sekolah';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected ?string $maxHeight = '600px';

    protected function getData(): array
    {
        $data = DB::table('sekolah')
            ->leftJoin('siswa', function ($join) {
                $join->on('sekolah.id', '=', 'siswa.sekolah_id')
                     ->whereNull('siswa.deleted_at');
            })
            ->select(
                'sekolah.nama as sekolah_nama',
                DB::raw("SUM(CASE WHEN LOWER(siswa.jenis_kelamin) IN ('laki-laki', 'l') THEN 1 ELSE 0 END) as laki_laki"),
                DB::raw("SUM(CASE WHEN LOWER(siswa.jenis_kelamin) IN ('perempuan', 'p') THEN 1 ELSE 0 END) as perempuan")
            )
            ->whereIn('sekolah.jenjang', ['SMA', 'SMK', 'sma', 'smk'])
            ->whereNull('sekolah.deleted_at')
            ->groupBy('sekolah.id', 'sekolah.nama')
            ->orderBy('sekolah.nama')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Laki-laki',
                    'data' => $data->pluck('laki_laki')->toArray(),
                    'backgroundColor' => '#3B82F6', // Biru
                ],
                [
                    'label' => 'Perempuan',
                    'data' => $data->pluck('perempuan')->toArray(),
                    'backgroundColor' => '#EC4899', // Merah Muda
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
                'y' => ['stacked' => true],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}