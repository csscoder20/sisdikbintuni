<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
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
