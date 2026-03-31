<?php

namespace App\Filament\Resources\PeriodeLaporans\Pages;

use App\Filament\Resources\PeriodeLaporans\PeriodeLaporanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPeriodeLaporan extends EditRecord
{
    protected static string $resource = PeriodeLaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
