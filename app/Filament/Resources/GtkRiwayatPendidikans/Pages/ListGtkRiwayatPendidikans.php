<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans\Pages;

use App\Filament\Resources\GtkRiwayatPendidikans\GtkRiwayatPendidikanResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\GtkPendidikanImporter;
use App\Filament\Traits\HasImportTemplate;
use Filament\Resources\Pages\ListRecords;

class ListGtkRiwayatPendidikans extends ListRecords
{
    use HasImportTemplate;

    protected static string $resource = GtkRiwayatPendidikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
