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
        return 'Dashboard Dinas';
    }

    public static function getRoutePath(Panel $panel): string
    {
        return 'dinas';
    }

    public function getHeading(): string | Htmlable
    {
        return 'Dashboard Dinas Pendidikan';
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\AdminDinasStatsOverview::class,
            \App\Filament\Widgets\GtkStatusKepegawaianChart::class,
            \App\Filament\Widgets\GuruPendidikanChart::class,
            // \App\Filament\Widgets\OperatorPending::class,
            \App\Filament\Widgets\LaporanTerbaruWidget::class,
            \App\Filament\Widgets\OperatorActivityLogWidget::class,
        ];
    }
}