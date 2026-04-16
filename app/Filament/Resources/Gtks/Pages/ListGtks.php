<?php

namespace App\Filament\Resources\Gtks\Pages;

use App\Filament\Resources\Gtks\GtkResource;
use App\Filament\Actions\ValidateChecklistAction;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\GtkImporter;
use Filament\Resources\Pages\ListRecords;

class ListGtks extends ListRecords
{
    protected static string $resource = GtkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(GtkImporter::class)
                ->label('Import Data')
                ->modalHeading('Import Data GTK')
                ->modalSubmitActionLabel('Import Sekarang')
                ->color('info'),
            CreateAction::make()
                ->label('Tambah Data')
                ->modalHeading('Tambah Data GTK')
                ->modalSubmitActionLabel('Tambah Data')
                ->createAnother(false),
        ];
    }
}
