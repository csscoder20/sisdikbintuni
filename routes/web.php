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
        ->select(
            'kecamatan', 
            \Illuminate\Support\Facades\DB::raw('count(*) as total'),
            \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN LOWER(jenjang) = 'sma' THEN 1 ELSE 0 END) as sma"),
            \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN LOWER(jenjang) = 'smk' THEN 1 ELSE 0 END) as smk")
        )
        ->groupBy('kecamatan')
        ->orderBy('kecamatan')
        ->get();

    // Grafik GTK berdasarkan Status Kepegawaian
    $statuses = ['PNS', 'CPNS', 'PPPK', 'GTY/PTY', 'Kontrak', 'Honorer Sekolah'];
    $grafikGtkStatus = [];
    foreach ($statuses as $status) {
        $count = \App\Models\Gtk::where('status_kepegawaian', $status)->count();
        if ($count > 0) {
            $grafikGtkStatus[] = ['status' => $status, 'total' => $count];
        }
    }

    // Grafik GTK berdasarkan Pendidikan Terakhir
    $pendidikan = ['D3', 'S1', 'S2', 'S3'];
    $grafikGtkPendidikan = [];
    foreach ($pendidikan as $p) {
        $count = \App\Models\Gtk::whereIn('jenis_gtk', ['Guru', 'Kepala Sekolah'])
            ->where(function($query) use ($p) {
                $query->where('pendidikan_terakhir', 'like', $p . '%')
                    ->orWhere('pendidikan_terakhir', 'like', substr($p, 0, 1) . '-' . substr($p, 1) . '%');
            })
            ->count();
        if ($count > 0) {
            $grafikGtkPendidikan[] = ['pendidikan' => $p, 'total' => $count];
        }
    }

    return view('landing', compact('totalSiswa', 'totalGtk', 'kondisiRuang', 'totalSekolah', 'daftarSekolah', 'sebaranSekolah', 'grafikGtkStatus', 'grafikGtkPendidikan'));
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

Route::get('/cetak-laporan/{sekolah}/pdf', [\App\Http\Controllers\CetakLaporanController::class, 'downloadPdf'])->name('cetak-laporan.pdf')->middleware('auth');
