<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$chartData = DB::table('sekolah')
    ->leftJoin('gtk', function ($join) {
        $join->on('sekolah.id', '=', 'gtk.sekolah_id')
            ->whereNull('gtk.deleted_at');
    })
    ->select(
        'sekolah.nama as sekolah_nama',
        DB::raw("SUM(CASE WHEN gtk.pendidikan_terakhir LIKE 'SMA%' OR gtk.pendidikan_terakhir LIKE 'SMK%' THEN 1 ELSE 0 END) as sma"),
        DB::raw("SUM(CASE WHEN gtk.pendidikan_terakhir LIKE 'S1%' OR gtk.pendidikan_terakhir LIKE 'S-1%' THEN 1 ELSE 0 END) as s1")
    )
    ->whereIn('sekolah.jenjang', ['SMA', 'SMK', 'sma', 'smk'])
    ->whereNull('sekolah.deleted_at')
    ->groupBy('sekolah.id', 'sekolah.nama')
    ->orderBy('sekolah.nama')
    ->get();

echo "=== DinasGtkPendidikanChart Data ===\n";
foreach($chartData as $d) {
    if ($d->sma > 0 || $d->s1 > 0) {
        echo $d->sekolah_nama . ": SMA=" . $d->sma . ", S1=" . $d->s1 . "\n";
    }
}

echo "\n=== Raw GTK Table - Pendidikan Terakhir ===\n";
$gtk_edu = DB::table('gtk')->select('pendidikan_terakhir', DB::raw('COUNT(*) as total'))->whereNull('deleted_at')->groupBy('pendidikan_terakhir')->get();
foreach($gtk_edu as $g) {
    echo $g->pendidikan_terakhir . ": " . $g->total . "\n";
}

$gtk_sekolah = DB::table('gtk')
    ->join('sekolah', 'gtk.sekolah_id', '=', 'sekolah.id')
    ->select('sekolah.nama', DB::raw('COUNT(gtk.id) as total'))
    ->whereNull('gtk.deleted_at')
    ->groupBy('sekolah.nama')
    ->get();

echo "\n=== Raw GTK Table - Per Sekolah ===\n";
foreach($gtk_sekolah as $s) {
    echo $s->nama . ": " . $s->total . "\n";
}
echo "\n";
