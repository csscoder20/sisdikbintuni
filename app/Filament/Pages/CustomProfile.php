<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomProfile extends Page
{
    protected string $view = 'filament.pages.custom-profile';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'pengaturan-akun';

    public static function getNavigationLabel(): string
    {
        return 'Pengaturan Akun';
    }

    public function getTitle(): string
    {
        return 'Pengaturan Akun';
    }

    // ── Form state ───────────────────────────────────────────────
    public string $profil_name  = '';
    public string $profil_email = '';
    public string $profil_nohp  = '';

    public string $pwd_current = '';
    public string $pwd_new     = '';
    public string $pwd_confirm = '';

    public function mount(): void
    {
        $user               = Auth::user();
        $this->profil_name  = $user->name  ?? '';
        $this->profil_email = $user->email ?? '';
        $this->profil_nohp  = $user->nohp  ?? '';
    }

    // ── Simpan semua sekaligus ────────────────────────────────────
    public function saveAll(): void
    {
        // Validasi info akun (selalu)
        $this->validate([
            'profil_name'  => ['required', 'string', 'max:255'],
            'profil_email' => ['required', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'profil_nohp'  => ['nullable', 'string', 'max:20'],
        ], [
            'profil_name.required'  => 'Nama wajib diisi.',
            'profil_email.required' => 'Email wajib diisi.',
            'profil_email.email'    => 'Format email tidak valid.',
            'profil_email.unique'   => 'Email sudah digunakan oleh akun lain.',
        ]);

        $user        = Auth::user();
        $user->name  = $this->profil_name;
        $user->email = $this->profil_email;
        $user->nohp  = $this->profil_nohp;

        // Ubah password hanya jika field password diisi
        $passwordChanged = false;
        if (!empty($this->pwd_new)) {
            $this->validate([
                'pwd_current' => ['required'],
                'pwd_new'     => ['required', Password::defaults(), 'same:pwd_confirm'],
                'pwd_confirm' => ['required'],
            ], [
                'pwd_current.required' => 'Password lama wajib diisi.',
                'pwd_new.same'         => 'Konfirmasi password tidak cocok.',
                'pwd_confirm.required' => 'Konfirmasi password wajib diisi.',
            ]);

            if (!Hash::check($this->pwd_current, $user->password)) {
                $this->addError('pwd_current', 'Password lama tidak sesuai.');
                return;
            }

            $user->password    = Hash::make($this->pwd_new);
            $this->pwd_current = '';
            $this->pwd_new     = '';
            $this->pwd_confirm = '';
            $passwordChanged   = true;
        }

        $user->save();

        Notification::make()
            ->title($passwordChanged
                ? 'Profil dan password berhasil diperbarui!'
                : 'Profil berhasil diperbarui!')
            ->success()
            ->send();
    }
}
