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
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

        if ($user && $user->hasRole('operator') && $user->status === 'pending') {
            Filament::auth()->logout();

            Notification::make()
                ->title('Verifikasi Tertunda')
                ->body('Akun Anda sedang diverifikasi oleh Admin Dinas. Silakan cek email Anda untuk mendapatkan update terkait akun Anda.')
                ->danger()
                ->persistent()
                ->send();

            return null;
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

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                RenderHook::make(PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE),
                $this->getFormContentComponent(),
                $this->getMultiFactorChallengeFormContentComponent(),
                RenderHook::make(PanelsRenderHook::AUTH_LOGIN_FORM_AFTER),
            ]);
    }

    protected function getRegisterLinkComponent(): Component
    {
        return RenderHook::make('panels::auth.login.form.after')
            ->content(
                fn(): string => filament()->hasRegistration()
                    ? '<div style="text-align: center; margin-top: 1.5rem;"><a href="' . filament()->getRegistrationUrl() . '" class="text-sm" style="color: inherit; text-decoration: none;"><span style="color: #f97316;">or</span> ' . __('filament-panels::auth/pages/login.actions.register.label') . '</a></div>'
                    : ''
            );
    }
}
