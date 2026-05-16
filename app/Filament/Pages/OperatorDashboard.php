<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Traits\HasLaporanBulananLogic;

class OperatorDashboard extends BaseDashboard
{
    use HasLaporanBulananLogic;

    protected static ?string $navigationLabel = 'Dasbor';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.laporan-bulanan';

    public static function canAccess(): bool
    {
        return auth()->check() && (auth()->user()->hasRole(['operator', 'super_admin', 'admin_dinas']));
    }

    public function mount()
    {
        $this->initializeLaporanBulanan();
    }
}
