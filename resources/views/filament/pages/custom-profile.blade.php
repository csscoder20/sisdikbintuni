<x-filament-panels::page>
    @php
        $user = auth()->user();
        $role = $user->roles->first()?->name ?? 'user';
        $roleLabel = match($role) {
            'super_admin' => 'Super Admin',
            'admin_dinas' => 'Admin Dinas',
            'operator'    => 'Operator Sekolah',
            default       => ucfirst($role),
        };
        $avatarUrl   = $user->getFilamentAvatarUrl();
        $sekolahNama = $user->sekolah?->nama ?? null;
    @endphp

    <style>
        .profil-wrapper {
            display: grid;
            grid-template-columns: 240px minmax(0, 1fr);
            gap: 1.5rem;
            align-items: start;
            width: 100%;
        }
        .profil-wrapper > * { min-width: 0; }
        @media (max-width: 900px) {
            .profil-wrapper { grid-template-columns: 1fr; }
        }

        /* Kartu kiri */
        .profil-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            padding: 1.5rem 1rem;
            text-align: center;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
        }
        .profil-avatar {
            width: 80px; height: 80px;
            border-radius: 50%; object-fit: cover;
            margin: 0 auto .75rem; display: block;
            border: 3px solid #16a34a;
        }
        .profil-name  { font-size: 1rem; font-weight: 700; color: #111827; margin: 0 0 .2rem; }
        .profil-badge {
            display: inline-block; padding: .15rem .65rem;
            background: #dcfce7; color: #15803d;
            border-radius: 9999px; font-size: .72rem; font-weight: 600; margin-bottom: .75rem;
        }
        .profil-meta  { font-size: .78rem; color: #6b7280; margin: .25rem 0; word-break: break-all; }
        .profil-divider { border: none; border-top: 1px solid #e5e7eb; margin: .75rem 0; }

        /* Kartu form */
        .profil-forms { display: flex; flex-direction: column; gap: 1.25rem; }
        .profil-section {
            background: #fff; border: 1px solid #e5e7eb;
            border-radius: 1rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
        }
        .profil-section-header {
            display: flex; align-items: center; gap: .5rem;
            padding: .75rem 1.25rem; border-bottom: 1px solid #e5e7eb;
            font-weight: 700; font-size: .875rem; color: #374151;
            border-radius: 1rem 1rem 0 0;
        }
        .profil-section-header svg { color: #16a34a; }
        .profil-section-body { padding: 1.25rem 1.25rem 1.5rem; }

        /* 3-kolom grid untuk fields */
        .profil-fields-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .875rem;
        }
        @media (max-width: 700px) {
            .profil-fields-grid { grid-template-columns: 1fr; }
        }

        /* Field */
        .profil-label {
            display: block; font-size: .775rem; font-weight: 600;
            color: #374151; margin-bottom: .3rem;
        }
        .profil-input {
            width: 100%; padding: .45rem .75rem;
            border: 1px solid #d1d5db; border-radius: .5rem;
            font-size: .875rem; color: #111827; background: #f9fafb;
            transition: border-color .2s, box-shadow .2s;
            outline: none; box-sizing: border-box;
        }
        .profil-input:focus {
            border-color: rgb(var(--primary-500));
            box-shadow: 0 0 0 3px rgba(var(--primary-500),.15);
            background: #fff;
        }
        .profil-error { font-size: .72rem; color: #ef4444; margin-top: .2rem; }

        /* Tombol */
        .profil-actions { display: flex; justify-content: flex-end; margin-top: 1rem; }
        .profil-btn {
            display: inline-flex !important; align-items: center; gap: .4rem;
            padding: .45rem 1.1rem;
            background: #16a34a !important; color: #fff !important;
            border: none; border-radius: .5rem;
            font-size: .85rem; font-weight: 600;
            cursor: pointer; transition: background .2s;
            text-decoration: none;
        }
        .profil-btn:hover { background: #15803d !important; }
        .profil-btn svg { width: 14px; height: 14px; }
    </style>

    <div class="profil-wrapper">

        {{-- ── Kartu Kiri ── --}}
        <div class="profil-card">
            <img src="{{ $avatarUrl }}" alt="Avatar" class="profil-avatar">
            <p class="profil-name">{{ $user->name }}</p>
            <span class="profil-badge">{{ $roleLabel }}</span>
            <hr class="profil-divider">
            <p class="profil-meta">✉ {{ $user->email }}</p>
            @if($user->nohp)
                <p class="profil-meta">📱 {{ $user->nohp }}</p>
            @endif
            @if($sekolahNama)
                <p class="profil-meta">🏫 {{ $sekolahNama }}</p>
            @endif
            <hr class="profil-divider">
            <p style="font-size:.7rem; color:#9ca3af;">
                Bergabung {{ $user->created_at?->locale('id')->isoFormat('D MMM YYYY') }}
            </p>
        </div>

        {{-- ── Kartu Kanan ── --}}
        <div class="profil-forms">

            {{-- Informasi Akun --}}
            <div class="profil-section">
                <div class="profil-section-header">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Akun
                </div>
                <div class="profil-section-body">
                    <div>
                        <div class="profil-fields-grid">
                            <div>
                                <label class="profil-label" for="p-name">Nama Lengkap</label>
                                <input id="p-name" type="text" class="profil-input"
                                       wire:model="profil_name" placeholder="Nama lengkap">
                                @error('profil_name') <p class="profil-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="profil-label" for="p-email">Email</label>
                                <input id="p-email" type="email" class="profil-input"
                                       wire:model="profil_email" placeholder="Alamat email">
                                @error('profil_email') <p class="profil-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="profil-label" for="p-nohp">Nomor HP / WhatsApp</label>
                                <input id="p-nohp" type="text" class="profil-input"
                                       wire:model="profil_nohp" placeholder="08xxxxxxxxxx">
                                @error('profil_nohp') <p class="profil-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ganti Password --}}
            <div class="profil-section">
                <div class="profil-section-header">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Ganti Password
                </div>
                <div class="profil-section-body">
                    <div>
                        <div class="profil-fields-grid">
                            <div>
                                <label class="profil-label" for="pw-cur">Password Lama</label>
                                <input id="pw-cur" type="password" class="profil-input"
                                       wire:model="pwd_current" placeholder="Password lama">
                                @error('pwd_current') <p class="profil-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="profil-label" for="pw-new">Password Baru</label>
                                <input id="pw-new" type="password" class="profil-input"
                                       wire:model="pwd_new" placeholder="Min. 8 karakter">
                                @error('pwd_new') <p class="profil-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="profil-label" for="pw-con">Konfirmasi Password</label>
                                <input id="pw-con" type="password" class="profil-input"
                                       wire:model="pwd_confirm" placeholder="Ulangi password baru">
                                @error('pwd_confirm') <p class="profil-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Simpan Tunggal --}}
            <div style="display:flex; justify-content:flex-end;">
                <button type="button" wire:click="saveAll" class="profil-btn" style="padding:.6rem 2rem; font-size:.9rem;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Pengaturan
                </button>
            </div>

        </div>
    </div>

</x-filament-panels::page>
