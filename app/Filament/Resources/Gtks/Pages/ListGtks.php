<?php

namespace App\Filament\Resources\Gtks\Pages;

use App\Filament\Resources\Gtks\GtkResource;
use App\Filament\Actions\ValidateChecklistAction;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\GtkImporter;
use App\Filament\Traits\HasImportTemplate;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\HtmlString;

class ListGtks extends ListRecords
{
    use HasImportTemplate;

    protected static string $resource = GtkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \App\Filament\Actions\ExcelImportAction::make()
                ->importer(GtkImporter::class)
                ->label('Impor Data GTK')
                ->modalHeading('Impor Data Guru & Tenaga Kependidikan')
                ->modalDescription(fn () => new \Illuminate\Support\HtmlString(
                    \Illuminate\Support\Facades\Blade::render(
                        <<<'BLADE'
                        <div class="text-sm space-y-2">
                            <div>
                                <x-filament::link href="{{ route('import-template.download', ['importer' => 'gtk']) }}" tag="a" color="success" class="font-bold hover:underline">
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
                ->label('Tambah GTK')
                ->modalHeading('Tambah Data GTK')
                ->modalSubmitActionLabel('Simpan Data GTK')
                ->createAnother(false),
            ValidateChecklistAction::make('validateNominatif', 'nominatif_gtk', fn() => \App\Models\Gtk::where('sekolah_id', filament()->getTenant()?->id)->exists()),
        ];
    }
}
