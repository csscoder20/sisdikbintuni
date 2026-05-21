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
                    $sekolah = filament()->getTenant();
                    if (!$sekolah && session('dinas_selected_sekolah_id')) {
                        $sekolah = \App\Models\Sekolah::find(session('dinas_selected_sekolah_id'));
                    }
                    $filename = 'Daftar Nominatif GTK - ' . ($sekolah?->nama ?? 'Sekolah');

                    if ($data['format'] === 'xlsx') {
                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new \App\Exports\DynamicExport($records, $selectedColumns, $sekolah, 'DAFTAR NOMINATIF GURU DAN TENAGA KEPENDIDIKAN ' . strtoupper($sekolah?->nama ?? '')),
                            $filename . '.xlsx'
                        );
                    }

                    $columnCount = count($data['columns']);
                    $fontSize = '9pt';
                    if ($columnCount <= 5) {
                        $fontSize = '9.5pt';
                    } elseif ($columnCount <= 8) {
                        $fontSize = '8pt';
                    } elseif ($columnCount <= 12) {
                        $fontSize = '7pt';
                    } elseif ($columnCount <= 16) {
                        $fontSize = '6pt';
                    } else {
                        $fontSize = '5pt';
                    }

                    $html = view('pdf.gtk-nominatif', [
                        'records' => $records,
                        'columns' => $selectedColumns,
                        'sekolah' => $sekolah,
                        'fontSize' => $fontSize,
                    ])->render();

                    $browsershot = \Spatie\Browsershot\Browsershot::html($html)
                        ->setNodeBinary('C:\Program Files\nodejs\node.exe')
                        ->setNpmBinary('C:\Program Files\nodejs\npm.cmd')
                        ->preferCssPageSize()
                        ->format('A4')
                        ->showBackground()
                        ->noSandbox();

                    if ($columnCount > 7) {
                        $browsershot->landscape();
                    }

                    $pdfContent = $browsershot->pdf();

                    return response()->streamDownload(
                        fn () => print($pdfContent),
                        $filename . '.pdf'
                    );
                }),
        ];
    }
}
