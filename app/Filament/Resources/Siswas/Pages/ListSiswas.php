<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use App\Filament\Actions\ValidateChecklistAction;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\SiswaImporter;
use App\Filament\Traits\HasImportTemplate;
use Filament\Resources\Pages\ListRecords;

class ListSiswas extends ListRecords
{
    use HasImportTemplate;

    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(SiswaImporter::class)
                ->label('Impor Data Siswa')
                ->modalHeading('Impor Data Siswa')
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
                ->label('Tambah Siswa')
                ->modalHeading('Tambah Data Siswa')
                ->modalSubmitActionLabel('Simpan Data Siswa')
                ->modalFooterActions([])
                ->createAnother(false),
        ];
    }
}
