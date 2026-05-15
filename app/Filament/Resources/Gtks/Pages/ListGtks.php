<?php

namespace App\Filament\Resources\Gtks\Pages;

use App\Filament\Resources\Gtks\GtkResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Actions\Action;
use App\Filament\Imports\GtkImporter;
use App\Filament\Traits\HasImportTemplate;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\CheckboxList;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Support\Icons\Heroicon;

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
                ->modalFooterActions([])
                ->createAnother(false),
            Action::make('exportPdf')
                ->label('Export PDF')
                ->color('success')
                ->modalHeading('Export Nominatif GTK ke PDF')
                ->modalDescription('Pilih kolom yang ingin Anda sertakan dalam file PDF. Data yang sesuai dengan filter saat ini akan diexport.')
                ->modalSubmitActionLabel('Export Sekarang')
                ->form([
                    CheckboxList::make('columns')
                        ->label('Pilih Kolom')
                        ->options([
                            'nama' => 'Nama GTK',
                            'nik' => 'NIK',
                            'nip' => 'NIP',
                            'nuptk' => 'NUPTK',
                            'nokarpeg' => 'No Karpeg',
                            'jenis_gtk' => 'Jenis GTK',
                            'jenis_kelamin' => 'Jenis Kelamin',
                            'status_kepegawaian' => 'Status Kepegawaian',
                            'pangkat_gol_terakhir' => 'Pangkat Gol Terakhir',
                            'tmt_pns' => 'TMT PNS',
                            'tempat_lahir' => 'Tempat Lahir',
                            'tanggal_lahir' => 'Tanggal Lahir',
                            'pendidikan_terakhir' => 'Pendidikan Terakhir',
                            'daerah_asal' => 'Daerah Asal',
                            'agama' => 'Agama',
                            'alamat' => 'Alamat',
                            'desa' => 'Desa',
                            'kecamatan' => 'Kecamatan',
                            'kabupaten' => 'Kabupaten',
                            'provinsi' => 'Provinsi',
                            'tmt_pangkat_gol_terakhir' => 'TMT Pangkat Gol',
                        ])
                        ->columns(3)
                        ->default(['nama', 'nip', 'nuptk'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $records = $this->getFilteredTableQuery()->get();

                    $allColumns = [
                        'nama' => 'Nama GTK',
                        'nik' => 'NIK',
                        'nip' => 'NIP',
                        'nuptk' => 'NUPTK',
                        'nokarpeg' => 'No Karpeg',
                        'jenis_gtk' => 'Jenis GTK',
                        'jenis_kelamin' => 'Jenis Kelamin',
                        'status_kepegawaian' => 'Status Kepegawaian',
                        'pangkat_gol_terakhir' => 'Pangkat Gol Terakhir',
                        'tmt_pns' => 'TMT PNS',
                        'tempat_lahir' => 'Tempat Lahir',
                        'tanggal_lahir' => 'Tanggal Lahir',
                        'pendidikan_terakhir' => 'Pendidikan Terakhir',
                        'daerah_asal' => 'Daerah Asal',
                        'agama' => 'Agama',
                        'alamat' => 'Alamat',
                        'desa' => 'Desa',
                        'kecamatan' => 'Kecamatan',
                        'kabupaten' => 'Kabupaten',
                        'provinsi' => 'Provinsi',
                        'tmt_pangkat_gol_terakhir' => 'TMT Pangkat Gol',
                    ];

                    $selectedColumns = array_intersect_key($allColumns, array_flip($data['columns']));

                    $pdf = Pdf::loadView('pdf.gtk-nominatif', [
                        'records' => $records,
                        'columns' => $selectedColumns,
                        'sekolah' => filament()->getTenant(),
                    ])->setPaper('a4', count($data['columns']) > 5 ? 'landscape' : 'portrait');

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'nominatif-gtk-' . now()->format('Y-m-d-His') . '.pdf'
                    );
                }),
        ];
    }
}
