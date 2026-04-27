<?php

namespace App\Filament\Resources\GtkJamAjars\Pages;

use App\Filament\Resources\GtkJamAjars\GtkJamAjarResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;


class EditGtkJamAjar extends EditRecord
{
    protected static string $resource = GtkJamAjarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            ForceDeleteAction::make(),
        ];
    }
}
