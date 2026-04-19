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
            ImportAction::make()
                ->importer(GtkPendidikanImporter::class)
                ->label('Impor Riwayat Pendidikan')
                ->modalHeading('Impor Data Riwayat Pendidikan')
                ->modalDescription(fn (ImportAction $action) => new \Illuminate\Support\HtmlString(
                    \Illuminate\Support\Facades\Blade::render(
                        <<<'BLADE'
                        <div class="text-sm">
                            <x-filament::link wire:click="mountAction('downloadExample')" tag="button" type="button" color="success" class="font-bold hover:underline">
                                Unduh contoh berkas (.csv)
                            </x-filament::link>
                        </div>
                        BLADE
                    )
                ))
                ->modalSubmitActionLabel('Impor Sekarang')
                ->color('info'),
            CreateAction::make()
                ->label('Tambah Riwayat Pendidikan')
                ->modalHeading('Tambah Data Riwayat Pendidikan')
                ->modalSubmitActionLabel('Simpan Riwayat Pendidikan')
                ->createAnother(false),
        ];
    }
}
