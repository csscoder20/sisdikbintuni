<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tenantId = 3; // Berdasarkan log sebelumnya, sekolah_id = 3

$gtks = \App\Models\Gtk::where('sekolah_id', $tenantId)->get();
echo "Total GTK di sekolah ID {$tenantId}: " . $gtks->count() . "\n";

foreach ($gtks as $gtk) {
    $mengajarCount = \App\Models\Mengajar::where('gtk_id', $gtk->id)
        ->whereNull('rombel_id')
        ->whereNull('mapel_id')
        ->count();
    
    echo "GTK: {$gtk->nama} ({$gtk->jenis_gtk}) -> Sebaran Jam: " . ($mengajarCount > 0 ? "ADA" : "TIDAK ADA") . "\n";
}
