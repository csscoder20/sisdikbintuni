<?php

namespace App\Filament\Resources\Rombels\Pages;

use App\Filament\Resources\Rombels\RombelResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\RombelImporter;
use App\Filament\Traits\HasImportTemplate;
use Filament\Resources\Pages\ListRecords;

class ListRombels extends ListRecords
{
    use HasImportTemplate;

    protected static string $resource = RombelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(RombelImporter::class)
                ->label('Impor Data Rombel')
                ->modalHeading('Impor Data Rombel')
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
                ->label('Tambah Rombel')
                ->modalHeading('Tambah Data Rombel')
                ->modalSubmitActionLabel('Simpan Rombel')
                ->createAnother(false),
        ];
    }
}
