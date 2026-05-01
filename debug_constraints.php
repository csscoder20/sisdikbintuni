<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$constraints = \Illuminate\Support\Facades\DB::select("
    SELECT conname, pg_get_constraintdef(oid) as definition 
    FROM pg_constraint 
    WHERE conrelid = 'gtk'::regclass
");

foreach ($constraints as $c) {
    echo "{$c->conname}: {$c->definition}\n";
}
