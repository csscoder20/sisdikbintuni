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
            Action::make('export')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->modalHeading('Export Nominatif GTK')
                ->modalDescription('Pilih format file dan kolom yang ingin Anda sertakan.')
                ->modalSubmitActionLabel('Export Sekarang')
                ->form([
                    \Filament\Schemas\Components\Section::make('Format File')
                        ->compact()
                        ->schema([
                            \Filament\Forms\Components\Radio::make('format')
                                ->label('Pilih Format')
                                ->options([
                                    'xlsx' => 'Excel (.xlsx)',
                                    'pdf' => 'PDF (.pdf)',
                                ])
                                ->default('xlsx')
                                ->required()
                                ->inline(),
                        ]),
                    \Filament\Schemas\Components\Section::make('Kolom Data')
                        ->compact()
                        ->schema([
                            CheckboxList::make('columns')
                                ->label('Pilih Kolom')
                                ->options([
                                    'nama' => 'Nama GTK',
                                    'nik' => 'NIK',
                                    'nip' => 'NIP',
                                    'nuptk' => 'NUPTK',
                                    'nokarpeg' => 'No Karpeg',
                                    'jenis_gtk' => 'Jenis GTK',
                                    'jenis_kelamin' => 'JK',
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
                                ->columns(4)
                                ->bulkToggleable()
                                ->default(['nama', 'nip', 'nuptk', 'jenis_gtk', 'status_kepegawaian'])
                                ->required(),
                        ]),
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
                        'jenis_kelamin' => 'JK',
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
                    $filename = 'nominatif-gtk-' . now()->format('Y-m-d-His');

                    if ($data['format'] === 'xlsx') {
                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new \App\Exports\DynamicExport($records, $selectedColumns, filament()->getTenant(), 'DAFTAR NOMINATIF GURU DAN TENAGA KEPENDIDIKAN'),
                            $filename . '.xlsx'
                        );
                    }

                    $pdf = Pdf::loadView('pdf.gtk-nominatif', [
                        'records' => $records,
                        'columns' => $selectedColumns,
                        'sekolah' => filament()->getTenant(),
                    ])->setPaper('a4', count($data['columns']) > 7 ? 'landscape' : 'portrait');

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        $filename . '.pdf'
                    );
                }),
        ];
    }
}
