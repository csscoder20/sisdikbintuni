<?php

namespace App\Http\Responses\Filament;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        $user = Filament::auth()->user();

        if ($user->isSuperAdmin()) {
            return redirect()->to('/admin/superadmin');
        }

        if ($user->isAdminDinas()) {
            return redirect()->to('/admin/dinas');
        }

        if ($user->hasRole('operator') && $user->sekolah) {
            $jenjang = strtolower($user->sekolah->jenjang);
            $id = $user->sekolah->getRouteKey();
            return redirect()->to("/admin/{$jenjang}/{$id}");
        }

        return redirect()->to(Filament::getLoginUrl());
    }
}
