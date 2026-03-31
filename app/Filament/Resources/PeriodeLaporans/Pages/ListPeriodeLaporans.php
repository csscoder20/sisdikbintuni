<?php

namespace App\Filament\Resources\PeriodeLaporans\Pages;

use App\Filament\Resources\PeriodeLaporans\PeriodeLaporanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPeriodeLaporans extends ListRecords
{
    protected static string $resource = PeriodeLaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
