<?php
namespace App\Filament\Resources\LaporanSiswa\Pages;
use App\Filament\Resources\LaporanSiswa\LaporanSiswaResource;
use Filament\Resources\Pages\EditRecord;
class EditLaporanSiswa extends EditRecord {
    protected static string $resource = LaporanSiswaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array { return []; }
}
