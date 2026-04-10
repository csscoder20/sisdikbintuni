<?php
namespace App\Filament\Resources\Laporan\Pages;
use App\Filament\Resources\Laporan\LaporanResource;
use Filament\Resources\Pages\CreateRecord;
class CreateLaporan extends CreateRecord {
    protected static bool $canCreateAnother = false;

    protected static string $resource = LaporanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
