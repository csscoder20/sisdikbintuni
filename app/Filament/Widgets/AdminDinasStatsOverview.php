<?php

namespace App\Filament\Widgets;

use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\Gtk;
use App\Models\Laporan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class AdminDinasStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalSekolah = Sekolah::count();
        $currentMonth = (int) date('m');
        $currentYear = (int) date('Y');
        $laporCount = Laporan::where('bulan', $currentMonth)->where('tahun', $currentYear)->count();

        return [
            Stat::make('Total Sekolah', $totalSekolah)
                ->description('Jumlah seluruh sekolah terdaftar')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),
            Stat::make('Total Guru & Tenaga Kependidikan', Gtk::count())
                ->description('Agregasi jumlah pegawai')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
            Stat::make('Total Siswa Aktif', Siswa::where('status', 'aktif')->count())
                ->description('Jumlah seluruh siswa aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Kepatuhan Laporan', "{$laporCount}/{$totalSekolah} Sekolah")
                ->description('Bulan ' . Carbon::now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-document-check')
                ->color($laporCount >= $totalSekolah ? 'success' : 'danger'),
        ];
    }
}