<x-mail::message>
# Pendaftaran Akun Operator Berhasil

Halo **{{ $user->name }}**,

Terima kasih telah mendaftar sebagai Operator Sekolah di **SISDIK BINTUNI**. Pendaftaran akun Anda telah kami terima dan saat ini sedang berada dalam proses peninjauan dan verifikasi oleh Admin Dinas Pendidikan, Pemuda, dan Olahraga Kabupaten Teluk Bintuni.

Berikut adalah rincian akun yang Anda daftarkan:

<x-mail::panel>
**Detail Akun Operator:**
- **Nama Lengkap:** {{ $user->name }}
- **Email (Username):** {{ $user->email }}
- **Nomor WhatsApp:** {{ $user->nohp }}
- **Asal Sekolah:** {{ $user->sekolah?->nama ?? '-' }}
- **Status Akun:** ⏳ Menunggu Verifikasi
</x-mail::panel>

### Langkah Selanjutnya:
1. **Verifikasi oleh Admin Dinas:** Admin Dinas akan memeriksa dan memvalidasi keaslian data akun Anda.
2. **Notifikasi Persetujuan:** Anda akan menerima email pemberitahuan resmi setelah akun Anda diaktifkan.
3. **Akses Masuk:** Setelah diaktifkan, Anda dapat masuk ke panel menggunakan email dan password yang Anda buat saat pendaftaran.

Jika Anda merasa tidak melakukan pendaftaran ini, harap abaikan email ini atau hubungi layanan bantuan kami.

Salam hangat,<br>
**{{ config('app.name', 'DIKPORABINTUNI.COM') }}**
</x-mail::message>
