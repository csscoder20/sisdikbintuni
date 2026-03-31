<?php

namespace App\Filament\Resources\GedungRuangs\Pages;

use App\Filament\Resources\GedungRuangs\GedungRuangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGedungRuangs extends ListRecords
{
    protected static string $resource = GedungRuangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
