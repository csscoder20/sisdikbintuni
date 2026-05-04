<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Str;

class ImportTemplateController extends Controller
{
    /**
     * Peta nama file statis per importer.
     * Key  : nama importer (lowercase, misal 'gtk', 'siswa')
     * Value: nama file di storage/app/templates/
     */
    protected static array $staticFileMap = [
        'gtk'   => 'gtk-import_template.xlsx',
        'siswa' => 'siswa_import_template.xlsx',
    ];

    public function download(string $importerName)
    {
        $importerClass = "App\\Filament\\Imports\\" . Str::studly($importerName) . "Importer";

        if (!class_exists($importerClass)) {
            abort(404, "Importer not found: {$importerClass}");
        }

        // Nama file untuk download response (yang user lihat)
        $downloadFileName = Str::kebab($importerName) . '-template.xlsx';

        // --- Prioritas 1: File statis di storage/app/templates/ ---
        // Cari menggunakan peta nama, atau fallback ke konvensi kebab
        $staticFileName = static::$staticFileMap[strtolower($importerName)]
            ?? $downloadFileName;
        $staticPath = storage_path('app/templates/' . $staticFileName);

        if (file_exists($staticPath)) {
            return response()->download($staticPath, $downloadFileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'max-age=0',
            ]);
        }

        // --- Prioritas 2: Generate dinamis menggunakan PhpSpreadsheet ---
        $spreadsheet = new Spreadsheet();
        
        // Set default alignment to center for the entire spreadsheet
        $spreadsheet->getDefaultStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getDefaultStyle()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Get columns from importer
        $columns = $importerClass::getColumns();
        
        if (\Illuminate\Support\Str::contains(strtolower($importerName), 'gtk')) {
            // Internal No column as primary joining key
            $noColumn = \Filament\Actions\Imports\ImportColumn::make('no')
                ->label('No')
                ->example('1');

            $instructions = [
                'no' => 'cukup jelas',
                'nama' => 'diisi nama lengkap GTK, tanpa gelar depan dan belakang',
                'nik' => 'cukup jelas',
                'nip' => 'cukup jelas',
                'nokarpeg' => 'cukup jelas',
                'nuptk' => 'cukup jelas',
                'jenis_kelamin' => 'diisi L atau P',
                'tempat_lahir' => 'cukup jelas',
                'tanggal_lahir' => 'diisi dengan format dd/mm/yyyy, contoh 2 maret 1990, maka ditulis 02/03/1990',
                'alamat' => 'nama jalan, nomor rumah, RT, RW',
                'desa' => 'nama desa/kampung/kelurahan',
                'kecamatan' => 'nama distrik/kecamatan',
                'kabupaten' => 'cukup jelas',
                'provinsi' => 'cukup jelas',
                'agama' => 'pilihan: Islam, Kristen, Katolik, Hindu, Buddha, Konghucu',
                'pendidikan_terakhir' => 'diisi dengan strata pendidikan dan jurusannya, contoh: S1 Pendidikan Bahasa Indonesia, D3 Teknik Informatika',
                'daerah_asal' => 'pilihan: Papua / Non Papua',
                'jenis_gtk' => 'diisi dengan pilihan: Kepala Sekolah / Guru / Tenaga Administrasi',
                'status_kepegawaian' => 'diisi dengan CPNS, PNS, PPPK, GTY/PTY, Kontrak, Honorer Sekolah',
                'tmt_pns' => 'cukup jelas',
                'pangkat_gol_terakhir' => 'diisi dengan pangkat golongan dan ruang. Contoh: III/a',
                'tmt_pangkat_gol_terakhir' => 'cukup jelas',
            ];

            // Sheet 1: IDENTITAS
            $sheet1 = $spreadsheet->getActiveSheet();
            $sheet1->setTitle('IDENTITAS');
            $identityColumns = array_merge([$noColumn], array_slice($columns, 0, 21));
            $this->setupSheet($sheet1, null, $identityColumns, '3C8DBC', 'FFFFFF', $instructions);

            // Sheet 2: PENDIDIKAN
            $sheet2 = $spreadsheet->createSheet();
            $sheet2->setTitle('PENDIDIKAN');
            $educationColumns = array_merge([$noColumn], array_slice($columns, 21, 26));
            $this->setupSheet($sheet2, null, $educationColumns, 'FFCE44', '000000', [], 'cukup jelas, kosongkan jika tidak ada');

            // Sheet 3: REKENING
            $sheet3 = $spreadsheet->createSheet();
            $sheet3->setTitle('REKENING');
            $financeColumns = array_merge([$noColumn], array_slice($columns, 47, 3));
            $this->setupSheet($sheet3, null, $financeColumns, '28A745', 'FFFFFF', [], 'cukup jelas');

            $spreadsheet->setActiveSheetIndex(0);
        } else {
            $sheet = $spreadsheet->getActiveSheet();
            $this->setupSheet($sheet, null, $columns);
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'import_template');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ])->deleteFileAfterSend(true);
    }

    private function setupSheet($sheet, $groupTitle, $columns, $color = '3C8DBC', $fontColor = 'FFFFFF', $instructions = [], $defaultInstruction = '')
    {
        $headerRow = 1;
        $instructionRow = 2;
        $exampleRow = 3;

        if ($groupTitle) {
            $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($columns));
            $sheet->setCellValue('A1', $groupTitle);
            $sheet->mergeCells("A1:{$lastCol}1");
            $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => $fontColor]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $headerRow = 2;
            $instructionRow = 3;
            $exampleRow = 4;
        }

        $colIndex = 1;
        foreach ($columns as $column) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            
            // Header
            $label = $column->getLabel() ?? Str::title($column->getName());
            $sheet->setCellValue($colLetter . $headerRow, Str::upper($label));
            
            // Instruction
            $instruction = $instructions[$column->getName()] ?? $defaultInstruction;
            $sheet->setCellValue($colLetter . $instructionRow, $instruction);
            $sheet->getStyle($colLetter . $instructionRow)->applyFromArray([
                'font' => ['italic' => true, 'color' => ['rgb' => 'FF0000'], 'size' => 9],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);

            // Example
            $examples = $column->getExamples();
            $exampleVal = $examples[0] ?? '';
            $sheet->setCellValueExplicit(
                $colLetter . $exampleRow, 
                (string) $exampleVal, 
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );

            // Styling
            $sheet->getStyle($colLetter . '1:' . $colLetter . '1000')->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);
            $sheet->getStyle($colLetter . '1:' . $colLetter . '1000')
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
            $colIndex++;
        }

        // Header Styling
        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex-1);
        $sheet->getStyle('A' . $headerRow . ':' . $lastColLetter . $headerRow)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => $fontColor]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Instruction Styling Borders
        $sheet->getStyle('A' . $instructionRow . ':' . $lastColLetter . $instructionRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
    }
}
