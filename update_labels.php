<?php
$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/app/Filament'));
$count = 0;
foreach ($dir as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        
        $newContent = preg_replace_callback(
            '/(protected static (\?string|string(\|\\\\?UnitEnum\|null|\|BackedEnum\|null)?) \$(modelLabel|pluralModelLabel|navigationLabel|navigationGroup)\s*=\s*)([\'"])([^\'"]+)([\'"]);/i', 
            function($m) { 
                $val = ucwords(strtolower($m[6])); 
                // Special casing for acronyms
                $val = str_replace(['Gtk', 'Sma', 'Smk'], ['GTK', 'SMA', 'SMK'], $val); 
                return $m[1] . $m[5] . $val . $m[7] . ';'; 
            }, 
            $content
        );
        
        if ($newContent !== $content) {
            file_put_contents($path, $newContent);
            echo "Updated $path\n";
            $count++;
        }
    }
}
echo "Total updated: $count\n";
