<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use App\Filament\Imports\SiswaImporter;
use App\Filament\Traits\HasImportTemplate;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\CheckboxList;
use Barryvdh\DomPDF\Facade\Pdf;

class ListSiswas extends ListRecords
{
    use HasImportTemplate;

    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \App\Filament\Actions\ExcelImportAction::make()
                ->importer(SiswaImporter::class)
                ->label('Impor Data Siswa')
                ->modalHeading('Impor Data Siswa')
                ->modalDescription(fn() => new \Illuminate\Support\HtmlString(
                    \Illuminate\Support\Facades\Blade::render(
                        <<<'BLADE'
                        <div class="text-sm space-y-2">
                            <div>
                                <x-filament::link href="{{ route('import-template.download', ['importer' => 'siswa']) }}" tag="a" color="success" class="font-bold hover:underline">
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
                ->label('Tambah Siswa')
                ->modalHeading('Tambah Data Siswa')
                ->modalSubmitActionLabel('Simpan Data Siswa')
                ->modalFooterActions([])
                ->createAnother(false),
            Action::make('export')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->modalHeading('Export Nominatif Siswa')
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
                                    'nama' => 'Nama Lengkap',
                                    'nisn' => 'NISN',
                                    'nik' => 'NIK',
                                    'jenis_kelamin' => 'JK',
                                    'status' => 'Status Siswa',
                                    'tempat_lahir' => 'Tempat Lahir',
                                    'tanggal_lahir' => 'Tanggal Lahir',
                                    'agama' => 'Agama',
                                    'daerah_asal' => 'Daerah Asal',
                                    'alamat' => 'Alamat Domisili',
                                    'desa' => 'Desa/Kelurahan',
                                    'kecamatan' => 'Kecamatan',
                                    'kabupaten' => 'Kabupaten',
                                    'provinsi' => 'Provinsi',
                                    'nama_ayah' => 'Nama Ayah',
                                    'nama_ibu' => 'Nama Ibu',
                                    'nama_wali' => 'Nama Wali',
                                    'nohp_ortuwali' => 'No. HP Orang Tua/Wali',
                                    'nokk' => 'Nomor KK',
                                    'nobpjs' => 'Nomor BPJS',
                                    'disabilitas' => 'Jenis Disabilitas',
                                ])
                                ->columns(4)
                                ->bulkToggleable()
                                ->default(['nama', 'nisn', 'jenis_kelamin', 'status'])
                                ->required(),
                        ]),
                ])
                ->action(function (array $data) {
                    $records = $this->getFilteredTableQuery()->get();
                    $allColumns = [
                        'nama' => 'Nama Lengkap',
                        'nisn' => 'NISN',
                        'nik' => 'NIK',
                        'jenis_kelamin' => 'JK',
                        'status' => 'Status Siswa',
                        'tempat_lahir' => 'Tempat Lahir',
                        'tanggal_lahir' => 'Tanggal Lahir',
                        'agama' => 'Agama',
                        'daerah_asal' => 'Daerah Asal',
                        'alamat' => 'Alamat Domisili',
                        'desa' => 'Desa/Kelurahan',
                        'kecamatan' => 'Kecamatan',
                        'kabupaten' => 'Kabupaten',
                        'provinsi' => 'Provinsi',
                        'nama_ayah' => 'Nama Ayah',
                        'nama_ibu' => 'Nama Ibu',
                        'nama_wali' => 'Nama Wali',
                        'nohp_ortuwali' => 'No. HP Orang Tua/Wali',
                        'nokk' => 'Nomor KK',
                        'nobpjs' => 'Nomor BPJS',
                        'disabilitas' => 'Jenis Disabilitas',
                    ];

                    $selectedColumns = array_intersect_key($allColumns, array_flip($data['columns']));
                    $sekolah = filament()->getTenant();
                    $filename = 'Daftar Nominatif Siswa - ' . ($sekolah?->nama ?? 'Sekolah');

                    if ($data['format'] === 'xlsx') {
                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new \App\Exports\DynamicExport($records, $selectedColumns, $sekolah, 'DAFTAR NOMINATIF SISWA ' . strtoupper($sekolah?->nama ?? '')),
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

                    $html = view('pdf.siswa-nominatif', [
                        'records' => $records,
                        'columns' => $selectedColumns,
                        'sekolah' => filament()->getTenant(),
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
