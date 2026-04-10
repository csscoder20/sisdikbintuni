<?php
namespace App\Filament\Resources\LaporanGtk\Pages;
use App\Filament\Resources\LaporanGtk\LaporanGtkResource;
use Filament\Resources\Pages\EditRecord;
class EditLaporanGtk extends EditRecord {
    protected static string $resource = LaporanGtkResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array { return []; }
}
