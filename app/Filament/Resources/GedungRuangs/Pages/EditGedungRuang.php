<?php

namespace App\Filament\Resources\GedungRuangs\Pages;

use App\Filament\Resources\GedungRuangs\GedungRuangResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditGedungRuang extends EditRecord
{
    protected static string $resource = GedungRuangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
