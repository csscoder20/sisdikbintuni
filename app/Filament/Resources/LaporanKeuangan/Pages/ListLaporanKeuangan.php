<?php

namespace App\Filament\Resources\LaporanKeuangan\Pages;

use App\Filament\Actions\ExcelImportAction;
use App\Filament\Imports\LaporanKeuanganImporter;
use App\Filament\Resources\LaporanKeuangan\LaporanKeuanganResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;

class ListLaporanKeuangan extends ListRecords
{
    protected static string $resource = LaporanKeuanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()
                ->importer(LaporanKeuanganImporter::class)
                ->label('Impor Data Keuangan')
                ->modalHeading('Impor Data')
                ->modalDescription(fn() => new HtmlString(
                    Blade::render(
                        <<<'BLADE'
                        <div class="space-y-2">
                            <div>
                                <x-filament::link href="{{ route('import-template.download', ['importer' => 'laporan-keuangan']) }}" tag="a" color="success" class="font-bold hover:underline">
                                    Unduh contoh berkas (.xlsx)
                                </x-filament::link>
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">
                                Silakan isi data di Excel, lalu unggah berkas tersebut langsung ke sini.
                            </div>
                        </div>
                        BLADE
                    )
                ))
                ->modalSubmitActionLabel('Impor Sekarang')
                ->color('info'),
            CreateAction::make()
                ->label('Tambah Transaksi')
                ->modalHeading('Tambah Transaksi Baru')
                ->modalSubmitActionLabel('Simpan Transaksi')
                ->createAnother(false),
            Action::make('export')
                ->label('Expor Data')
                // ->icon('heroicon-o-arrow-down-tray')
                ->color('warning')
                ->modalHeading('Export Data Keuangan')
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
                                    'tanggal' => 'Tanggal',
                                    'jenis_transaksi' => 'Jenis Transaksi',
                                    'keterangan' => 'Keterangan',
                                    'nominal' => 'Nominal',
                                    'saldo' => 'Saldo',
                                ])
                                ->columns(2)
                                ->bulkToggleable()
                                ->default(['tanggal', 'jenis_transaksi', 'keterangan', 'nominal', 'saldo'])
                                ->required(),
                        ]),
                ])
                ->action(function (array $data) {
                    $records = $this->getFilteredTableQuery()->get();
                    $allColumns = [
                        'tanggal' => 'Tanggal',
                        'jenis_transaksi' => 'Jenis Transaksi',
                        'keterangan' => 'Keterangan',
                        'nominal' => 'Nominal',
                        'saldo' => 'Saldo',
                    ];

                    $selectedColumns = array_intersect_key($allColumns, array_flip($data['columns']));
                    $sekolah = Filament::getTenant();
                    if (!$sekolah && session('dinas_selected_sekolah_id')) {
                        $sekolah = \App\Models\Sekolah::find(session('dinas_selected_sekolah_id'));
                    }
                    $filename = 'Data Keuangan - ' . ($sekolah?->nama ?? 'Sekolah');

                    if ($data['format'] === 'xlsx') {
                        return Excel::download(
                            new \App\Exports\DynamicExport($records, $selectedColumns, $sekolah, 'DATA KEUANGAN ' . strtoupper($sekolah?->nama ?? '')),
                            $filename . '.xlsx'
                        );
                    }

                    $html = view('pdf.laporan-keuangan', [
                        'records' => $records,
                        'columns' => $selectedColumns,
                        'sekolah' => $sekolah,
                    ])->render();

                    $browsershot = \Spatie\Browsershot\Browsershot::html($html)
                        ->setNodeBinary('C:\\Program Files\\nodejs\\node.exe')
                        ->setNpmBinary('C:\\Program Files\\nodejs\\npm.cmd')
                        ->preferCssPageSize()
                        ->format('A4')
                        ->showBackground()
                        ->noSandbox();

                    if (count($selectedColumns) > 3) {
                        $browsershot->landscape();
                    }

                    $pdfContent = $browsershot->pdf();

                    return response()->streamDownload(
                        fn() => print($pdfContent),
                        $filename . '.pdf'
                    );
                }),
        ];
    }
}
