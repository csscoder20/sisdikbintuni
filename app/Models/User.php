<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;


class User extends Authenticatable implements HasTenants, FilamentUser
{
    use HasRoles, \App\Traits\HasActivityLog, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function operatorSekolah()
    {
        return $this->hasOne(OperatorSekolah::class);
    }

    public function sekolah()
    {
        return $this->hasOneThrough(
            Sekolah::class,
            OperatorSekolah::class,
            'user_id',
            'id',
            'id',
            'sekolah_id'
        );
    }

    public function isOperator()
    {
        return $this->hasRole('operator');
    }

    public function isAdminDinas()
    {
        return $this->hasRole('admin_dinas');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin');
    }

    public function getTenants(Panel $panel): array|Collection
    {
        if ($this->hasRole(['super_admin', 'admin_dinas'])) {
            return Sekolah::all();
        }

        return $this->sekolah ? collect([$this->sekolah]) : collect();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->hasRole(['super_admin', 'admin_dinas'])) {
            return true;
        }

        return $this->sekolah?->id === $tenant->id;
    }

    public function canAccessPanel(Panel $panel): bool
        {
            // OPSI 1: Izinkan SEMUA user yang berhasil login (untuk testing)
            return true;
            
            // OPSI 2: Izinkan user dengan email domain tertentu (RECOMMENDED untuk keamanan)
            // Ganti 'gmail.com' dengan domain email Anda
            // return str_ends_with($this->email, '@gmail.com') && $this->hasVerifiedEmail();
            
            // OPSI 3: Jika Anda punya kolom 'is_admin' di tabel users
            // return $this->is_admin === 1;
            
            // OPSI 4: Izinkan user tertentu berdasarkan email
            // return in_array($this->email, [
            //     'admin@gmail.com',
            //     'operator@gmail.com',
            // ]);
        }
}