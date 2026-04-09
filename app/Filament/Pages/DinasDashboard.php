<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Panel;
use Illuminate\Contracts\Support\Htmlable;

// class DinasDashboard extends BaseDashboard
// {
//     protected static bool $shouldRegisterNavigation = false;
//     public static function getNavigationIcon(): ?string
//     {
//         return 'heroicon-o-home';
//     }

//     public function getTitle(): string | Htmlable
//     {
//         return 'Dashboard Dinas';
//     }

//     public static function getRoutePath(Panel $panel): string
//     {
//         return 'dinas';
//     }

//     public function getHeading(): string | Htmlable
//     {
//         return 'Dashboard Dinas Pendidikan';
//     }
// }


class DinasDashboard extends BaseDashboard
{
    public static function canAccess(): bool
    {
        return auth()->check() && !auth()->user()->hasRole('operator');
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
}


// class DinasDashboard extends BaseDashboard
// {
//     protected string $view = 'filament.pages.dinas-dashboard';

//     public static function getNavigationLabel(): string
//     {
//         return 'DASHBOARD';
//     }

//     public static function getRoutePath(Panel $panel): string
//     {
//         return '/';
//     }
// }