<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->to('/admin/dinas');
        }
        if ($user->role === 'operator' && $user->sekolah) {
            return redirect()->to("/admin/{$user->sekolah->jenjang}/{$user->sekolah->id}/operator");
        }
    }
    return redirect()->to('/admin/login');
});
