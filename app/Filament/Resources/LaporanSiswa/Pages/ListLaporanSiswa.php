<?php
namespace App\Filament\Resources\LaporanSiswa\Pages;
use App\Filament\Resources\LaporanSiswa\LaporanSiswaResource;
use App\Filament\Actions\ValidateChecklistAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListLaporanSiswa extends ListRecords {
    protected static string $resource = LaporanSiswaResource::class;
    protected function getHeaderActions(): array {
        return [
            CreateAction::make()
                ->label('Tambah Laporan Siswa')
                ->modalHeading('Tambah Laporan Siswa')
                ->modalSubmitActionLabel('Simpan Laporan Siswa')
                ->createAnother(false),
        ];
    }
}
