<?php

namespace App\Filament\Resources\Sarpras\Pages;

use App\Filament\Resources\Sarpras\SarprasResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSarpras extends EditRecord
{
    protected static string $resource = SarprasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
