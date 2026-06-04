<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiDetailController extends Controller
{
    /**
     * Tampilkan halaman detail rilis note untuk operator.
     * Otomatis menandai notifikasi Filament sebagai sudah dibaca.
     */
    public function show(int $id, Request $request)
    {
        $notifikasi = Notifikasi::withoutTrashed()->findOrFail($id);

        // Pastikan hanya user yang sudah login yang bisa mengakses
        abort_if(! Auth::check(), 403);

        return view('notifikasi.detail', compact('notifikasi'));
    }
}
