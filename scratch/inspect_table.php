<?php
require 'vendor/autoload.php';
$methods = get_class_methods(\Filament\Tables\Table::class);
foreach($methods as $m) {
    if(str_contains(strtolower($m), 'query')) {
        echo $m . PHP_EOL;
    }
}
