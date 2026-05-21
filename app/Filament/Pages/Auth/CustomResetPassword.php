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
            $this->getRateLimitedNotification($exception)?->send();

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
            session()->flash('swal_message', [
                'title' => 'Atur Ulang Kata Sandi',
                'text' => 'Kata sandi Anda telah berhasil diatur ulang.',
                'icon' => 'success',
            ]);

            $this->redirect(filament()->getLoginUrl());
            return null;
        }

        Notification::make()
            ->title(__($status))
            ->danger()
            ->send();

        return null;
    }
}
