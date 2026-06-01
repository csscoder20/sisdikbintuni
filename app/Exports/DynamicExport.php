<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DynamicExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithCustomValueBinder, WithEvents, WithCustomStartCell
{
    protected $records;
    protected $columns;
    protected $sekolah;
    protected $title;

    protected $textColumnLetters = [];
    protected $rowNumber = 0;

    public function __construct($records, array $columns, $sekolah = null, string $title = '')
    {
        $this->records = $records;
        $this->columns = $columns;
        $this->sekolah = $sekolah;
        $this->title = $title;
        
        // Identify which column letters should be treated as text
        $textColumnKeys = [
            'nip', 'nik', 'nuptk', 'nisn', 'nis', 'nokarpeg', 
            'nokk', 'nobpjs', 'nohp_ortuwali', 'no_rekening',
            'npsn', 'kodepos'
        ];
        
        $colIndex = 2; // Start from 2 because 1 is 'No'
        foreach (array_keys($this->columns) as $column) {
            if (in_array(strtolower($column), $textColumnKeys) || str_contains(strtolower($column), 'nomor') || str_contains(strtolower($column), 'no_')) {
                $this->textColumnLetters[] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            }
            $colIndex++;
        }
    }

    public function collection()
    {
        return $this->records;
    }

    public function headings(): array
    {
        return array_map('strtoupper', array_merge(['No'], array_values($this->columns)));
    }

    public function map($record): array
    {
        $this->rowNumber++;
        $mapped = [$this->rowNumber];
        
        foreach (array_keys($this->columns) as $column) {
            $value = $record->{$column} ?? '-';
            
            // Convert Jenis Kelamin to L/P
            if ($column === 'jenis_kelamin') {
                if (strtolower($value) === 'laki-laki') {
                    $value = 'L';
                } elseif (strtolower($value) === 'perempuan') {
                    $value = 'P';
                }
            }

            // Format dates to Indonesian format
            if ($value instanceof \Carbon\Carbon || $value instanceof \DateTime) {
                $value = $value->format('d/m/Y');
            } elseif (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                // If it's a Y-m-d string, format it
                $value = \Carbon\Carbon::parse($value)->format('d/m/Y');
            }
            
            $mapped[] = $value;
        }
        return $mapped;
    }

    public function columnFormats(): array
    {
        $formats = [];
        foreach ($this->textColumnLetters as $letter) {
            $formats[$letter] = NumberFormat::FORMAT_TEXT;
        }
        return $formats;
    }

    public function bindValue(Cell $cell, $value)
    {
        $columnLetter = $cell->getColumn();
        
        // If this column is designated as a text column, or if it's a long numeric string
        if (in_array($columnLetter, $this->textColumnLetters) || (is_numeric($value) && strlen((string)$value) > 10)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($this->columns) + 1);

                $sheet->setCellValue('A1', $this->title);
                $sheet->mergeCells("A1:{$lastColumnLetter}1");
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1')->getFont()->setSize(12)->setBold(true);
            },
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($this->columns) + 1);
                $lastRow = $sheet->getHighestRow();

                $startRow = 3;
                // Table headings are at startRow
                $headerRange = "A{$startRow}:{$lastColumnLetter}{$startRow}";
                $tableRange = "A{$startRow}:{$lastColumnLetter}{$lastRow}";

                // Style the headings
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E78'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                // Set row height for header (increase to accommodate wrap text)
                $sheet->getRowDimension($startRow)->setRowHeight(35);

                // Column Sizing Logic
                $sheet->getColumnDimension('A')->setWidth(5);
                $colIndex = 2;
                foreach (array_values($this->columns) as $label) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                    
                    // If header has multiple words, wrap the entire column and set a reasonable width
                    if (str_contains(trim($label), ' ')) {
                        $sheet->getColumnDimension($columnLetter)->setAutoSize(false);
                        $sheet->getColumnDimension($columnLetter)->setWidth(20);
                        
                        // Enable wrap text for the whole column
                        $sheet->getStyle("{$columnLetter}{$startRow}:{$columnLetter}{$lastRow}")->getAlignment()->setWrapText(true);
                    } else {
                        $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
                    }
                    $colIndex++;
                }

                // Add borders to the entire table
                $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Center align the NO column
                $sheet->getStyle("A{$startRow}:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Set default alignment for all table cells to center vertical and set font size
                $sheet->getStyle($tableRange)->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'size' => 10,
                    ],
                ]);
                
                // Re-apply bold and white to headers because we just overwrote with general font style
                $sheet->getStyle($headerRange)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));

                // Ensure row heights for data rows are automatic to accommodate wrapped text
                for ($i = $startRow + 1; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(-1); // -1 means auto height
                }
            }
        ];
    }
}
