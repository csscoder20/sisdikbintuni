<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans\Pages;

use App\Filament\Resources\GtkRiwayatPendidikans\GtkRiwayatPendidikanResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\GtkPendidikanImporter;
use Filament\Resources\Pages\ListRecords;

class ListGtkRiwayatPendidikans extends ListRecords
{
    protected static string $resource = GtkRiwayatPendidikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(GtkPendidikanImporter::class)
                ->label('Import Data')
                ->modalHeading('Import Data Riwayat Pendidikan')
                ->modalSubmitActionLabel('Import Sekarang')
                ->color('info'),
            CreateAction::make()
                ->label('Tambah Data')
                ->modalHeading('Tambah Data Riwayat Pendidikan')
                ->modalSubmitActionLabel('Tambah Data')
                ->createAnother(false),
        ];
    }
}
