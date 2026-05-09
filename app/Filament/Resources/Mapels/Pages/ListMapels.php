<?php

namespace App\Filament\Resources\Mapels\Pages;

use App\Filament\Resources\Mapels\MapelResource;
use Filament\Actions\CreateAction;
use App\Filament\Actions\ExcelImportAction;
use App\Filament\Imports\MapelImporter;
use App\Filament\Traits\HasImportTemplate;
use Filament\Resources\Pages\ListRecords;

class ListMapels extends ListRecords
{
    use HasImportTemplate;

    protected static string $resource = MapelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()
                ->importer(MapelImporter::class)
                ->label('Impor Mapel')
                ->modalHeading('Impor Data Mata Pelajaran')
                ->modalDescription(fn () => new \Illuminate\Support\HtmlString(
                    \Illuminate\Support\Facades\Blade::render(
                        <<<'BLADE'
                        <div class="text-sm space-y-2">
                            <div>
                                <x-filament::link href="{{ route('import-template.download', ['importer' => 'mapel']) }}" tag="a" color="success" class="font-bold hover:underline">
                                    Unduh contoh berkas (.xlsx)
                                </x-filament::link>
                            </div>
                            <div class="text-gray-600 dark:text-gray-400">
                                Silakan isi data di Excel, lalu unggah berkas tersebut langsung ke sini.
                            </div>
                        </div>
                        BLADE
                    )
                ))
                ->modalSubmitActionLabel('Impor Sekarang')
                ->color('info'),
            CreateAction::make()
                ->label('Tambah Mapel')
                ->modalHeading('Tambah Mata Pelajaran')
                ->modalSubmitActionLabel('Simpan Data')
                ->createAnother(false),
        ];
    }
}
