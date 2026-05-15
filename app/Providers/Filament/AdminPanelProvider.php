<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use App\Models\Sekolah;
use App\Filament\Pages\OperatorDashboard;
use App\Filament\Pages\DinasDashboard;
use App\Filament\Pages\SuperAdminDashboard;
use App\Filament\Pages\SekolahPage;
use Filament\Facades\Filament;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\MenuItem;
use Filament\Support\Enums\Width;
use App\Filament\Pages\Auth\CustomLogin;
use App\Filament\Pages\Auth\CustomRegister;
use App\Filament\Pages\Auth\CustomRequestPasswordReset;
use App\Filament\Pages\KeadaanGtk;

use App\Filament\Pages\KeadaanSiswa;
use App\Filament\Resources\GtkJamAjars\GtkJamAjarResource;
use App\Filament\Resources\GtkRiwayatPendidikans\GtkRiwayatPendidikanResource;
use App\Filament\Resources\Gtks\GtkResource;
use App\Filament\Resources\KehadiranGtk\KehadiranGtkResource;
use App\Filament\Resources\Kelulusan\KelulusanResource;
use App\Filament\Resources\LaporanGedung\LaporanGedungResource;
use App\Filament\Resources\Mapels\MapelResource;
use App\Filament\Resources\Rombels\RombelResource;
use App\Filament\Resources\Siswas\SiswaResource;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('dinas')
            ->path('admin')
            ->passwordReset(CustomRequestPasswordReset::class)
            ->emailVerification()

            ->darkMode(false)
            ->brandName('ADMIN')
            ->databaseNotifications()
            ->brandLogo(fn() => request()->routeIs('filament.*.auth.*')
                ? new \Illuminate\Support\HtmlString('
                    <style>.fi-logo { height: auto !important; margin-bottom: 1rem; }</style>
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; text-align: center;">
                        <img src="' . asset('storage/logo/logo-bintuni.png') . '" style="height: 5rem; width: auto; object-fit: contain;">
                        <div style="font-size: 1rem; line-height: 1.5rem; font-weight: 700; letter-spacing: -0.025em; color: inherit;">
                            Sistem Pelaporan Bulanan <span style="color: #ea580c;">SMA/SMK</span>
                        </div>
                    </div>
                ')




                : new \Illuminate\Support\HtmlString('
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; background: white; padding: 2px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                        <img src="' . asset('assets/logo/logo-bintuni.png') . '" alt="Logo Bintuni" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <span style="font-size: 1.25rem; font-weight: 700; letter-spacing: -0.02em; color: var(--text-color, #1e293b);">
                        Sistem Pelaporan Bulanan<span style="color: #3b82f6;"> SMA/SMK</span>' . (auth()->check() ? ' <span style="font-size:0.95rem; font-weight:600; opacity:0.75; margin-left:2px;">| ' . request()->user()->name . '</span>' : '') . '
                    </span>
                </div>
            '))

            ->profile(isSimple: false)
            ->maxContentWidth(Width::Full)
            ->sidebarCollapsibleOnDesktop()
            ->breadcrumbs(true)
            ->globalSearch(false)
            ->sidebarWidth('16rem')
            ->userMenuItems([
                MenuItem::make()
                    ->label('Kunjungi Web')
                    ->url('/')
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-globe-alt'),
            ])
            ->login(CustomLogin::class)
            ->registration(CustomRegister::class)
            ->colors([
                'primary' => Color::Orange,
            ])
            ->resources([
                \App\Filament\Resources\Users\UserResource::class,
                \App\Filament\Resources\Sekolahs\SekolahResource::class,
                \App\Filament\Resources\ActivityLog\ActivityLogResource::class,
                \App\Filament\Resources\Notifikasis\NotifikasiResource::class,

            ])
            ->pages([
                SuperAdminDashboard::class,
                DinasDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\Filament\RedirectIncorrectPanel::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                'panels::auth.login.form.after',
                fn(): string => filament()->hasRegistration()
                    ? '<p style="text-align:center; margin-top:1.25rem; font-size:0.875rem; color:#6b7280;">
                            Belum punya akun?
                            <a href="' . filament()->getRegistrationUrl() . '"
                               style="color:#f97316; font-weight:600; text-decoration:none;">
                                Daftar Sekarang!
                            </a>
                       </p>'
                    : ''
            )
            ->renderHook(
                'panels::auth.register.form.after',
                fn(): string => '<p style="text-align:center; margin-top:1.25rem; font-size:0.875rem; color:#6b7280;">
                        Sudah punya akun?
                        <a href="' . filament()->getLoginUrl() . '"
                           style="color:#f97316; font-weight:600; text-decoration:none;">
                            Login Sekarang!
                        </a>
                   </p>'
            )
            ->renderHook(
                'panels::head.done',
                fn(): string =>
                \Illuminate\Support\Facades\Blade::render("@vite(['resources/css/app.css'])") .
                    '<link rel="icon" type="image/png" href="/favicon.png?v=20260507b">' .
                    '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico?v=20260507b">' .
                    '<link rel="apple-touch-icon" href="/favicon.png?v=20260507b">'
            )
            ->renderHook('panels::table.container.after', function () {
                return <<<'HTML'
                    <div wire:loading.delay class="fi-ta-loading-overlay fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/10 backdrop-blur-[1px]">
                        <div class="p-3 bg-white dark:bg-gray-900 rounded-xl shadow-xl border border-gray-200 dark:border-gray-800 flex items-center gap-3">
                            <svg class="animate-spin h-5 w-5" style="color: rgb(var(--primary-600))" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Memuat data...</span>
                        </div>
                    </div>
                HTML;
            })
            ->renderHook('panels::body.start', function () {
                if (!session()->has('impersonating_sekolah_id')) {
                    return '';
                }
                $sekolah = \App\Models\Sekolah::find(session('impersonating_sekolah_id'));
                $namaSekolah = $sekolah?->nama ?? 'Sekolah';
                return \Illuminate\Support\Facades\Blade::render('
                    <div style="background-color: transparent; color: #ea580c; padding: 8px 12px; text-align: center; font-size: 14px; font-weight: bold; display: flex; justify-content: center; align-items: center; gap: 16px; position: sticky; top: 0; z-index: 40; box-shadow: none; width: 100%; pointer-events: none;">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Anda sedang mengakses panel sebagai Operator {{ $namaSekolah }}
                        </span>
                        <a href="{{ route(\'stop-impersonating\') }}" style="background-color: white; color: #ea580c; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: bold; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: background-color 0.2s; pointer-events: auto;" onmouseover="this.style.backgroundColor=\'#f3f4f6\'" onmouseout="this.style.backgroundColor=\'white\'">
                            HENTIKAN & KELUAR
                        </a>
                    </div>
                ', ['namaSekolah' => $namaSekolah]);
            })

            ->renderHook('panels::body.end', function () {
                return <<<'HTML'
                    <style>
                        .fi-ta-header-cell > * {
                            padding-top: 0px !important;
                            padding-bottom: 0px !important;
                        }
                        .fi-ta-cell > * {
                            padding-top: 0px !important;
                            padding-bottom: 0px !important;
                        }
                        .fi-ta-cell {
                            height: auto !important;
                            min-height: unset !important;
                        }
                        .fi-modal-actions,
                        .fi-modal-footer-actions,
                        .fi-fo-modal-actions {
                            justify-content: flex-end !important;
                        }
                    </style>
                HTML;
            });
    }

    public function register(): void
    {
        parent::register();

        $jenjangs = ['sma', 'smk'];

        foreach ($jenjangs as $jenjang) {
            Filament::registerPanel(
                $this->createSchoolPanel($jenjang)
            );
        }
    }

    protected function createSchoolPanel(string $jenjang): Panel
    {
        return Panel::make()
            ->id($jenjang)
            ->path("admin/{$jenjang}")
            ->colors([
                'primary' => Color::Green,
            ])
            ->maxContentWidth(Width::Full)
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->profile()
            ->passwordReset(CustomRequestPasswordReset::class)
            ->emailVerification()

            ->userMenuItems([
                MenuItem::make()
                    ->label('Kunjungi Web')
                    ->url('/')
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-globe-alt'),
            ])
            ->breadcrumbs()
            ->globalSearch(false)
            ->sidebarWidth('16rem')
            ->brandName('OPERATOR ' . strtoupper($jenjang))
            ->brandLogo(fn() => request()->routeIs('filament.*.auth.*')
                ? new \Illuminate\Support\HtmlString('
                    <style>.fi-logo { height: auto !important; margin-bottom: 1rem; }</style>
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem; text-align: center;">
                        <img src="' . asset('storage/logo/logo-bintuni.png') . '" style="height: 5rem; width: auto; object-fit: contain;">
                        <div style="font-size: 1rem; line-height: 1.5rem; font-weight: 700; letter-spacing: -0.025em; color: inherit;">
                            Sistem Pelaporan Bulanan <span style="color: #10b981;">SMA/SMK</span>
                        </div>
                    </div>
                ')




                : new \Illuminate\Support\HtmlString('
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; background: white; padding: 2px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                        <img src="' . asset('storage/logo/logo-bintuni.png') . '" alt="Logo Bintuni" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <span style="font-size: 1.25rem; font-weight: 700; letter-spacing: -0.02em; color: var(--text-color, #1e293b);">
                        Sistem Pelaporan Bulanan<span style="color: #10b981;"> SMA/SMK</span>' . (auth()->check() ? ' <span style="font-size:0.95rem; font-weight:600; opacity:0.75; margin-left:2px;">| ' . (request()->user()->sekolah?->nama ?? request()->user()->name) . '</span>' : '') . '
                    </span>
                </div>
            '))

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\Filament\RedirectIncorrectPanel::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->tenant(Sekolah::class)
            ->tenantMenu(false)
            ->navigationGroups([
                'Data Sekolah',
                'Data Siswa',
                'Data GTK',
                'Laporan Bulanan',
                'Validasi',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->pages([
                OperatorDashboard::class,
                SekolahPage::class,
                KeadaanGtk::class,
                KeadaanSiswa::class,
                \App\Filament\Pages\ValidasiData::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->renderHook(
                'panels::head.done',
                fn(): string =>
                \Illuminate\Support\Facades\Blade::render("@vite(['resources/css/app.css'])") .
                    '<link rel="icon" type="image/png" href="/favicon.png?v=20260507b">' .
                    '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico?v=20260507b">' .
                    '<link rel="apple-touch-icon" href="/favicon.png?v=20260507b">'
            )
            ->renderHook('panels::table.container.after', function () {
                return <<<'HTML'
                    <div wire:loading.delay class="fi-ta-loading-overlay fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/10 backdrop-blur-[1px]">
                        <div class="p-3 bg-white dark:bg-gray-900 rounded-xl shadow-xl border border-gray-200 dark:border-gray-800 flex items-center gap-3">
                            <svg class="animate-spin h-5 w-5" style="color: rgb(var(--primary-600))" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Memuat data...</span>
                        </div>
                    </div>
                HTML;
            })
            ->renderHook('panels::body.start', function () {
                if (!session()->has('impersonating_sekolah_id')) {
                    return '';
                }
                $sekolah = \App\Models\Sekolah::find(session('impersonating_sekolah_id'));
                $namaSekolah = $sekolah?->nama ?? 'Sekolah';
                return \Illuminate\Support\Facades\Blade::render('
                    <div style="background-color: transparent; color: #ea580c; padding: 8px 12px; text-align: center; font-size: 14px; font-weight: bold; display: flex; justify-content: center; align-items: center; gap: 16px; position: sticky; top: 0; z-index: 40; box-shadow: none; width: 100%; pointer-events: none;">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Anda sedang mengakses panel sebagai Operator {{ $namaSekolah }}
                        </span>
                        <a href="{{ route(\'stop-impersonating\') }}" style="background-color: white; color: #ea580c; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: bold; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: background-color 0.2s; pointer-events: auto;" onmouseover="this.style.backgroundColor=\'#f3f4f6\'" onmouseout="this.style.backgroundColor=\'white\'">
                            HENTIKAN & KELUAR
                        </a>
                    </div>
                ', ['namaSekolah' => $namaSekolah]);
            })

            ->renderHook('panels::body.end', function () {
                return <<<'HTML'
                    <style>
                        .fi-sidebar-nav::-webkit-scrollbar {
                            display: none;
                        }

                        .fi-sidebar-nav {
                            -ms-overflow-style: none;
                            scrollbar-width: none;
                        }
                        
                        .max-w-7xl {
                            max-width: 100%;
                        }

                        .fi-ta-header-cell > * {
                            padding-top: 0px !important;
                            padding-bottom: 0px !important;
                        }
                        .fi-ta-cell > * {
                            padding-top: 0px !important;
                            padding-bottom: 0px !important;
                        }
                        .fi-ta-cell {
                            height: auto !important;
                            min-height: unset !important;
                        }
                        .fi-modal-actions,
                        .fi-modal-footer-actions,
                        .fi-fo-modal-actions {
                            justify-content: flex-end !important;
                        }
                    </style>
                HTML;
            });
    }
}
