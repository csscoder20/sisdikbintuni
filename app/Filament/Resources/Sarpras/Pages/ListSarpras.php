<?php

namespace App\Filament\Resources\Sarpras\Pages;

use App\Filament\Resources\Sarpras\SarprasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSarpras extends ListRecords
{
    protected static string $resource = SarprasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
