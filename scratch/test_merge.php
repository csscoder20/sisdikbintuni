<?php
require_once __DIR__ . '/../vendor/autoload.php';

$path = __DIR__ . '/../storage/app/templates/gtk-import_template.xlsx';

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($path);

$allSheets = collect();
foreach ($spreadsheet->getAllSheets() as $ws) {
    $sheetRows = collect();
    foreach ($ws->toArray(null, true, true, false) as $rawRow) {
        $sheetRows->push(collect($rawRow));
    }
    $allSheets->push($sheetRows);
}

$normalize = fn($s) => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string)$s));

$primarySheet = $allSheets->first();
$sheets = $allSheets->slice(1)->values();

// Find header
$primaryHeaderIndex = 0; $keyIndex = false;
foreach ($primarySheet->take(2) as $idx => $row) {
    $foundKey = $row->search(fn($h) => in_array($normalize($h), ['no', 'nourut', 'nik', 'nip']));
    if ($foundKey !== false) { $primaryHeaderIndex = $idx; $keyIndex = $foundKey; break; }
}

$primaryHeaders = $primarySheet[$primaryHeaderIndex];
$mergedData = []; $headerMap = $primaryHeaders->toArray();

// FIXED: skip baris instruksi (key bukan angka positif)
foreach ($primarySheet->slice($primaryHeaderIndex + 1) as $row) {
    $key = trim((string)($row[$keyIndex] ?? ''));
    if ($key !== '' && is_numeric($key) && (int)$key > 0) {
        $mergedData[$key] = $row->toArray();
    }
}

echo "Rows from primary (after filter): " . count($mergedData) . "\n";
echo "Keys: " . implode(', ', array_slice(array_keys($mergedData), 0, 5)) . "\n";

foreach ($sheets as $sheet) {
    $headerIndex = 0; $sheetKeyIndex = false;
    foreach ($sheet->take(2) as $idx => $row) {
        $foundKey = $row->search(fn($h) => in_array($normalize($h), ['no', 'nourut', 'nik', 'nip']));
        if ($foundKey !== false) { $headerIndex = $idx; $sheetKeyIndex = $foundKey; break; }
    }
    if ($sheetKeyIndex === false) continue;

    $headers = $sheet[$headerIndex];
    $newColumns = [];
    foreach ($headers as $idx => $header) {
        if ($idx === $sheetKeyIndex) continue;
        $alreadyExists = collect($headerMap)->contains(fn($h) => $normalize($h) === $normalize($header));
        if (!$alreadyExists) { $newColumns[$idx] = count($headerMap); $headerMap[] = $header; }
    }

    $matched = 0;
    foreach ($sheet->slice($headerIndex + 1) as $row) {
        $key = trim((string)($row[$sheetKeyIndex] ?? ''));
        // FIXED: skip instruksi di secondary sheet juga
        if (!is_numeric($key) || (int)$key <= 0) continue;
        if (isset($mergedData[$key])) {
            $matched++;
            foreach ($newColumns as $oldIdx => $newIdx) {
                $mergedData[$key][$newIdx] = $row[$oldIdx] ?? null;
            }
        }
    }
    echo "Sheet matched: $matched rows, added " . count($newColumns) . " new columns\n";
}

echo "\nTotal merged header columns: " . count($headerMap) . "\n";
$firstKey = array_key_first($mergedData);
$firstRow = $mergedData[$firstKey];

// Check fields
foreach (['NAMA LENGKAP', 'TAHUN TAMAT S1', 'JURUSAN S1', 'NO. REKENING', 'NAMA BANK', 'NPWP', 'GELAR BELAKANG'] as $field) {
    $idx = array_search($field, $headerMap);
    $val = $idx !== false ? ($firstRow[$idx] ?? 'null') : 'HEADER NOT FOUND';
    echo "  $field [col $idx]: $val\n";
}
