<?php
$files = glob('D:\laragon\www\sisdikbintuni\app\Filament\Resources\*\Pages\List*.php');

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Fix Actions\CreateAction and Filament\Filament\Actions if present
        $newContent = str_replace(
            "Actions\CreateAction::make()",
            "CreateAction::make()",
            $content
        );

        if ($newContent !== $content) {
            file_put_contents($file, $newContent);
            echo "Updated: $file\n";
        }
    }
}
echo "Done.\n";
