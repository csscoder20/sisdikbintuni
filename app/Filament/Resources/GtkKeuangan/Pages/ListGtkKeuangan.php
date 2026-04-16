<?php
namespace App\Filament\Resources\GtkKeuangan\Pages;
use App\Filament\Resources\GtkKeuangan\GtkKeuanganResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\GtkKeuanganImporter;
use Filament\Resources\Pages\ListRecords;
class ListGtkKeuangan extends ListRecords {
    protected static string $resource = GtkKeuanganResource::class;
    protected function getHeaderActions(): array { 
        return [
            ImportAction::make()
                ->importer(GtkKeuanganImporter::class)
                ->label('Import Data')
                ->modalHeading('Import Data Rekening & NPWP')
                ->modalSubmitActionLabel('Import Sekarang')
                ->color('info'),
            CreateAction::make()
                ->label('Tambah Data')
                ->modalHeading('Tambah Rekening & NPWP')
                ->modalSubmitActionLabel('Tambah Data')
                ->createAnother(false)
        ]; 
    }
}
