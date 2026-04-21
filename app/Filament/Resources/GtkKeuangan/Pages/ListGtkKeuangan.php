<?php
namespace App\Filament\Resources\GtkKeuangan\Pages;
use App\Filament\Resources\GtkKeuangan\GtkKeuanganResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\GtkKeuanganImporter;
use App\Filament\Traits\HasImportTemplate;
use Filament\Resources\Pages\ListRecords;
class ListGtkKeuangan extends ListRecords {
    use HasImportTemplate;

    protected static string $resource = GtkKeuanganResource::class;
    protected function getHeaderActions(): array { 
        return [
            //
        ]; 
    }
}
