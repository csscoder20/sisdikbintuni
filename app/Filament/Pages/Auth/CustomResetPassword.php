<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\PasswordReset\ResetPassword as BaseResetPassword;
use Filament\Auth\Http\Responses\Contracts\PasswordResetResponse;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomResetPassword extends BaseResetPassword
{
    use HasCustomLogo;

    public function resetPassword(): ?PasswordResetResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->mountAction('rateLimitedError', ['seconds' => ceil($exception->secondsUntilAvailable)]);

            return null;
        }

        if ($this->isResetPasswordRateLimited($this->email)) {
            return null;
        }

        $data = $this->form->getState();

        $data['email'] = $this->email;
        $data['token'] = $this->token;

        $hasPanelAccess = true;

        $status = Password::broker(Filament::getAuthPasswordBroker())->reset(
            $this->getCredentialsFromFormData($data),
            function ($user) use ($data, &$hasPanelAccess): void {
                if (
                    ($user instanceof \Filament\Models\Contracts\FilamentUser) &&
                    (! $user->canAccessPanel(Filament::getCurrentOrDefaultPanel()))
                ) {
                    $hasPanelAccess = false;

                    return;
                }

                $user->forceFill([
                    $user->getAuthPasswordName() => Hash::make($data['password']),
                    $user->getRememberTokenName() => Str::random(60),
                ])->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );

        if ($hasPanelAccess === false) {
            $status = Password::INVALID_USER;
        }

        if ($status === Password::PASSWORD_RESET) {
            $this->mountAction('resetSuccess');
            return null;
        }

        $this->mountAction('resetError', ['message' => __($status)]);

        return null;
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

    public function resetSuccessAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('resetSuccess')
            ->modalHeading('Atur Ulang Kata Sandi')
            ->modalIcon('heroicon-o-check-circle')
            ->modalIconColor('success')
            ->modalAlignment(\Filament\Support\Enums\Alignment::Center)
            ->modalWidth('md')
            ->modalDescription('Kata sandi Anda telah berhasil diatur ulang.')
            ->modalSubmitActionLabel('Mengerti, ke Halaman Login')
            ->modalCancelAction(false)
            ->closeModalByClickingAway(false)
            ->action(fn () => $this->redirect(filament()->getLoginUrl()));
    }

    public function resetErrorAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('resetError')
            ->modalHeading('Gagal Mengatur Ulang')
            ->modalIcon('heroicon-o-x-circle')
            ->modalIconColor('danger')
            ->modalAlignment(\Filament\Support\Enums\Alignment::Center)
            ->modalWidth('md')
            ->modalDescription(fn (array $arguments) => $arguments['message'] ?? 'Tautan atau token atur ulang kata sandi ini tidak valid.')
            ->modalSubmitActionLabel('Tutup')
            ->modalCancelAction(false)
            ->closeModalByClickingAway(false)
            ->action(fn () => null);
    }
}
