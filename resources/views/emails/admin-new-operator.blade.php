<x-mail::message>
# 🔔 Pendaftaran Operator Baru Memerlukan Verifikasi

Halo **Admin Dinas**,

Terdapat pendaftaran akun operator baru yang memerlukan tindakan verifikasi dari Anda. Berikut adalah detail pendaftar:

<x-mail::panel>
**Detail Operator Baru:**
- **Nama Lengkap:** {{ $operator->name }}
- **Email:** {{ $operator->email }}
- **Nomor WhatsApp:** {{ $operator->nohp ?? '-' }}
- **Asal Sekolah:** {{ $operator->sekolah?->nama ?? '-' }}
- **Waktu Daftar:** {{ now()->timezone('Asia/Jayapura')->format('d M Y, H:i') }} WIT
- **Status:** ⏳ Menunggu Verifikasi
</x-mail::panel>

Silakan segera masuk ke panel Admin Dinas untuk meninjau dan memverifikasi akun operator ini.

<x-mail::button :url="config('app.url') . '/admin/dinas'" color="success">
Verifikasi Sekarang
</x-mail::button>

> **Catatan:** Jika Anda tidak mengambil tindakan, operator tersebut tidak akan dapat mengakses sistem hingga akun diverifikasi.

Terima kasih,<br>
**{{ config('app.name', 'DIKPORABINTUNI.COM') }}**
</x-mail::message>
