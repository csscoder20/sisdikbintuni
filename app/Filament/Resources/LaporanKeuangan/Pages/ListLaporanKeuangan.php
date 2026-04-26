<?php

namespace App\Filament\Resources\LaporanKeuangan\Pages;

use App\Filament\Resources\LaporanKeuangan\LaporanKeuanganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLaporanKeuangan extends ListRecords
{
    protected static string $resource = LaporanKeuanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Laporan Keuangan')
                ->modalHeading('Tambah Laporan Keuangan')
                ->modalSubmitActionLabel('Simpan Laporan Keuangan')
                ->createAnother(false),
        ];
    }
}
