<?php

use App\Filament\Pages\KeadaanSiswa;
use App\Filament\Actions\ValidateChecklistAction;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Mock a page instance
$page = new KeadaanSiswa();

// Render one action and see the attributes
$action = $page->validateSiswaRombelAction();
echo "Action Name: " . $action->getName() . "\n";
// We can't easily render to HTML here without a full Livewire lifecycle, 
// but we can inspect the properties.

echo "Extra Attributes: " . json_encode($action->getExtraAttributes()) . "\n";
echo "Wire Click: " . $action->getWireClick() . "\n";
