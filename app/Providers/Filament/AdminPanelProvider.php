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
            ->simpleProfilePage(true)
            ->profile()
            ->maxContentWidth(Width::Full)
            ->sidebarCollapsibleOnDesktop()
            ->profile()
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
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
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
            ]);
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
            ->simpleProfilePage(true)
            ->profile()
            ->maxContentWidth(Width::Full)
            ->sidebarCollapsibleOnDesktop()
            ->profile()
            ->breadcrumbs(false)
            ->globalSearch(false)
            ->sidebarWidth('16rem')
            ->brandName('OPERATOR ' . strtoupper($jenjang))
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
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                OperatorDashboard::class,
                SekolahPage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
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
                        
                       .fi-main {
                            min-width: 100rem !important;
                        }

                        .max-w-7xl {
                            max-width: 100%;
                        }
                    </style>
                HTML;
            });
    }
}
