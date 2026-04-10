<?php

namespace App\Http\Middleware\Filament;

use Closure;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIncorrectPanel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Filament::auth()->user();
        $panel = Filament::getCurrentPanel();

        // If not logged in, let the default Filament authentication handle it
        if (! $user || ! $panel) {
            return $next($request);
        }

        // 1. If Operator tries to access Dinas panel (unless they are on the login/register/logout/profile pages)
        if ($panel->getId() === 'dinas' && $user->hasRole('operator')) {
            // Check if it's an internal page, not auth pages. 
            // In Filament v3, we can check if it's the dashboard or a resource.
            // Actually, any internal access should be restricted.
            $path = $request->path();
            if (! str_contains($path, 'login') && ! str_contains($path, 'register') && ! str_contains($path, 'logout')) {
                Notification::make()
                    ->title('Maaf, Anda tidak diizinkan untuk mengakses url ini')
                    ->danger()
                    ->persistent()
                    ->send();

                $sekolah = $user->sekolah;
                $jenjang = $sekolah?->jenjang ?? 'sma';
                $id = $sekolah?->getRouteKey();
                
                if ($id) {
                    return redirect()->to("/admin/{$jenjang}/{$id}");
                }
                
                return redirect()->to('/admin/login');
            }
        }

        // 2. If Admin tries to access a School panel
        if ($panel->getId() !== 'dinas' && ! $user->hasRole('operator')) {
            Notification::make()
                ->title('Dialihkan ke Dashboard Admin Dinas')
                ->info()
                ->send();

            return redirect()->to('/admin/dinas');
        }

        // 3. If Operator tries to access a School panel that does NOT match their jenjang
        if ($user->hasRole('operator') && in_array($panel->getId(), ['sma', 'smk'])) {
            $expectedJenjang = $user->sekolah?->jenjang ?? 'sma';
            
            if ($panel->getId() !== $expectedJenjang) {
                // Determine the correct redirect URL
                $id = $user->sekolah?->getRouteKey();
                
                // Show notification to user that they were redirected
                Notification::make()
                    ->title('Maaf, Anda hanya bisa mengakses panel ' . strtoupper($expectedJenjang))
                    ->warning()
                    ->send();
                    
                if ($id) {
                    return redirect()->to("/admin/{$expectedJenjang}/{$id}");
                }
            }
        }

        return $next($request);
    }
}
