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
    use HasCustomLogo;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                TextInput::make('nohp')
                    ->label('Nomor WA')
                    ->required()
                    ->tel()
                    ->maxLength(255),
                Select::make('sekolah_id')
                    ->label('Asal Sekolah')
                    ->options(Sekolah::query()
                        // ->whereNotIn('id', \App\Models\OperatorSekolah::pluck('sekolah_id'))
                        // ->where(function ($query) {
                        //     $query->where('nama', 'ilike', '%sma%')
                        //         ->orWhere('nama', 'ilike', '%smk%');
                        // })
                        ->pluck('nama', 'id'))
                    ->required()
                    ->searchable(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                \Filament\Schemas\Components\View::make('filament.components.captcha'),
                \Filament\Forms\Components\Hidden::make('captcha_token')
                    ->extraAttributes(['data-captcha-token' => 'true'])
                    ->required()
                    ->rule(new \App\Rules\Captcha()),
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
        $user->assignRole('operator');
        $user->update([
            'status' => 'pending',
        ]);

        // Link user to school via operator_sekolah pivot table
        \App\Models\OperatorSekolah::create([
            'user_id'    => $user->id,
            'sekolah_id' => $sekolahId,
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OperatorRegistered($user));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal mengirim email registrasi operator: ' . $e->getMessage());
        }

        // Kirim notifikasi ke semua Admin Dinas
        try {
            $adminDinasList = \App\Models\User::role('admin_dinas')->get();
            foreach ($adminDinasList as $admin) {
                \Illuminate\Support\Facades\Mail::to($admin->email)
                    ->send(new \App\Mail\AdminNewOperator($user));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal mengirim email notifikasi ke admin dinas: ' . $e->getMessage());
        }

        event(new Registered($user));

        // Skip automatic login after registration
        // Filament::auth()->login($user);

        // Tampilkan modal sukses di tengah halaman
        $this->mountAction('registrationSuccess');

        return null;
    }

    public function registrationSuccessAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('registrationSuccess')
            ->modalHeading('Pendaftaran Berhasil! 🎉')
            ->modalIcon('heroicon-o-check-circle')
            ->modalIconColor('success')
            ->modalWidth('md')
            ->modalDescription(
                'Akun Anda telah berhasil didaftarkan. Harap tunggu verifikasi dari Admin Dinas sebelum Anda dapat masuk ke panel.'
            )
            ->modalSubmitActionLabel('Mengerti, ke Halaman Login')
            ->modalCancelAction(false)
            ->closeModalByClickingAway(false)
            ->action(fn () => $this->redirect(route('filament.dinas.auth.login')));
    }

    public function getSubheading(): string | \Illuminate\Contracts\Support\Htmlable | null
    {
        return null;
    }
}
