<?php

namespace App\Filament\Pages;

use App\Models\ActivityLog;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Traits\HasLaporanBulananLogic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OperatorDashboard extends BaseDashboard
{
    use HasLaporanBulananLogic;

    protected static ?string $navigationLabel = 'Dasbor';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.laporan-bulanan';

    public int $laporanCurrentPage = 1;
    public int $laporanPerPage = 5;

    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->hasRole(['operator', 'super_admin', 'admin_dinas']);
    }

    public function mount()
    {
        $this->initializeLaporanBulanan();
    }

    public function getRiwayatLaporanDashboard()
    {
        $allLaporan = collect($this->getValidatedLaporanList());
        $skip = ($this->laporanCurrentPage - 1) * $this->laporanPerPage;

        return $allLaporan->slice($skip, $this->laporanPerPage);
    }

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

    public function getOperatorActivityLogs()
    {
        return ActivityLog::query()
            ->where('user_id', Auth::id())
            ->latest('created_at')
            ->limit(5)
            ->get();
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
}
