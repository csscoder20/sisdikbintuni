<?php

namespace App\Filament\Resources\PeriodeLaporans\Pages;

use App\Filament\Resources\PeriodeLaporans\PeriodeLaporanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPeriodeLaporan extends ViewRecord
{
    protected static string $resource = PeriodeLaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
