<?php
namespace App\Filament\Resources\KehadiranGtk\Pages;
use App\Filament\Resources\KehadiranGtk\KehadiranGtkResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;

class EditKehadiranGtk extends EditRecord {
    protected static string $resource = KehadiranGtkResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array { return [
            RestoreAction::make(),
            ForceDeleteAction::make(),]; }
}
