<?php

namespace App\Filament\Resources\LaporanGedung\Pages;

use App\Filament\Resources\LaporanGedung\LaporanGedungResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Actions\Action;
use App\Filament\Imports\LaporanGedungImporter;
use App\Filament\Traits\HasImportTemplate;
use Filament\Resources\Pages\ListRecords;

class ListLaporanGedung extends ListRecords
{
    use HasImportTemplate;

    protected static string $resource = LaporanGedungResource::class;

    public function getLaporanStatus(string $type): bool
    {
        $sekolahId = filament()->getTenant()?->id;
        $laporan = \App\Models\Laporan::where([
            'sekolah_id' => $sekolahId,
            'bulan' => (int) date('m'),
            'tahun' => (int) date('Y'),
        ])->first();

        $column = "is_{$type}_valid";
        return $laporan ? (bool) $laporan->$column : false;
    }

    protected function getHeaderActions(): array
    {
        return [
            \App\Filament\Actions\ExcelImportAction::make()
                ->importer(LaporanGedungImporter::class)
                ->options(function () {
                    return [
                        'sekolah_id' => filament()->getTenant()?->id ?? session('dinas_selected_sekolah_id'),
                    ];
                })
                ->label('Impor Data Sarpras')
                ->modalHeading('Impor Data Sarana & Prasarana')
                ->modalDescription(fn () => new \Illuminate\Support\HtmlString(
                    \Illuminate\Support\Facades\Blade::render(
                        <<<'BLADE'
                        <div class="text-sm space-y-2">
                            <div>
                                <x-filament::link href="{{ route('import-template.download', ['importer' => 'laporan-gedung']) }}" tag="a" color="success" class="font-bold hover:underline">
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
                ->label('Tambah Sarpras')
                ->modalHeading('Tambah Sarpras')
                ->modalSubmitActionLabel('Simpan Data')
                ->createAnother(false),
        ];
    }
}
