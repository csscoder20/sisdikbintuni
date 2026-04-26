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
use App\Filament\Pages\KeadaanGtk;
use App\Filament\Pages\KeadaanSiswa;
use App\Filament\Resources\GtkJamAjars\GtkJamAjarResource;
use App\Filament\Resources\GtkKeuangan\GtkKeuanganResource;
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
            ->darkMode(false)
            ->brandName('ADMIN')
            ->databaseNotifications()
            ->brandLogo(fn () => new \Illuminate\Support\HtmlString('
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #6366f1); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.35);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/>
                            <path d="M2 12h20"/>
                        </svg>
                    </div>
                    <span style="font-size: 1.25rem; font-weight: 700; letter-spacing: -0.02em; color: var(--text-color, #1e293b);">
                        Sisdik<span style="color: #3b82f6;">Bintuni</span>' . (auth()->check() ? ' <span style="font-size:0.95rem; font-weight:600; opacity:0.75; margin-left:2px;">| ' . request()->user()->name . '</span>' : '') . '
                    </span>
                </div>
            '))
            ->profile(isSimple: false)
            ->maxContentWidth(Width::Full)
            ->sidebarCollapsibleOnDesktop()
            ->breadcrumbs(false)
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
                fn (): string => filament()->hasRegistration()
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
                fn (): string => '<p style="text-align:center; margin-top:1.25rem; font-size:0.875rem; color:#6b7280;">
                        Sudah punya akun?
                        <a href="' . filament()->getLoginUrl() . '"
                           style="color:#f97316; font-weight:600; text-decoration:none;">
                            Login Sekarang!
                        </a>
                   </p>'
            )
            ->renderHook('panels::head.done', fn (): string => \Illuminate\Support\Facades\Blade::render("@vite(['resources/css/app.css'])"))
            ->renderHook('panels::body.end', function () {
                return <<<'HTML'
                    <style>
                        .fi-ta-header-cell > * {
                            padding: 4px !important;
                        }
                        .fi-ta-cell > * {
                            padding: 4px !important;
                        }
                        .fi-ta-cell {
                            height: auto !important;
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
            ->userMenuItems([
                MenuItem::make()
                    ->label('Kunjungi Web')
                    ->url('/')
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-globe-alt'),
            ])
            ->breadcrumbs(false)
            ->globalSearch(false)
            ->sidebarWidth('16rem')
            ->brandName('OPERATOR ' . strtoupper($jenjang))
            ->brandLogo(fn () => new \Illuminate\Support\HtmlString('
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/>
                            <path d="M2 12h20"/>
                        </svg>
                    </div>
                    <span style="font-size: 1.25rem; font-weight: 700; letter-spacing: -0.02em; color: var(--text-color, #1e293b);">
                        Sisdik<span style="color: #10b981;">Bintuni</span>' . (auth()->check() ? ' <span style="font-size:0.95rem; font-weight:600; opacity:0.75; margin-left:2px;">| ' . (request()->user()->sekolah?->nama ?? request()->user()->name) . '</span>' : '') . '
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
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->pages([
                OperatorDashboard::class,
                SekolahPage::class,
                KeadaanGtk::class,
                KeadaanSiswa::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->renderHook('panels::head.done', fn (): string => \Illuminate\Support\Facades\Blade::render("@vite(['resources/css/app.css'])"))
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

                        /* Compact tables */
                        .fi-ta-header-cell > * {
                            padding: 4px !important;
                        }
                        .fi-ta-cell > * {
                            padding: 4px !important;
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
