<?php
$files = glob('D:\laragon\www\sisdikbintuni\app\Filament\Resources\*\Pages\List*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Replace 'CreateAction::make()' with 'CreateAction::make()->label('Tambah Data')'
    $newContent = str_replace(
        "CreateAction::make()",
        "CreateAction::make()->label('Tambah Data')",
        $content
    );
    
    if ($content !== $newContent) {
        file_put_contents($file, $newContent);
        echo "Updated: $file\n";
    }
}
echo "Done.\n";
