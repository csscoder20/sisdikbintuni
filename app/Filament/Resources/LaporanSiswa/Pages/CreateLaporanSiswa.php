<?php
namespace App\Filament\Resources\LaporanSiswa\Pages;
use App\Filament\Resources\LaporanSiswa\LaporanSiswaResource;
use Filament\Resources\Pages\CreateRecord;
class CreateLaporanSiswa extends CreateRecord {
    protected static bool $canCreateAnother = false;

    protected static string $resource = LaporanSiswaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
