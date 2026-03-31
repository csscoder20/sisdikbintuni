<x-mail::message>
# Akun Terverifikasi

Halo {{ $user->name }},

Selamat! Akun Anda telah berhasil diverifikasi oleh Admin Dinas. Sekarang Anda sudah dapat masuk ke sistem menggunakan email dan password yang telah Anda daftarkan.

<x-mail::button :url="config('app.url') . '/admin'">
Login ke Sistem
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
