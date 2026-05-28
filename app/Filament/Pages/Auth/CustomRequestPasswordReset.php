<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions;
use Filament\Support\Enums\Alignment;
use Filament\Schemas\Components\RenderHook;
use Filament\View\PanelsRenderHook;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Support\Facades\Password;
use Filament\Facades\Filament;

class CustomRequestPasswordReset extends BaseRequestPasswordReset
{
    use HasCustomLogo;

    public function getSubheading(): string | Htmlable | null
    {
        return null;
    }

    public function getTitle(): string | Htmlable
    {
        return 'Lupa Kata Sandi';
    }

    public function getHeading(): string | Htmlable
    {
        return 'Atur Ulang Kata Sandi';
    }

    public function request(): void
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->mountAction('rateLimitedError', ['seconds' => ceil($exception->secondsUntilAvailable)]);

            return;
        }

        $data = $this->form->getState();

        // Send reset link using the custom callback to ensure Filament reset URL is generated
        Password::broker(Filament::getAuthPasswordBroker())->sendResetLink(
            $this->getCredentialsFromFormData($data),
            function ($user, string $token): void {
                if (
                    ($user instanceof \Filament\Models\Contracts\FilamentUser) &&
                    (! $user->canAccessPanel(Filament::getCurrentOrDefaultPanel()))
                ) {
                    return;
                }

                $notification = new \App\Notifications\IndonesianResetPasswordNotification($token, Filament::getResetPasswordUrl($token, $user));

                $user->notify($notification);

                if (class_exists(\Illuminate\Auth\Events\PasswordResetLinkSent::class)) {
                    event(new \Illuminate\Auth\Events\PasswordResetLinkSent($user));
                }
            },
        );

        // Tampilkan modal sukses
        $this->mountAction('resetEmailSent');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                RenderHook::make(PanelsRenderHook::AUTH_PASSWORD_RESET_REQUEST_FORM_BEFORE),
                $this->getFormContentComponent(),
                RenderHook::make(PanelsRenderHook::AUTH_PASSWORD_RESET_REQUEST_FORM_AFTER),
                Actions::make([
                    $this->loginAction,
                ])->alignment(Alignment::Center),
            ]);
    }

    public function resetEmailSentAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('resetEmailSent')
            ->modalHeading('Tautan Terkirim')
            ->modalIcon('heroicon-o-check-circle')
            ->modalIconColor('success')
            ->modalAlignment(\Filament\Support\Enums\Alignment::Center)
            ->modalWidth('md')
            ->modalDescription(
                'Kami telah mengirimkan tautan atur ulang kata sandi ke email Anda. Jika akun Anda terdaftar, Anda akan segera menerima email.'
            )
            ->modalSubmitActionLabel('Mengerti, ke Halaman Login')
            ->modalCancelAction(false)
            ->closeModalByClickingAway(false)
            ->action(fn () => $this->redirect(filament()->getLoginUrl()));
    }

    public function rateLimitedErrorAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('rateLimitedError')
            ->modalHeading('Terlalu banyak permintaan')
            ->modalIcon('heroicon-o-exclamation-triangle')
            ->modalIconColor('danger')
            ->modalAlignment(\Filament\Support\Enums\Alignment::Center)
            ->modalWidth('md')
            ->modalDescription(fn (array $arguments) => 'Silakan coba lagi dalam ' . ($arguments['seconds'] ?? 'beberapa') . ' detik.')
            ->modalSubmitActionLabel('Mengerti')
            ->modalCancelAction(false)
            ->closeModalByClickingAway(false)
            ->action(fn () => null);
    }

    public function loginAction(): \Filament\Actions\Action
    {
        return parent::loginAction()
            ->label('Kembali ke halaman Login');
    }
}
