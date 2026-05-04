<?php
require_once __DIR__ . '/../vendor/autoload.php';

$files = [
    'GTK'   => __DIR__ . '/../storage/app/templates/gtk-import_template.xlsx',
    'Siswa' => __DIR__ . '/../storage/app/templates/siswa_import_template.xlsx',
];

foreach ($files as $label => $path) {
    echo "=== $label: " . basename($path) . " ===\n";
    if (!file_exists($path)) { echo "FILE NOT FOUND\n\n"; continue; }

    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($path);

    foreach ($spreadsheet->getAllSheets() as $i => $sheet) {
        $name = $sheet->getTitle();
        $highestRow = $sheet->getHighestDataRow();
        $highestCol = $sheet->getHighestDataColumn();

        // Read header row(s)
        echo "  Sheet[$i]: '$name'  (rows: $highestRow, cols up to: $highestCol)\n";

        // Print first 3 rows
        for ($r = 1; $r <= min(3, $highestRow); $r++) {
            $rowData = [];
            foreach ($sheet->getRowIterator($r, $r) as $row) {
                foreach ($row->getCellIterator('A', $highestCol) as $cell) {
                    $v = $cell->getValue();
                    if ($v !== null && $v !== '') $rowData[] = $v;
                }
            }
            echo "    Row $r: " . implode(' | ', $rowData) . "\n";
        }
    }
    echo "\n";
}
