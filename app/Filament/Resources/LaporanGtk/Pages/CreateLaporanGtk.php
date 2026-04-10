<?php
namespace App\Filament\Resources\LaporanGtk\Pages;
use App\Filament\Resources\LaporanGtk\LaporanGtkResource;
use Filament\Resources\Pages\CreateRecord;
class CreateLaporanGtk extends CreateRecord {
    protected static bool $canCreateAnother = false;

    protected static string $resource = LaporanGtkResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
