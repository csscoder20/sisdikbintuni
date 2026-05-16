<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Panel;
use Illuminate\Contracts\Support\Htmlable;

class DinasDashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dasbor';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin_dinas');
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-home';
    }

    public function getTitle(): string | Htmlable
    {
        $selectedId = session('dinas_selected_sekolah_id');
        if ($selectedId) {
            $sekolah = \App\Models\Sekolah::find($selectedId);
            return "Dashboard " . ($sekolah?->nama ?? 'Sekolah');
        }
        return 'Dashboard Dinas';
    }

    public static function getRoutePath(Panel $panel): string
    {
        return 'dinas';
    }

    public function getHeading(): string | Htmlable
    {
        $selectedId = session('dinas_selected_sekolah_id');
        if ($selectedId) {
            $sekolah = \App\Models\Sekolah::find($selectedId);
            return "Dashboard " . ($sekolah?->nama ?? 'Sekolah');
        }
        return 'Dashboard Dinas Pendidikan';
    }

    public function getWidgets(): array
    {
        $selectedId = session('dinas_selected_sekolah_id');
        
        if ($selectedId) {
            // Widget khusus konteks sekolah
            return [
                \App\Filament\Widgets\StatsOverview::class, // Widget statistik sekolah
                \App\Filament\Widgets\GtkStatusKepegawaianChart::class,
                \App\Filament\Widgets\GuruPendidikanChart::class,
            ];
        }

        // Widget global dinas
        return [
            \App\Filament\Widgets\AdminDinasStatsOverview::class,
            \App\Filament\Widgets\GtkStatusKepegawaianChart::class,
            \App\Filament\Widgets\GuruPendidikanChart::class,
            \App\Filament\Widgets\LaporanTerbaruWidget::class,
            \App\Filament\Widgets\OperatorActivityLogWidget::class,
        ];
    }
}