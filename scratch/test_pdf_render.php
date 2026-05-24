<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$pdf = (new \App\Http\Controllers\CetakLaporanController())
    ->downloadPdf(\App\Models\Sekolah::find(1));

file_put_contents(__DIR__ . '/../storage/app/public/test-laporan.pdf', $pdf->getContent());

echo "done\n";
