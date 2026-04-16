<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Panel;
use Illuminate\Contracts\Support\Htmlable;

class SuperAdminDashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dasbor';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->isSuperAdmin();
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-home';
    }

    public function getTitle(): string | Htmlable
    {
        return 'Dashboard Superadmin';
    }

    public static function getRoutePath(Panel $panel): string
    {
        return 'superadmin';
    }

    public function getHeading(): string | Htmlable
    {
        return 'Dashboard Super Admin';
    }
}
