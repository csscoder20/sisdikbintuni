<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            \Filament\Widgets\StatsOverviewWidget\Stat::make('Total Sekolah', \App\Models\Sekolah::count())
                ->description('Sekolah terdaftar')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            \Filament\Widgets\StatsOverviewWidget\Stat::make('Total Siswa', \App\Models\Siswa::count())
                ->description('Siswa aktif')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            \Filament\Widgets\StatsOverviewWidget\Stat::make('Total Guru (GTK)', \App\Models\Gtk::count())
                ->description('Guru & Tenaga Kependidikan')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),
        ];
    }
}
