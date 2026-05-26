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
use App\Filament\Pages\CetakCustom;
use App\Filament\Pages\RiwayatLaporan;
use App\Filament\Pages\KeadaanGtk;
use App\Filament\Pages\KeadaanSiswa;
use App\Filament\Pages\ValidasiData;
use App\Filament\Pages\CustomProfile;
use App\Filament\Resources\CetakLaporan\CetakLaporanResource;
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
use App\Filament\Pages\Auth\CustomResetPassword;
use App\Filament\Pages\HelpGuide;
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
            ->passwordReset(CustomRequestPasswordReset::class, CustomResetPassword::class)
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
                            Sistem Pelaporan Bulanan <span style="color: #ea580c;">Satuan Pendidikan</span>
                        </div>
                    </div>
                ')
                : new \Illuminate\Support\HtmlString('
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; background: white; padding: 2px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                        <img src="' . asset('assets/logo/logo-bintuni.png') . '" alt="Logo Bintuni" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <span style="font-size: 1.25rem; font-weight: 700; letter-spacing: -0.02em; color: var(--text-color, #1e293b);">
                        Sistem Pelaporan Bulanan<span style="color: #3b82f6;"> Satuan Pendidikan</span>' . (auth()->check() ? ' <span style="font-size:0.95rem; font-weight:600; opacity:0.75; margin-left:2px;">| ' . request()->user()->name . '</span>' : '') . '
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
                'profile' => MenuItem::make()
                    ->label('Pengaturan Akun')
                    ->url(fn() => CustomProfile::getUrl())
                    ->icon('heroicon-o-user-circle'),
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
                \App\Filament\Resources\Siswas\SiswaResource::class,
                \App\Filament\Resources\Gtks\GtkResource::class,
                \App\Filament\Resources\Rombels\RombelResource::class,
                \App\Filament\Resources\LaporanGedung\LaporanGedungResource::class,
                \App\Filament\Resources\Mapels\MapelResource::class,
                \App\Filament\Resources\LaporanKeuangan\LaporanKeuanganResource::class,
                \App\Filament\Resources\GtkRiwayatPendidikans\GtkRiwayatPendidikanResource::class,
                \App\Filament\Resources\GtkKeuangan\GtkKeuanganResource::class,
                \App\Filament\Resources\GtkJamAjars\GtkJamAjarResource::class,
                \App\Filament\Resources\KehadiranGtk\KehadiranGtkResource::class,
                \App\Filament\Resources\CetakLaporan\CetakLaporanResource::class,
                \App\Filament\Resources\ActivityLog\ActivityLogResource::class,
            ])
            ->pages([
                SuperAdminDashboard::class,
                DinasDashboard::class,
                SekolahPage::class,
                CetakCustom::class,
                CustomProfile::class,
                RiwayatLaporan::class,
                \App\Filament\Pages\ValidasiData::class,
                HelpGuide::class,
            ])
            ->navigationGroups([
                'Data Sekolah',
                'Data Siswa',
                'Data GTK',
                'Data Master',
                'Validasi/Verifikasi',
                'Cetak',
                'Sistem',
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

            // 1. SCHOOL SELECTOR (paling kiri setelah breadcrumbs)
            ->renderHook(
                'panels::global-search.before',
                fn(): string => \Illuminate\Support\Facades\Blade::render('<livewire:school-selector />'),
            )

            ->renderHook(
                'panels::global-search.after',
                fn(): string => \Illuminate\Support\Facades\Blade::render('<livewire:validation-period-toggle />'),
            )

            // 2. HELP GUIDE (di antara school selector dan notifikasi)
            ->renderHook(
                'panels::global-search.after',
                fn(): string => \Illuminate\Support\Facades\Blade::render('
                    <div class="flex items-center gap-x-2">
                        <a href="' . HelpGuide::getUrl() . '"
                            class="flex items-center justify-center rounded-full w-9 h-9 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 transition"
                            title="Panduan Penggunaan">
                            <x-filament::icon
                                icon="heroicon-o-question-mark-circle"
                                class="w-6 h-6 text-gray-400 dark:text-gray-400" />
                        </a>
                    </div>
                ')
            )

            ->renderHook(
                'panels::user-menu.before',
                fn(): string => self::renderTopbarUserName()
            )

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
                    '<link rel="apple-touch-icon" href="/favicon.png?v=20260507b">' .
                    (request()->routeIs('filament.*.auth.*') ? '<script src="https://www.google.com/recaptcha/api.js" async defer></script>' : '')
            )
            ->renderHook('panels::body.end', function () {
                return self::renderGlobalOperationLoader() . <<<'HTML'
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

    protected static function renderTopbarUserName(): string
    {
        if (!auth()->check()) {
            return '';
        }

        $user = auth()->user();
        $avatarUrl = $user->avatar_url ?? $user->profile_photo_url ?? null;

        return \Illuminate\Support\Facades\Blade::render('
        <style>
            /* Reset user menu trigger styling */
            .fi-topbar .fi-user-menu-trigger {
                display: flex !important;
                align-items: center !important;
                gap: 0.75rem !important;
                padding: 5px !important;
                background: transparent !important;
                border-radius: 40px !important;
                border: 1px solid #e2e8f0 !important;
                transition: all 0.2s ease !important;
            }
            
            .fi-topbar .fi-user-menu-trigger:hover {
                background: #f8fafc !important;
                border-color: #cbd5e1 !important;
            }
            
            /* Avatar styling */
            .fi-topbar .fi-user-menu-trigger .fi-avatar,
            .fi-topbar .fi-user-menu-trigger .fi-user-avatar {
                width: 30px !important;
                height: 30px !important;
                border-radius: 50% !important;
                overflow: hidden !important;
                flex-shrink: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            
            /* Avatar fallback text */
            .fi-topbar .fi-user-menu-trigger .fi-avatar span,
            .fi-topbar .fi-user-menu-trigger .fi-user-avatar span {
                font-size: 14px !important;
                font-weight: 600 !important;
                color: white !important;
            }
            
            /* Avatar image */
            .fi-topbar .fi-user-menu-trigger .fi-avatar img,
            .fi-topbar .fi-user-menu-trigger .fi-user-avatar img {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
            }
            
            /* Name wrapper */
            .custom-topbar-user-wrapper {
                display: none;
                flex-direction: column;
                align-items: flex-start;
                gap: 2px;
                margin-left: 5px !important;
            }

            @media (min-width: 768px) {
                .custom-topbar-user-wrapper {
                    display: flex;
                }
            }
            
            /* User name */
            .custom-topbar-user-name {
                font-size: 0.875rem;
                font-weight: 600;
                line-height: 1.2;
                color: #1e293b;
                white-space: nowrap;
            }
            
            /* User role */
            .custom-topbar-user-role {
                font-size: 0.7rem;
                font-weight: 500;
                line-height: 1.2;
                color: #64748b;
                white-space: nowrap;
            }
            
            /* Hide original user menu text */
            .fi-topbar .fi-user-menu-trigger .fi-user-menu-trigger-text {
                display: none !important;
            }
            
            /* Dark mode styles */
            .dark .fi-topbar .fi-user-menu-trigger {
                border-color: #334155 !important;
            }
            
            .dark .fi-topbar .fi-user-menu-trigger:hover {
                background: #1e293b !important;
                border-color: #475569 !important;
            }
            
            .dark .custom-topbar-user-name {
                color: #f1f5f9 !important;
            }
            
            .dark .custom-topbar-user-role {
                color: #94a3b8 !important;
            }
        </style>

        <div class="custom-topbar-user-wrapper">
            <span class="custom-topbar-user-name">{{ $name }}</span>
            <span class="custom-topbar-user-role text-capitalize">
                {{ $role }}
            </span>
        </div>
    ', [
            'name' => self::getTopbarDisplayName(),
            'role' => self::getUserRole(),
            'avatarUrl' => $avatarUrl,
        ]);
    }

    protected static function getUserRole(): string
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return 'Super Admin';
        }

        if ($user->hasRole('admin_dinas')) {
            return 'Admin Dinas';
        }

        if ($user->hasRole('operator')) {
            return 'Operator Sekolah';
        }

        return $user->roles->first()?->name ?? 'User';
    }

    protected static function getTopbarDisplayName(): string
    {
        $user = auth()->user();

        // Try to get full name from user
        $name = trim((string) ($user->name ?? ''));

        // If name is empty, try to get from sekolah or username
        if (empty($name)) {
            $name = trim((string) ($user->username ?? ''));
        }

        if (empty($name)) {
            $name = trim((string) ($user->email ?? 'User'));
        }

        // Limit to 2-3 words for better display
        $parts = preg_split('/\s+/', $name) ?: [];
        return implode(' ', array_slice(array_filter($parts), 0, 2));
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
            ->passwordReset(CustomRequestPasswordReset::class, CustomResetPassword::class)
            ->emailVerification()

            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('Pengaturan Akun')
                    ->url(fn() => auth()->check() && auth()->user()->sekolah
                        ? CustomProfile::getUrl(tenant: auth()->user()->sekolah)
                        : '#')
                    ->icon('heroicon-o-user-circle'),
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
                            Sistem Pelaporan Bulanan <span style="color: #10b981;">Satuan Pendidikan</span>
                        </div>
                    </div>
                ')
                : new \Illuminate\Support\HtmlString('
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; background: white; padding: 2px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                        <img src="' . asset('storage/logo/logo-bintuni.png') . '" alt="Logo Bintuni" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <span style="font-size: 1.25rem; font-weight: 700; letter-spacing: -0.02em; color: var(--text-color, #1e293b);">
                        Sistem Pelaporan Bulanan<span style="color: #10b981;"> Satuan Pendidikan</span>' . (auth()->check() ? ' <span style="font-size:0.95rem; font-weight:600; opacity:0.75; margin-left:2px;">| ' . (request()->user()->sekolah?->nama ?? request()->user()->name) . '</span>' : '') . '
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
                CustomProfile::class,
                HelpGuide::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])

            // HELP GUIDE untuk panel sekolah (di antara breadcrumbs dan notifikasi)
            ->renderHook(
                'panels::global-search.after',
                fn(): string => \Illuminate\Support\Facades\Blade::render('
                    <div class="flex items-center gap-x-2">
                        <a href="' . HelpGuide::getUrl() . '"
                            class="flex items-center justify-center rounded-full w-9 h-9 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 transition"
                            title="Panduan Penggunaan">
                            <x-filament::icon
                                icon="heroicon-o-question-mark-circle"
                                class="w-6 h-6 text-gray-400 dark:text-gray-400" />
                        </a>
                    </div>
                ')
            )

            ->renderHook(
                'panels::user-menu.before',
                fn(): string => \App\Providers\Filament\AdminPanelProvider::renderTopbarUserName()
            )

            ->renderHook(
                'panels::head.done',
                fn(): string =>
                \Illuminate\Support\Facades\Blade::render("@vite(['resources/css/app.css'])") .
                    '<link rel="icon" type="image/png" href="/favicon.png?v=20260507b">' .
                    '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico?v=20260507b">' .
                    '<link rel="apple-touch-icon" href="/favicon.png?v=20260507b">' .
                    (request()->routeIs('filament.*.auth.*') ? '<script src="https://www.google.com/recaptcha/api.js" async defer></script>' : '')
            )
            ->renderHook('panels::body.start', function () {
                $content = \App\Providers\Filament\AdminPanelProvider::renderValidationPeriodClosedNotice();

                if (!session()->has('impersonating_sekolah_id')) {
                    return $content;
                }
                $sekolah = \App\Models\Sekolah::find(session('impersonating_sekolah_id'));
                $namaSekolah = $sekolah?->nama ?? 'Sekolah';
                return $content . \Illuminate\Support\Facades\Blade::render('
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
                return \App\Providers\Filament\AdminPanelProvider::renderGlobalOperationLoader() . <<<'HTML'
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

    public static function renderValidationPeriodClosedNotice(): string
    {
        if (! \App\Support\ValidationPeriod::isLockedForOperatorPanel()) {
            return '';
        }

        return \Illuminate\Support\Facades\Blade::render('
            <style>
                [data-validation-period-notice] {
                    z-index: 10;
                }

                .fi-dropdown-panel {
                    z-index: 80 !important;
                }
            </style>
            <div data-validation-period-notice style="position: sticky; top: 0; width: 100%; padding: 8px 44px 8px 16px; background: #fef2f2; border-bottom: 1px solid #fecaca; color: #991b1b;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; font-weight: 700; line-height: 1.25rem; text-align: center;">
                    <x-filament::icon icon="heroicon-o-no-symbol" style="width: 18px; height: 18px; flex: none;" />
                    <span>Periode validasi sudah ditutup oleh Admin Dinas. Aksi tambah, edit, hapus, upload, dan validasi sementara tidak dapat dilakukan di sisi Operator.</span>
                </div>
                <button
                    type="button"
                    aria-label="Tutup pemberitahuan"
                    title="Tutup"
                    onclick="this.closest(\'[data-validation-period-notice]\').remove();"
                    style="position: absolute; top: 50%; right: 12px; transform: translateY(-50%); display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border: 0; border-radius: 9999px; background: transparent; color: #991b1b; cursor: pointer;"
                >
                    <x-filament::icon icon="heroicon-o-x-mark" style="width: 18px; height: 18px;" />
                </button>
            </div>
        ');
    }

    protected static function renderGlobalOperationLoader(): string
    {
        return <<<'HTML'
            <div id="app-operation-loader" aria-live="polite" aria-busy="true">
                <div class="app-operation-loader-card">
                    <svg class="app-operation-loader-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="app-operation-loader-track" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="app-operation-loader-path" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <div class="app-operation-loader-content">
                        <div class="app-operation-loader-title" data-operation-loader-message>Memproses operasi...</div>
                        <div class="app-operation-loader-subtitle" data-operation-loader-subtitle>Mohon tunggu, sistem sedang bekerja.</div>
                        <div class="app-operation-loader-progress" data-operation-loader-progress hidden>
                            <div class="app-operation-loader-progress-bar" data-operation-loader-progress-bar></div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                #app-operation-loader {
                    position: fixed;
                    inset: 0;
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 1rem;
                    background: rgba(15, 23, 42, 0.16);
                    opacity: 0;
                    pointer-events: none;
                    transition: opacity 160ms ease;
                    backdrop-filter: blur(2px);
                }

                #app-operation-loader.is-visible {
                    opacity: 1;
                    pointer-events: auto;
                }

                .app-operation-loader-card {
                    display: flex;
                    align-items: center;
                    gap: 0.875rem;
                    width: min(100%, 24rem);
                    border: 1px solid rgba(148, 163, 184, 0.35);
                    border-radius: 0.75rem;
                    background: #ffffff;
                    padding: 1rem;
                    box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18);
                }

                .dark .app-operation-loader-card {
                    border-color: rgba(51, 65, 85, 0.85);
                    background: #111827;
                }

                .app-operation-loader-spinner {
                    width: 1.5rem;
                    height: 1.5rem;
                    flex: none;
                    color: rgb(var(--primary-600));
                    animation: app-operation-loader-spin 1s linear infinite;
                }

                .app-operation-loader-track {
                    opacity: 0.25;
                }

                .app-operation-loader-path {
                    opacity: 0.85;
                }

                .app-operation-loader-content {
                    min-width: 0;
                    flex: 1;
                }

                .app-operation-loader-title {
                    color: #111827;
                    font-size: 0.9rem;
                    font-weight: 700;
                    line-height: 1.25rem;
                }

                .dark .app-operation-loader-title {
                    color: #f9fafb;
                }

                .app-operation-loader-subtitle {
                    margin-top: 0.125rem;
                    color: #64748b;
                    font-size: 0.78rem;
                    line-height: 1.1rem;
                }

                .dark .app-operation-loader-subtitle {
                    color: #cbd5e1;
                }

                .app-operation-loader-progress {
                    height: 0.45rem;
                    margin-top: 0.7rem;
                    overflow: hidden;
                    border-radius: 999px;
                    background: #e5e7eb;
                }

                .dark .app-operation-loader-progress {
                    background: #334155;
                }

                .app-operation-loader-progress-bar {
                    width: 0%;
                    height: 100%;
                    border-radius: inherit;
                    background: rgb(var(--primary-600));
                    transition: width 140ms ease;
                }

                @keyframes app-operation-loader-spin {
                    to {
                        transform: rotate(360deg);
                    }
                }
                
                .fi-fo-field .fi-fo-field-wrp-error-message {
                    font-size: 11px;
                }
            </style>

            <script>
                (() => {
                    if (window.__sisdikOperationLoaderInitialized) {
                        return;
                    }

                    window.__sisdikOperationLoaderInitialized = true;

                    const loader = document.getElementById('app-operation-loader');

                    if (!loader) {
                        return;
                    }

                    const message = loader.querySelector('[data-operation-loader-message]');
                    const subtitle = loader.querySelector('[data-operation-loader-subtitle]');
                    const progress = loader.querySelector('[data-operation-loader-progress]');
                    const progressBar = loader.querySelector('[data-operation-loader-progress-bar]');
                    let activeRequests = 0;
                    let activeUploads = 0;
                    let pendingMessage = '';
                    let loaderArmedUntil = 0;
                    let hideTimer = null;
                    let directNavigationTimer = null;

                    const setMessage = (text, detail = 'Mohon tunggu, sistem sedang bekerja.') => {
                        message.textContent = text || 'Memproses operasi...';
                        subtitle.textContent = detail;
                    };

                    const setProgress = (percent = null) => {
                        if (percent === null || Number.isNaN(Number(percent))) {
                            progress.hidden = true;
                            progressBar.style.width = '0%';
                            return;
                        }

                        progress.hidden = false;
                        progressBar.style.width = `${Math.max(0, Math.min(100, Number(percent)))}%`;
                    };

                    const show = (text, detail) => {
                        window.clearTimeout(hideTimer);
                        setMessage(text || pendingMessage || 'Memproses operasi...', detail);
                        loader.classList.add('is-visible');
                    };

                    const hideWhenIdle = () => {
                        if (activeRequests > 0 || activeUploads > 0) {
                            return;
                        }

                        hideTimer = window.setTimeout(() => {
                            loader.classList.remove('is-visible');
                            setProgress(null);
                            pendingMessage = '';
                            loaderArmedUntil = 0;
                            window.clearTimeout(directNavigationTimer);
                        }, 180);
                    };

                    const inferMessage = (value) => {
                        const text = String(value || '').toLowerCase();

                        if (text.includes('upload') || text.includes('unggah')) {
                            return 'Mengunggah berkas...';
                        }

                        if (text.includes('import') || text.includes('impor')) {
                            return 'Mengimpor data...';
                        }

                        if (
                            text.includes('export') ||
                            text.includes('ekspor') ||
                            text.includes('download') ||
                            text.includes('unduh') ||
                            text.includes('cetak') ||
                            text.includes('pdf') ||
                            text.includes('excel')
                        ) {
                            return 'Menyiapkan berkas unduhan...';
                        }

                        if (
                            text.includes('save') ||
                            text.includes('simpan') ||
                            text.includes('perbarui') ||
                            text.includes('update')
                        ) {
                            return 'Menyimpan perubahan...';
                        }

                        return null;
                    };

                    const getTriggerText = (trigger) => [
                        trigger.textContent,
                        trigger.getAttribute('aria-label'),
                        trigger.getAttribute('title'),
                        trigger.getAttribute('href'),
                    ].filter(Boolean).join(' ');

                    const isPaginationTrigger = (trigger) => {
                        const triggerText = getTriggerText(trigger).toLowerCase();
                        const labelledAncestor = trigger.closest('[aria-label]');
                        const ancestorLabel = labelledAncestor?.getAttribute('aria-label')?.toLowerCase() || '';
                        const classText = [
                            trigger.className,
                            trigger.closest('nav')?.className,
                            trigger.closest('[class]')?.className,
                        ].filter(Boolean).join(' ').toLowerCase();

                        return (
                            ancestorLabel.includes('pagination') ||
                            ancestorLabel.includes('halaman') ||
                            classText.includes('pagination') ||
                            triggerText.includes('sebelumnya') ||
                            triggerText.includes('selanjutnya') ||
                            triggerText.includes('previous') ||
                            triggerText.includes('next') ||
                            triggerText.includes('page ') ||
                            triggerText.includes('halaman') ||
                            triggerText.trim() === '«' ||
                            triggerText.trim() === '»'
                        );
                    };

                    const isInternalNavigationLink = (trigger, event) => {
                        if (trigger.tagName !== 'A' || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                            return false;
                        }

                        if (trigger.target === '_blank' || trigger.hasAttribute('download')) {
                            return false;
                        }

                        const href = trigger.getAttribute('href');
                        if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) {
                            return false;
                        }

                        const url = new URL(trigger.href, window.location.href);
                        const currentUrl = new URL(window.location.href);

                        if (url.origin !== currentUrl.origin || url.href === currentUrl.href) {
                            return false;
                        }

                        if (/\.(pdf|xlsx?|csv|docx?|zip)(\?|$)/i.test(url.pathname)) {
                            return false;
                        }

                        return (
                            trigger.closest('.fi-sidebar') ||
                            trigger.closest('.fi-topbar') ||
                            trigger.closest('.fi-breadcrumbs') ||
                            url.pathname.startsWith('/admin')
                        );
                    };

                    document.addEventListener('click', (event) => {
                        const trigger = event.target.closest('button, a, [role="button"]');

                        if (!trigger) {
                            return;
                        }

                        pendingMessage = inferMessage(getTriggerText(trigger));

                        if (!pendingMessage && isPaginationTrigger(trigger)) {
                            pendingMessage = 'Memuat halaman...';
                        }

                        const isInternalNavigation = !pendingMessage && isInternalNavigationLink(trigger, event);

                        if (isInternalNavigation) {
                            pendingMessage = 'Membuka halaman...';
                        }

                        if (pendingMessage) {
                            loaderArmedUntil = Date.now() + 3000;
                        }

                        if (isInternalNavigation) {
                            window.clearTimeout(directNavigationTimer);
                            directNavigationTimer = window.setTimeout(() => show(pendingMessage), 250);
                        }
                    }, true);

                    document.addEventListener('livewire:init', () => {
                        if (!window.Livewire?.hook) {
                            return;
                        }

                        window.Livewire.hook('request', ({ payload, options, respond, succeed, fail }) => {
                            const requestText = payload || options?.body || pendingMessage;
                            const inferredMessage = inferMessage(requestText);
                            const shouldShowLoader = pendingMessage && Date.now() <= loaderArmedUntil;
                            const requestMessage = pendingMessage || inferredMessage || 'Memproses operasi...';
                            let requestShowTimer = null;

                            if (shouldShowLoader) {
                                activeRequests++;
                                requestShowTimer = window.setTimeout(() => show(requestMessage), 250);
                            }

                            let finished = false;
                            const finish = () => {
                                if (finished) {
                                    return;
                                }

                                finished = true;
                                window.clearTimeout(requestShowTimer);
                                if (shouldShowLoader) {
                                    activeRequests = Math.max(0, activeRequests - 1);
                                }
                                hideWhenIdle();
                            };

                            respond(finish);
                            succeed(finish);
                            fail(finish);
                        });
                    });

                    window.addEventListener('livewire-upload-start', () => {
                        activeUploads++;
                        setProgress(0);
                        show('Mengunggah berkas...', 'Progress upload sedang dihitung.');
                    });

                    window.addEventListener('livewire-upload-progress', (event) => {
                        const percent = event.detail?.progress ?? 0;
                        setProgress(percent);
                        show('Mengunggah berkas...', `Upload berjalan ${percent}%.`);
                    });

                    const finishUpload = () => {
                        activeUploads = Math.max(0, activeUploads - 1);
                        hideWhenIdle();
                    };

                    window.addEventListener('livewire-upload-finish', finishUpload);
                    window.addEventListener('livewire-upload-error', finishUpload);
                    window.addEventListener('livewire-upload-cancel', finishUpload);
                })();
            </script>
        HTML;
    }
}
