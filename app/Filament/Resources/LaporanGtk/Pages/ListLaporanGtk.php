<?php
namespace App\Filament\Resources\LaporanGtk\Pages;
use App\Filament\Resources\LaporanGtk\LaporanGtkResource;
use App\Filament\Actions\ValidateChecklistAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListLaporanGtk extends ListRecords {
    protected static string $resource = LaporanGtkResource::class;
    protected function getHeaderActions(): array {
        return [
            CreateAction::make()
                ->label('Tambah Laporan GTK')
                ->modalHeading('Tambah Laporan GTK')
                ->modalSubmitActionLabel('Simpan Laporan GTK')
                ->createAnother(false),
        ];
    }
}
