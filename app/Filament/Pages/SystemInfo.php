<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;
use Throwable;

class SystemInfo extends Page
{
    protected static ?string $navigationLabel = 'Info Sistem';
    protected static ?string $title = 'Info Sistem';
    protected static ?int $navigationSort = 100;
    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;
    protected string $view = 'filament.pages.system-info';

    public static function canAccess(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'dinas'
            && auth()->user()?->hasRole('super_admin');
    }

    public function getApplicationInfo(): array
    {
        return [
            ['label' => 'Nama aplikasi', 'value' => config('app.name')],
            ['label' => 'Environment', 'value' => strtoupper(config('app.env'))],
            ['label' => 'Debug mode', 'value' => config('app.debug') ? 'Aktif' : 'Nonaktif', 'status' => config('app.debug') ? 'warning' : 'success'],
            ['label' => 'Zona waktu', 'value' => config('app.timezone')],
            ['label' => 'Laravel', 'value' => app()->version()],
            ['label' => 'Livewire', 'value' => defined('Livewire\\Livewire::VERSION') ? \Livewire\Livewire::VERSION : 'Terdeteksi'],
        ];
    }

    public function getRuntimeInfo(): array
    {
        return [
            ['label' => 'Versi PHP', 'value' => PHP_VERSION],
            ['label' => 'PHP SAPI', 'value' => PHP_SAPI],
            ['label' => 'Sistem operasi', 'value' => PHP_OS_FAMILY . ' (' . php_uname('r') . ')'],
            ['label' => 'Web server', 'value' => $_SERVER['SERVER_SOFTWARE'] ?? 'PHP built-in server'],
            ['label' => 'Batas memori', 'value' => ini_get('memory_limit') ?: 'Tidak terbatas'],
            ['label' => 'Batas unggah file', 'value' => ini_get('upload_max_filesize') ?: 'Tidak tersedia'],
        ];
    }

    public function getFilamentInfo(): array
    {
        return [
            ['label' => 'Versi Filament', 'value' => \Composer\InstalledVersions::getPrettyVersion('filament/filament') ?? 'Tidak diketahui'],
            ['label' => 'Panel aktif', 'value' => Filament::getCurrentPanel()?->getId() ?? 'Tidak diketahui'],
        ];
    }

    public function getDatabaseInfo(): array
    {
        $base = [
            ['label' => 'Koneksi', 'value' => strtoupper(config('database.default'))],
            ['label' => 'Database', 'value' => config('database.connections.' . config('database.default') . '.database')],
            ['label' => 'Host', 'value' => config('database.connections.' . config('database.default') . '.host')],
        ];

        try {
            $version = DB::selectOne('SHOW server_version');
            $base[] = ['label' => 'PostgreSQL', 'value' => $version->server_version ?? 'Terhubung', 'status' => 'success'];
        } catch (Throwable) {
            $base[] = ['label' => 'PostgreSQL', 'value' => 'Tidak terhubung', 'status' => 'danger'];
        }

        return $base;
    }

    public function getExtensions(): array
    {
        return [
            'pdo_pgsql' => extension_loaded('pdo_pgsql'),
            'pgsql' => extension_loaded('pgsql'),
            'mbstring' => extension_loaded('mbstring'),
            'intl' => extension_loaded('intl'),
            'gd' => extension_loaded('gd'),
            'zip' => extension_loaded('zip'),
        ];
    }
}
