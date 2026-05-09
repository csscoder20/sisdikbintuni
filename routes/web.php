<?php

use App\Models\Gtk;
use App\Models\Siswa;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $totalSiswa = Siswa::count();
    $totalGtk = Gtk::count();
    $totalSekolah = \App\Models\Sekolah::count();

    // Mengambil data dari tabel laporan_gedung
    $gedungStats = \Illuminate\Support\Facades\DB::table('laporan_gedung')
        ->selectRaw('SUM(jumlah_baik) as baik, SUM(jumlah_rusak) as rusak')
        ->first();

    $kondisiRuang = [
        'Baik' => (int) ($gedungStats->baik ?? 0),
        'Rusak Ringan' => 0, // Tabel laporan_gedung Anda tidak memisahkan rusak ringan/sedang
        'Rusak Sedang' => 0,
        'Rusak Berat' => (int) ($gedungStats->rusak ?? 0),
    ];

    // Mengambil data Laporan Masuk terbaru
    $latestLaporan = \App\Models\Laporan::orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->first();
    $laporanBulan = $latestLaporan ? $latestLaporan->bulan : date('n');
    $laporanTahun = $latestLaporan ? $latestLaporan->tahun : date('Y');

    $laporanMasuk = \App\Models\Laporan::where('tahun', $laporanTahun)
        ->where('bulan', $laporanBulan)
        ->whereIn('status', ['submitted', 'verified'])
        ->count();

    $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ][$laporanBulan] ?? '';
    $periodeLaporan = "{$namaBulan} {$laporanTahun}";

    return view('landing', compact('totalSiswa', 'totalGtk', 'kondisiRuang', 'totalSekolah', 'laporanMasuk', 'periodeLaporan'));
});

Route::get('/admin', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('operator')) {
            $sekolah = $user->sekolah;
            if ($sekolah) {
                return redirect()->to("/admin/" . strtolower($sekolah->jenjang) . "/{$sekolah->getRouteKey()}");
            }
        }
        return redirect()->to('/admin/dinas');
    }
    return redirect()->to('/admin/login');
});

Route::get('/login', function () {
    return redirect()->route('filament.dinas.auth.login');
})->name('login');

Route::get('/import-template/{importer}', [\App\Http\Controllers\ImportTemplateController::class, 'download'])->name('import-template.download');
Route::get('/start-impersonating/{sekolah}', function (\App\Models\Sekolah $sekolah) {
    if (!auth()->check() || !(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin_dinas'))) {
        abort(403);
    }
    session(['impersonating_sekolah_id' => $sekolah->id]);
    $panelId = strtolower($sekolah->jenjang);
    return redirect()->route("filament.{$panelId}.pages.operator-dashboard", ['tenant' => $sekolah->npsn]);
})->name('start-impersonating')->middleware('auth');



Route::get('/stop-impersonating', function () {

    session()->forget('impersonating_sekolah_id');
    return redirect()->back();
})->name('stop-impersonating');
