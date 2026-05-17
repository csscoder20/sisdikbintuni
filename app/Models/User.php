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

    protected static function booted()
    {
        static::updated(function ($user) {
            if (app()->runningInConsole()) {
                return;
            }

            if ($user->isDirty('status')) {
                $oldStatus = $user->getOriginal('status');
                $newStatus = $user->status;

                if ($newStatus === 'active' && $oldStatus !== 'active') {
                    // Send WhatsApp if phone number exists
                    if ($user->nohp) {
                        try {
                            \App\Services\ZenzivaService::sendWhatsApp(
                                $user->nohp,
                                "Halo {$user->name}, akun Anda di Sisdik Bintuni telah AKTIF. Silakan masuk ke sistem menggunakan email dan password Anda. Terima kasih."
                            );
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::warning('Gagal mengirim WA verifikasi: ' . $e->getMessage());
                        }
                    }

                    // Send Email
                    try {
                        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OperatorVerified($user));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::warning('Gagal mengirim email verifikasi: ' . $e->getMessage());
                    }
                } elseif ($newStatus === 'rejected' && $oldStatus !== 'rejected') {
                    // Send WhatsApp if phone number exists
                    if ($user->nohp) {
                        try {
                            \App\Services\ZenzivaService::sendWhatsApp(
                                $user->nohp,
                                "Halo {$user->name}, mohon maaf, permohonan pengaktifan akun Anda di Sisdik Bintuni DITOLAK atau dinonaktifkan oleh Admin Dinas. Silakan hubungi admin untuk informasi lebih lanjut."
                            );
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::warning('Gagal mengirim WA penolakan: ' . $e->getMessage());
                        }
                    }

                    // Send Email
                    try {
                        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OperatorRejected($user));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::warning('Gagal mengirim email penolakan: ' . $e->getMessage());
                    }
                }
            }
        });
    }

    protected $fillable = [
        'name',
        'email',
        'nohp',
        'password',
        'status',
        'sekolah_id',
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

    public function getFilamentAvatarUrl(): ?string
    {
        // === Gunakan logo sekolah jika tersedia (khusus operator) ===
        $logoPath = $this->sekolah?->logo;
        if ($logoPath) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($logoPath);
        }

        // === Fallback: Identicon Generator — GitHub-style, 100% mandiri, tanpa DB, tanpa storage, tanpa internet ===
        $seed = $this->email ?? $this->name ?? 'user';
        $hash = md5(strtolower(trim($seed)));

        // Warna foreground dari byte pertama hash (dipastikan cukup jenuh & gelap)
        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));
        // Kurangi kecerahan agar kontras di background terang
        $r = (int) ($r * 0.6 + 20);
        $g = (int) ($g * 0.6 + 20);
        $b = (int) ($b * 0.6 + 20);
        $fgColor = sprintf('#%02x%02x%02x', min(200, $r), min(200, $g), min(200, $b));

        // Warna background — versi muda dari foreground
        $bgR = (int) ($r * 0.2 + 230);
        $bgG = (int) ($g * 0.2 + 230);
        $bgB = (int) ($b * 0.2 + 230);
        $bgColor = sprintf('#%02x%02x%02x', min(255, $bgR), min(255, $bgG), min(255, $bgB));

        // === Grid 5x5 simetris kiri-kanan (seperti GitHub identicon) ===
        $gridSize   = 5;
        $cellPx     = 16;          // piksel per sel
        $padding    = 12;          // padding dalam SVG
        $innerSize  = $gridSize * $cellPx;
        $totalSize  = $innerSize + $padding * 2;   // = 80 + 24 = 104
        $cornerRadius = (int) ($totalSize / 2);    // lingkaran penuh

        $rects = '';
        for ($row = 0; $row < $gridSize; $row++) {
            for ($col = 0; $col < (int) ceil($gridSize / 2); $col++) {
                // Ambil bit dari hash — setiap karakter hex = 0–15
                $hashIndex = $row * (int) ceil($gridSize / 2) + $col;
                $bit       = hexdec($hash[$hashIndex % strlen($hash)]) % 2;

                if ($bit === 1) {
                    $x         = $padding + $col * $cellPx;
                    $y         = $padding + $row * $cellPx;
                    $mirrorCol = $gridSize - 1 - $col;
                    $mx        = $padding + $mirrorCol * $cellPx;

                    $rects .= "<rect x=\"$x\" y=\"$y\" width=\"$cellPx\" height=\"$cellPx\" fill=\"$fgColor\"/>";
                    if ($col !== $mirrorCol) {
                        $rects .= "<rect x=\"$mx\" y=\"$y\" width=\"$cellPx\" height=\"$cellPx\" fill=\"$fgColor\"/>";
                    }
                }
            }
        }

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {$totalSize} {$totalSize}" width="{$totalSize}" height="{$totalSize}">
  <rect width="{$totalSize}" height="{$totalSize}" rx="{$cornerRadius}" fill="{$bgColor}"/>
  {$rects}
</svg>
SVG;

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function getSekolahIdAttribute()
    {
        return $this->operatorSekolah?->sekolah_id;
    }

    public function setSekolahIdAttribute($value)
    {
        if ($value) {
            $this->operatorSekolah()->updateOrCreate([], ['sekolah_id' => $value]);
        } else {
            $this->operatorSekolah()?->delete();
        }
    }
}