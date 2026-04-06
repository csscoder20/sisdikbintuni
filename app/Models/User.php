<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nohp',
        'is_verified',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    public function sekolah(): HasOne
    {
        return $this->hasOne(Sekolah::class, 'user_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'dinas') {
            // Allow both admin and operator to access the 'dinas' panel gateway
            // (Operator will be redirected by middleware if they try to access internal pages)
            return in_array($this->role, ['admin', 'operator']);
        }

        // For school panels, check if the user is an operator and is verified
        if ($this->role === 'operator' && $this->is_verified) {
            // Also ensure their school jenjang matches the panel ID
            return $this->sekolah?->jenjang === $panel->getId();
        }

        return false;
    }

    public function getTenants(Panel $panel): Collection
    {
        return collect([$this->sekolah])->filter();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->sekolah?->id === $tenant->id;
    }
}
