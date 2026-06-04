<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;


use Filament\Models\Contracts\HasAvatar;

class User extends Authenticatable implements HasTenants, FilamentUser, HasAvatar
{
    use SoftDeletes;
    use HasRoles, \App\Traits\HasActivityLog, Notifiable;

    public ?int $sekolah_id_temp = null;
    public bool $sekolah_id_temp_set = false;

    protected static function booted()
    {
        static::saved(function ($user) {
            if ($user->sekolah_id_temp_set) {
                $user->sekolah_id_temp_set = false;
                if ($user->sekolah_id_temp) {
                    $user->operatorSekolah()->updateOrCreate([], ['sekolah_id' => $user->sekolah_id_temp]);
                } else {
                    $user->operatorSekolah()?->delete();
                }
            }
        });

        static::updated(function ($user) {
            if (app()->runningInConsole()) {
                return;
            }

            if ($user->isDirty('status')) {
                $oldStatus = $user->getOriginal('status');
                $newStatus = $user->status;

                if ($newStatus === 'active' && $oldStatus !== 'active') {
                    // Send Email
                    try {
                        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OperatorVerified($user));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::warning('Gagal mengirim email verifikasi: ' . $e->getMessage());
                    }

                    // Sinkronisasi status di operator_sekolah
                    $user->operatorSekolah()?->update(['status' => 'approved']);
                } elseif ($newStatus === 'rejected' && $oldStatus !== 'rejected') {
                    // Send Email
                    try {
                        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OperatorRejected($user));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::warning('Gagal mengirim email penolakan: ' . $e->getMessage());
                    }

                    // Sinkronisasi status di operator_sekolah
                    $user->operatorSekolah()?->update(['status' => 'rejected']);
                }
            }
        });
    }

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'nohp',
        'password',
        'status',
        'sekolah_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'sekolah_id',
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

    public function isPengawas()
    {
        return $this->hasRole('pengawas');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin');
    }

    public function getTenants(Panel $panel): array|Collection
    {
        if ($this->hasRole(['super_admin', 'admin_dinas', 'pengawas'])) {
            return Sekolah::all();
        }

        return $this->sekolah ? collect([$this->sekolah]) : collect();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->hasRole(['super_admin', 'admin_dinas', 'pengawas'])) {
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

    public function getFilamentAvatarUrl(): ?string
    {
        // === Gunakan logo sekolah jika tersedia (khusus operator) ===
        $logoPath = $this->sekolah?->logo;
        if ($logoPath) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($logoPath);
        }

        // === Fallback: Static User Icon ===
        return asset('assets/icon/user.png');
    }

    public function getSekolahIdAttribute()
    {
        if ($this->sekolah_id_temp_set) {
            return $this->sekolah_id_temp;
        }
        return $this->operatorSekolah?->sekolah_id;
    }

    public function setSekolahIdAttribute($value)
    {
        $this->sekolah_id_temp = $value ? (int) $value : null;
        $this->sekolah_id_temp_set = true;
    }
}
