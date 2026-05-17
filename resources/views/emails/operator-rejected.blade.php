<x-mail::message>
# Pemberitahuan Status Akun Operator

Halo **{{ $user->name }}**,

Kami ingin menginformasikan mengenai status pendaftaran/akun Anda di **SISDIK BINTUNI**.

Mohon maaf, permohonan pengaktifan akun Anda saat ini **ditangguhkan, ditolak, atau dinonaktifkan** oleh Admin Dinas Pendidikan, Pemuda, dan Olahraga Kabupaten Teluk Bintuni.

Berikut adalah rincian akun Anda:

<x-mail::panel>
**Detail Akun Operator:**
- **Nama Lengkap:** {{ $user->name }}
- **Email (Username):** {{ $user->email }}
- **Asal Sekolah:** {{ $user->sekolah?->nama ?? '-' }}
- **Status Akun:** ❌ Tidak Aktif / Ditolak
</x-mail::panel>

Apabila Anda merasa ada kekeliruan, atau jika Anda ingin mengonfirmasi dan meminta penjelasan lebih lanjut mengenai penonaktifan ini, silakan hubungi **Admin Dinas Pendidikan, Pemuda, dan Olahraga Kabupaten Teluk Bintuni** secara langsung atau melalui saluran komunikasi resmi.

Terima kasih atas perhatian dan kerja sama Anda.

Salam hormat,<br>
**{{ config('app.name', 'DIKPORABINTUNI.COM') }}**
</x-mail::message>
