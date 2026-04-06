<?php

namespace App\Filament\Pages\Auth;

use App\Models\Sekolah;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse;
use Filament\Notifications\Notification;
use Filament\Auth\Pages\Register as BaseRegister;
use Illuminate\Auth\Events\Registered;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;

class CustomRegister extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                TextInput::make('nohp')
                    ->label('No. Handphone')
                    ->required()
                    ->tel()
                    ->maxLength(255),
                Select::make('sekolah_id')
                    ->label('Asal Sekolah')
                    ->options(Sekolah::query()
                        ->whereNull('user_id')
                        ->where(function ($query) {
                            $query->where('nama_sekolah', 'ilike', '%sma%')
                                ->orWhere('nama_sekolah', 'ilike', '%smk%');
                        })
                        ->pluck('nama_sekolah', 'id'))
                    ->required()
                    ->searchable(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        $sekolahId = $data['sekolah_id'];
        unset($data['sekolah_id']);

        $user = $this->handleRegistration($data);

        // Set role and verification status
        $user->update([
            'role' => 'operator',
            'is_verified' => false,
        ]);

        // Link user to school
        Sekolah::find($sekolahId)?->update(['user_id' => $user->id]);

        event(new Registered($user));

        // Skip automatic login after registration
        // Filament::auth()->login($user);

        Notification::make()
            ->title('Pendaftaran Berhasil')
            ->body('Akun Anda telah berhasil didaftarkan. Harap tunggu verifikasi dari Admin Dinas sebelum Anda dapat masuk ke panel.')
            ->success()
            ->persistent()
            ->send();

        // Redirect to login page using the correct RegistrationResponse contract
        return new class implements RegistrationResponse {
            public function toResponse($request)
            {
                return redirect()->route('filament.dinas.auth.login');
            }
        };
    }
}
