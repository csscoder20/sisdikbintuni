<?php

$files = [
    'd:\laragon\www\sisdikbintuni\app\Filament\Resources\LaporanGedung\Pages\ListLaporanGedung.php',
    'd:\laragon\www\sisdikbintuni\app\Filament\Resources\KehadiranGtk\Pages\ListKehadiranGtk.php',
    'd:\laragon\www\sisdikbintuni\app\Filament\Resources\Kelulusan\Pages\ListKelulusan.php',
    'd:\laragon\www\sisdikbintuni\app\Filament\Resources\GtkJamAjars\Pages\ListGtkJamAjars.php',
    'd:\laragon\www\sisdikbintuni\app\Filament\Pages\KeadaanSiswa.php',
    'd:\laragon\www\sisdikbintuni\app\Filament\Pages\KeadaanGtk.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Remove ->label('...') specifically when it follows ValidateChecklistAction::make
        // Use a regex to match ->label('...') which is chained after ValidateChecklistAction::make(...)
        // Since there could be line breaks, we'll just match ->label('...') applied to the ValidateChecklistAction calls.
        
        $newContent = preg_replace("/(ValidateChecklistAction::make\([^)]+\))\s*->label\('[^']+'\)/s", "$1", $content);
        
        if ($newContent !== $content) {
            file_put_contents($file, $newContent);
            echo "Updated: $file\n";
        }
    }
}
echo "Done.\n";
