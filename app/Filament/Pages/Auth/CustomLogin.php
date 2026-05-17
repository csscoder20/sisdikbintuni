<?php

namespace App\Filament\Pages\Auth;

use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Illuminate\Validation\ValidationException;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\RenderHook;
use Filament\View\PanelsRenderHook;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;

class CustomLogin extends BaseLogin
{
    use HasCustomLogo;

    public function authenticate(): ?LoginResponse

    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        try {
            \Illuminate\Support\Facades\Validator::make(
                ['captcha_token' => $this->form->getState()['captcha_token'] ?? null],
                ['captcha_token' => ['required', new \App\Rules\Captcha]],
                ['captcha_token.required' => 'Selesaikan captcha terlebih dahulu.']
            )->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            Notification::make()
                ->title('Gagal Validasi Captcha')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

        // 1. Cek jika akun dinonaktifkan (Rejected) untuk semua role
        if ($user && $user->status === 'rejected') {
            Filament::auth()->logout();

            session()->flash('swal_message', [
                'title' => 'Akun Dinonaktifkan',
                'text' => 'Akun Anda telah dinonaktifkan oleh administrator. Silakan hubungi Admin Dinas untuk informasi lebih lanjut.',
                'icon' => 'error',
            ]);

            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    return redirect()->to(filament()->getLoginUrl());
                }
            };
        }

        // 2. Cek jika operator belum diverifikasi (Pending)
        if ($user && $user->hasRole('operator') && $user->status === 'pending') {
            Filament::auth()->logout();

            session()->flash('swal_message', [
                'title' => 'Verifikasi Tertunda',
                'text' => 'Akun Anda sedang diverifikasi oleh Admin Dinas. Silakan cek email Anda untuk mendapatkan update terkait akun Anda.',
                'icon' => 'warning',
            ]);

            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    return redirect()->to(filament()->getLoginUrl());
                }
            };
        }

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    public function getSubheading(): string | Htmlable | null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return __('filament-panels::auth/pages/login.multi_factor.subheading');
        }

        return null;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                \Filament\Schemas\Components\View::make('filament.components.captcha'),
                \Filament\Forms\Components\Hidden::make('captcha_token')
                    ->extraAttributes(['data-captcha-token' => 'true'])
                    ->required(),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                RenderHook::make(PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE),
                $this->getFormContentComponent(),
                $this->getMultiFactorChallengeFormContentComponent(),
                RenderHook::make(PanelsRenderHook::AUTH_LOGIN_FORM_AFTER),
                \Filament\Schemas\Components\Html::make(function () {
                    if (session()->has('swal_message')) {
                        $swal = session('swal_message');
                        $title = addslashes($swal['title']);
                        $text = addslashes($swal['text']);
                        $icon = addslashes($swal['icon']);
                        
                        return '
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            setTimeout(() => {
                                if (typeof Swal !== "undefined") {
                                    Swal.fire({
                                        title: "<span style=\"font-size: 1.125rem; font-weight: 600; color: #111827;\">" + "' . $title . '" + "</span>",
                                        html: "<span style=\"font-size: 0.875rem; color: #4b5563;\">" + "' . $text . '" + "</span>",
                                        icon: "' . $icon . '",
                                        confirmButtonText: "OK",
                                        confirmButtonColor: "#ea580c"
                                    });
                                }
                            }, 500);
                        </script>';
                    }
                    return '';
                }),
            ]);
    }
}


