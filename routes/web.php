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

    // Grafik GTK per Sekolah berdasarkan Status Kepegawaian
    $grafikGtkSekolah = \Illuminate\Support\Facades\DB::table('sekolah')
        ->leftJoin('gtk', function ($join) {
            $join->on('sekolah.id', '=', 'gtk.sekolah_id')
                 ->whereNull('gtk.deleted_at');
        })
        ->select(
            'sekolah.nama as sekolah_nama',
            \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN gtk.status_kepegawaian = 'PNS' THEN 1 ELSE 0 END) as pns"),
            \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN gtk.status_kepegawaian = 'CPNS' THEN 1 ELSE 0 END) as cpns"),
            \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN gtk.status_kepegawaian = 'PPPK' THEN 1 ELSE 0 END) as pppk"),
            \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN gtk.status_kepegawaian = 'GTY/PTY' THEN 1 ELSE 0 END) as gty_pty"),
            \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN gtk.status_kepegawaian = 'Kontrak' THEN 1 ELSE 0 END) as kontrak"),
            \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN gtk.status_kepegawaian = 'Honorer Sekolah' THEN 1 ELSE 0 END) as honorer")
        )
        ->whereIn('sekolah.jenjang', ['SMA', 'SMK', 'sma', 'smk'])
        ->whereNull('sekolah.deleted_at')
        ->groupBy('sekolah.id', 'sekolah.nama')
        ->orderBy('sekolah.nama')
        ->get();

    // Grafik Kondisi Sarpras per Sekolah
    $grafikSarprasSekolah = \Illuminate\Support\Facades\DB::table('sekolah')
        ->leftJoin('laporan', 'sekolah.id', '=', 'laporan.sekolah_id')
        ->leftJoin('laporan_gedung', 'laporan.id', '=', 'laporan_gedung.laporan_id')
        ->select(
            'sekolah.nama as sekolah_nama',
            \Illuminate\Support\Facades\DB::raw('COALESCE(SUM(laporan_gedung.jumlah_baik), 0) as baik'),
            \Illuminate\Support\Facades\DB::raw('COALESCE(SUM(laporan_gedung.jumlah_rusak), 0) as rusak')
        )
        ->whereIn('sekolah.jenjang', ['SMA', 'SMK', 'sma', 'smk'])
        ->whereNull('sekolah.deleted_at')
        ->groupBy('sekolah.id', 'sekolah.nama')
        ->orderBy('sekolah.nama')
        ->get();

    // Grafik Siswa Laki-laki dan Perempuan per Sekolah
    $grafikSiswaSekolah = \Illuminate\Support\Facades\DB::table('sekolah')
        ->leftJoin('siswa', function ($join) {
            $join->on('sekolah.id', '=', 'siswa.sekolah_id')
                 ->whereNull('siswa.deleted_at');
        })
        ->select(
            'sekolah.nama as sekolah_nama',
            \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN LOWER(siswa.jenis_kelamin) IN ('laki-laki', 'l') THEN 1 ELSE 0 END) as laki_laki"),
            \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN LOWER(siswa.jenis_kelamin) IN ('perempuan', 'p') THEN 1 ELSE 0 END) as perempuan")
        )
        ->whereIn('sekolah.jenjang', ['SMA', 'SMK', 'sma', 'smk'])
        ->whereNull('sekolah.deleted_at')
        ->groupBy('sekolah.id', 'sekolah.nama')
        ->orderBy('sekolah.nama')
        ->get();

    return view('landing', compact('totalSiswa', 'totalGtk', 'kondisiRuang', 'totalSekolah', 'daftarSekolah', 'sebaranSekolah', 'grafikGtkSekolah', 'grafikSarprasSekolah', 'grafikSiswaSekolah'));
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
