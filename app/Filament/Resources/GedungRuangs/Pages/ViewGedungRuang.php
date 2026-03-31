<?php

namespace App\Filament\Resources\GedungRuangs\Pages;

use App\Filament\Resources\GedungRuangs\GedungRuangResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGedungRuang extends ViewRecord
{
    protected static string $resource = GedungRuangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
