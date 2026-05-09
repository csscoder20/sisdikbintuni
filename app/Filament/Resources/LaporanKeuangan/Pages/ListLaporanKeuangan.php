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
                ->label('Tambah Transaksi')
                ->icon(\Filament\Support\Icons\Heroicon::OutlinedPlus)
                ->modalHeading('Tambah Transaksi Baru')
                ->modalSubmitActionLabel('Simpan Transaksi')
                ->createAnother(false),
        ];
    }
}
