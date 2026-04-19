<?php

namespace App\Filament\Resources\LaporanGedung\Pages;

use App\Filament\Resources\LaporanGedung\LaporanGedungResource;
use App\Filament\Actions\ValidateChecklistAction;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
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
            ImportAction::make()
                ->importer(LaporanGedungImporter::class)
                ->label('Impor Data Keadaan Gedung')
                ->modalHeading('Impor Data Keadaan Gedung')
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
                ->label('Tambah Laporan Gedung')
                ->modalHeading('Tambah Laporan Gedung')
                ->modalSubmitActionLabel('Simpan Laporan Gedung')
                ->createAnother(false),
            ValidateChecklistAction::make('validateKondisiSarpras', 'kondisi_sarpras', fn() => \App\Models\LaporanGedung::whereHas('laporan', fn($q) => $q->where('sekolah_id', filament()->getTenant()?->id))->exists()),
        ];
    }
}
