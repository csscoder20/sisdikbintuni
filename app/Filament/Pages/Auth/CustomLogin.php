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

            session()->flash('modal_alert', 'rejected');

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

            session()->flash('modal_alert', 'pending');

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

    public function mount(): void
    {
        parent::mount();

        if (session()->has('modal_alert')) {
            $type = session()->pull('modal_alert');
            $this->mountAction($type === 'pending' ? 'pendingAlert' : 'rejectedAlert');
        }
    }

    public function pendingAlertAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('pendingAlert')
            ->modalHeading('Verifikasi Tertunda')
            ->modalIcon('heroicon-o-clock')
            ->modalIconColor('warning')
            ->modalWidth('md')
            ->modalDescription(
                'Akun Anda sedang diverifikasi oleh Admin Dinas. Silakan cek email Anda untuk mendapatkan update terkait akun Anda.'
            )
            ->modalSubmitActionLabel('Mengerti')
            ->modalCancelAction(false)
            ->closeModalByClickingAway(false)
            ->action(fn () => null);
    }

    public function rejectedAlertAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('rejectedAlert')
            ->modalHeading('Akun Dinonaktifkan')
            ->modalIcon('heroicon-o-x-circle')
            ->modalIconColor('danger')
            ->modalWidth('md')
            ->modalDescription(
                'Akun Anda telah dinonaktifkan oleh administrator. Silakan hubungi Admin Dinas untuk informasi lebih lanjut.'
            )
            ->modalSubmitActionLabel('Mengerti')
            ->modalCancelAction(false)
            ->closeModalByClickingAway(false)
            ->action(fn () => null);
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
}


