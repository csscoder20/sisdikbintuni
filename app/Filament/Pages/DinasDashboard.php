<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Panel;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Traits\HasLaporanBulananLogic;
use App\Models\ActivityLog;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Filament\Actions\Action;

class DinasDashboard extends BaseDashboard
{
    use HasLaporanBulananLogic;

    protected static ?string $navigationLabel = 'Dasbor';

    public ?int $previewLaporanId = null;

    public function openLaporanPreview(int $laporanId): void
    {
        $this->previewLaporanId = $laporanId;
        $this->mountAction('previewLaporan');
    }

    public function getActions(): array
    {
        return [
            Action::make('previewLaporan')
                ->label('Pratinjau Laporan')
                ->modalHeading(function () {
                    if (!$this->previewLaporanId) return 'Pratinjau Laporan';
                    $laporan = Laporan::find($this->previewLaporanId);
                    if (!$laporan) return 'Pratinjau Laporan';
                    $periode = \Carbon\Carbon::createFromDate($laporan->tahun, $laporan->bulan, 1)->translatedFormat('F Y');
                    return 'Pratinjau Laporan — ' . $periode;
                })
                ->modalWidth('5xl')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->extraModalFooterActions([
                    Action::make('download_pdf_laporan')
                        ->label('Download PDF')
                        ->color('info')
                        ->icon('heroicon-m-document-arrow-down')
                        ->url(function () {
                            if (!$this->previewLaporanId) return '#';
                            $laporan = Laporan::find($this->previewLaporanId);
                            if (!$laporan) return '#';
                            return route('cetak-laporan.pdf', $laporan->sekolah) . '?laporan_id=' . $laporan->id;
                        })
                        ->openUrlInNewTab(),
                ])
                ->modalContent(function () {
                    if (!$this->previewLaporanId) return view('livewire.report-preview-container', ['schoolId' => null, 'laporanId' => null]);
                    $laporan = Laporan::find($this->previewLaporanId);
                    return view('livewire.report-preview-container', [
                        'schoolId'  => $laporan?->sekolah_id,
                        'laporanId' => $this->previewLaporanId,
                    ]);
                }),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin_dinas');
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-home';
    }


    public function mount()
    {
        $selectedId = session('dinas_selected_sekolah_id');
        if ($selectedId) {
            $this->schoolId = $selectedId;
            $this->initializeLaporanBulanan();
        }
    }

    public function getRiwayatLaporanDashboard()
    {
        return collect($this->getValidatedLaporanList())->take(5);
    }

    public int $laporanCurrentPage = 1;
    public int $laporanPerPage = 5;

    public function getRiwayatLaporanTotal()
    {
        return collect($this->getValidatedLaporanList())->count();
    }

    public function getRiwayatLaporanLastPage()
    {
        $total = $this->getRiwayatLaporanTotal();
        return (int) ceil($total / $this->laporanPerPage);
    }

    public function goToLaporanPage(int $page)
    {
        $lastPage = $this->getRiwayatLaporanLastPage();

        if ($page < 1) {
            $this->laporanCurrentPage = 1;
        } elseif ($page > $lastPage) {
            $this->laporanCurrentPage = $lastPage;
        } else {
            $this->laporanCurrentPage = $page;
        }
    }

    public function nextLaporanPage()
    {
        $this->goToLaporanPage($this->laporanCurrentPage + 1);
    }

    public function prevLaporanPage()
    {
        $this->goToLaporanPage($this->laporanCurrentPage - 1);
    }

    public int $activityLogCurrentPage = 1;
    public int $activityLogPerPage = 5;

    public function getOperatorActivityLogs()
    {
        $allLogs = ActivityLog::query()
            ->where('user_id', Auth::id())
            ->latest('created_at')
            ->get();

        $skip = ($this->activityLogCurrentPage - 1) * $this->activityLogPerPage;

        return $allLogs->slice($skip, $this->activityLogPerPage);
    }

    public function getActivityLogTotal()
    {
        return ActivityLog::query()
            ->where('user_id', Auth::id())
            ->count();
    }

    public function getActivityLogLastPage()
    {
        $total = $this->getActivityLogTotal();
        return (int) ceil($total / $this->activityLogPerPage);
    }

    public function goToActivityLogPage(int $page)
    {
        $lastPage = $this->getActivityLogLastPage();

        if ($page < 1) {
            $this->activityLogCurrentPage = 1;
        } elseif ($page > $lastPage) {
            $this->activityLogCurrentPage = $lastPage;
        } else {
            $this->activityLogCurrentPage = $page;
        }
    }

    public function nextActivityLogPage()
    {
        $this->goToActivityLogPage($this->activityLogCurrentPage + 1);
    }

    public function prevActivityLogPage()
    {
        $this->goToActivityLogPage($this->activityLogCurrentPage - 1);
    }

    public function getActivityLogAccessLocation(ActivityLog $log): string
    {
        $location = $log->properties['access_location'] ?? $log->properties['location'] ?? null;

        if (is_array($location)) {
            if (filled($location['place_name'] ?? null)) {
                return $location['place_name'];
            }

            $parts = array_filter([
                $location['city'] ?? null,
                $location['region'] ?? null,
                $location['country'] ?? $location['country_name'] ?? null,
            ]);

            if (! empty($parts)) {
                return implode(', ', $parts);
            }
        }

        if (is_string($location) && filled($location)) {
            return $location;
        }

        if (! filled($log->ip_address)) {
            return '-';
        }

        return $this->isPrivateIpAddress($log->ip_address)
            ? 'Jaringan lokal'
            : $this->resolveIpAddressLocation($log->ip_address);
    }

    protected function resolveIpAddressLocation(string $ipAddress): string
    {
        return Cache::remember("activity-log-location:{$ipAddress}", now()->addDays(7), function () use ($ipAddress) {
            try {
                $response = Http::timeout(2)
                    ->acceptJson()
                    ->get("https://ipapi.co/{$ipAddress}/json/");

                if (! $response->successful()) {
                    return 'Lokasi tidak ditemukan';
                }

                $data = $response->json();
                $parts = array_filter([
                    $data['city'] ?? null,
                    $data['region'] ?? null,
                    $data['country_name'] ?? null,
                ]);

                return ! empty($parts)
                    ? implode(', ', $parts)
                    : 'Lokasi tidak ditemukan';
            } catch (\Throwable) {
                return 'Lokasi tidak ditemukan';
            }
        });
    }

    protected function isPrivateIpAddress(string $ipAddress): bool
    {
        return filter_var(
            $ipAddress,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }

    public function getView(): string
    {
        $selectedId = session('dinas_selected_sekolah_id');
        if ($selectedId) {
            return 'filament.pages.laporan-bulanan';
        }
        return parent::getView();
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

    public function getColumns(): int | array
    {
        return 4;
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
            \App\Filament\Widgets\DinasSarprasChart::class,
            \App\Filament\Widgets\DinasSiswaChart::class,
            \App\Filament\Widgets\DinasSiswaDaerahChart::class,
            \App\Filament\Widgets\DinasSiswaAgamaChart::class,
            \App\Filament\Widgets\DinasGtkStatusChart::class,
            \App\Filament\Widgets\DinasGtkPendidikanChart::class,
            // \App\Filament\Widgets\GtkStatusKepegawaianChart::class,
            // \App\Filament\Widgets\GuruPendidikanChart::class,
            // \App\Filament\Widgets\LaporanTerbaruWidget::class,
            // \App\Filament\Widgets\OperatorActivityLogWidget::class,
        ];
    }
}
