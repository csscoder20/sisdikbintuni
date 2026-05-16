<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$id = 1;
$s = \App\Models\Sekolah::find($id);

if (!$s) {
    echo "Sekolah tidak ditemukan\n";
    exit;
}

$checklist = [
    'identitas_sekolah',
    'kondisi_sarpras',
    'kondisi_siswa',
    'kondisi_gtk',
    'nominatif_gtk',
    'riwayat_pendidikan_gtk',
    'nominatif_siswa',
    'rekening_npwp_gtk',
    'sebaran_jam',
    'rekap_kehadiran',
    'kelulusan',
];

echo "DEBUG VALIDASI: " . $s->nama . "\n";
echo "---------------------------------\n";
foreach ($checklist as $key) {
    $isValid = $s->checkSectionValidity($key);
    echo str_pad($key, 25) . ": " . ($isValid ? "VALID" : "INVALID") . "\n";
}
