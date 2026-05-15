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

    // Daftar Sekolah SMA/SMK
    $daftarSekolah = \App\Models\Sekolah::whereIn('jenjang', ['SMA', 'SMK', 'sma', 'smk'])
        ->orderBy('nama')
        ->get();

    // Sebaran Sekolah SMA/SMK per Kecamatan
    $sebaranSekolah = \App\Models\Sekolah::whereIn('jenjang', ['SMA', 'SMK', 'sma', 'smk'])
        ->select('kecamatan', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->groupBy('kecamatan')
        ->orderBy('kecamatan')
        ->get();

    return view('landing', compact('totalSiswa', 'totalGtk', 'kondisiRuang', 'totalSekolah', 'daftarSekolah', 'sebaranSekolah'));
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

    $returnUrl = url()->previous();
    $returnHost = parse_url($returnUrl, PHP_URL_HOST);

    session([
        'impersonating_sekolah_id' => $sekolah->id,
        'impersonating_return_url' => $returnHost === request()->getHost()
            ? $returnUrl
            : url('/admin/sekolahs'),
    ]);

    $panelId = strtolower($sekolah->jenjang);
    return redirect()->route("filament.{$panelId}.pages.operator-dashboard", ['tenant' => $sekolah->npsn]);
})->name('start-impersonating')->middleware('auth');



Route::get('/stop-impersonating', function () {
    $returnUrl = session('impersonating_return_url', url('/admin/sekolahs'));

    session()->forget([
        'impersonating_sekolah_id',
        'impersonating_return_url',
    ]);

    return redirect()->to($returnUrl);
})->name('stop-impersonating');
