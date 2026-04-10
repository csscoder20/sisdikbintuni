<?php
namespace App\Filament\Resources\KehadiranGtk\Pages;
use App\Filament\Resources\KehadiranGtk\KehadiranGtkResource;
use Filament\Resources\Pages\CreateRecord;
class CreateKehadiranGtk extends CreateRecord {
    protected static bool $canCreateAnother = false;

    protected static string $resource = KehadiranGtkResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
