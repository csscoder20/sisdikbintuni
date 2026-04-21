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
    public function download(string $importerName)
    {
        $importerClass = "App\\Filament\\Imports\\" . Str::studly($importerName) . "Importer";

        if (!class_exists($importerClass)) {
            abort(404, "Importer not found: {$importerClass}");
        }

        $fileName = Str::kebab($importerName) . '-template.xlsx';
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Get columns from importer
        $columns = $importerClass::getColumns();
        
        if ($importerName === 'gtk') {
            // Group indices based on the expanded GtkImporter
            // Identity: 0-20
            // Education: 21-46
            // Finance: 47-49
            
            // Row 1: Groups
            $sheet->setCellValue('A1', 'IDENTITAS GTK');
            $sheet->mergeCells('A1:U1');
            
            $sheet->setCellValue('V1', 'PENDIDIKAN GTK');
            $sheet->mergeCells('V1:AU1');
            
            $sheet->setCellValue('AV1', 'REKENING DAN NPWP GTK');
            $sheet->mergeCells('AV1:AX1');

            // Styling Row 1
            $sheet->getStyle('A1:U1')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3C8DBC']], // Blue
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $sheet->getStyle('V1:AU1')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFCE44']], // Yellow
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $sheet->getStyle('AV1:AX1')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '28A745']], // Green
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);

            $headerRow = 2;
            $exampleRow = 3;
        } else {
            $headerRow = 1;
            $exampleRow = 2;
        }

        // Header and Example Rows
        $colIndex = 1;
        foreach ($columns as $column) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            
            // Set Header
            $sheet->setCellValue($colLetter . $headerRow, $column->getLabel() ?? Str::title($column->getName()));
            
            // Set Example as EXPLICIT STRING to prevent scientific notation
            $examples = $column->getExamples();
            $exampleVal = $examples[0] ?? '';
            $sheet->setCellValueExplicit(
                $colLetter . $exampleRow, 
                (string) $exampleVal, 
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );

            // Force the entire column (up to 1000 rows) to Text format
            $sheet->getStyle($colLetter . $headerRow . ':' . $colLetter . '1000')
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

            // Auto-size columns
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
            
            $colIndex++;
        }

        // Styling Header Row
        $sheet->getStyle('A' . $headerRow . ':' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex-1) . $headerRow)->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'import_template');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ])->deleteFileAfterSend(true);
    }
}
