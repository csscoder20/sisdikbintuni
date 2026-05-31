<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DynamicExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithCustomValueBinder, WithDrawings, WithEvents, WithCustomStartCell
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

    public function drawings()
    {
        $drawings = [];

        // Left Logo
        if (file_exists(public_path('assets/logo/logo-bintuni.png'))) {
            $drawing = new Drawing();
            $drawing->setName('Logo Bintuni');
            $drawing->setPath(public_path('assets/logo/logo-bintuni.png'));
            $drawing->setHeight(70);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(5);
            $drawings[] = $drawing;
        }

        // Right Logo
        $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($this->columns) + 1);
        if (file_exists(public_path('assets/logo/tut-wuri-handayani.png'))) {
            $drawing2 = new Drawing();
            $drawing2->setName('Logo Tut Wuri');
            $drawing2->setPath(public_path('assets/logo/tut-wuri-handayani.png'));
            $drawing2->setHeight(70);
            $drawing2->setCoordinates($lastColumnLetter . '1');
            $drawing2->setOffsetX(-5);
            $drawing2->setOffsetY(5);
            $drawings[] = $drawing2;
        }

        return $drawings;
    }

    public function startCell(): string
    {
        return $this->sekolah ? 'A9' : 'A6';
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($this->columns) + 1);

                // Header Text
                $sheet->setCellValue('A1', 'PEMERINTAH KABUPATEN TELUK BINTUNI');
                $sheet->setCellValue('A2', 'DINAS PENDIDIKAN, KEBUDAYAAN, PEMUDA, DAN OLAHRAGA');
                
                $titleRow = 4;
                if ($this->sekolah) {
                    $sheet->setCellValue('A3', strtoupper($this->sekolah->nama));
                    
                    $alamat = ($this->sekolah->alamat ?? '---') . ', ' . ($this->sekolah->desa ?? '-') . ', ' . ($this->sekolah->kecamatan ?? '-') . ', ' . ($this->sekolah->kabupaten ?? 'Teluk Bintuni') . ', Papua Barat';
                    $sheet->setCellValue('A4', $alamat);
                    
                    $kontak = 'email : ' . ($this->sekolah->email ?? '-') . ' - Website : ' . ($this->sekolah->website ?? '-') . ' - Kode Pos: ' . ($this->sekolah->kodepos ?? '-');
                    $sheet->setCellValue('A5', $kontak);
                    
                    $sheet->mergeCells("A3:{$lastColumnLetter}3");
                    $sheet->mergeCells("A4:{$lastColumnLetter}4");
                    $sheet->mergeCells("A5:{$lastColumnLetter}5");
                    $sheet->getStyle("A1:{$lastColumnLetter}5")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('A3')->getFont()->setSize(14)->setBold(true);
                    $sheet->getStyle('A4:A5')->getFont()->setSize(8);
                    $sheet->getStyle("A5:{$lastColumnLetter}5")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THICK);
                    $titleRow = 7;
                } else {
                    $sheet->getStyle("A1:{$lastColumnLetter}2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("A2:{$lastColumnLetter}2")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THICK);
                    // Hide empty rows to shift table up, but Excel doesn't easily delete rows in event. 
                    // Better to just start the title at row 4
                }

                $sheet->mergeCells("A1:{$lastColumnLetter}1");
                $sheet->mergeCells("A2:{$lastColumnLetter}2");
                $sheet->getStyle('A1:A2')->getFont()->setSize(11);

                $sheet->setCellValue('A' . $titleRow, $this->title);
                $sheet->mergeCells('A' . $titleRow . ":{$lastColumnLetter}" . $titleRow);
                $sheet->getStyle('A' . $titleRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $titleRow)->getFont()->setSize(12)->setBold(true);
            },
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($this->columns) + 1);
                $lastRow = $sheet->getHighestRow();

                $startRow = $this->sekolah ? 9 : 6;
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
