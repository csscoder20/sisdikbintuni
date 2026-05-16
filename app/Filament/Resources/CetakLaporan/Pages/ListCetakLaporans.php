<?php

namespace App\Filament\Resources\CetakLaporan\Pages;

use App\Filament\Resources\CetakLaporan\CetakLaporanResource;
use Filament\Resources\Pages\ListRecords;

class ListCetakLaporans extends ListRecords
{
    protected static string $resource = CetakLaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
