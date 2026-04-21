<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$import = \DB::table('imports')->latest()->first();
if ($import) {
    echo "Import ID: " . $import->id . "\n";
    echo "Successful rows: " . $import->successful_rows . "\n";
    echo "Total rows: " . $import->total_rows . "\n";
    echo "Failures: " . $import->failures . "\n";
} else {
    echo "No imports found.\n";
}
