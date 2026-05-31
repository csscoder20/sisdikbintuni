<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class HelpGuide extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::QuestionMarkCircle;

    protected string $view = 'filament.pages.help-guide';

    protected static ?string $navigationLabel = 'Panduan Penggunaan';

    protected static ?string $title = 'Panduan Penggunaan';

    protected static ?int $navigationSort = 99;

    protected static string | \UnitEnum | null $navigationGroup = 'Informasi';

    protected static bool $shouldRegisterNavigation = false;

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    public function getHeading(): string
    {
        return 'Panduan Penggunaan Sistem Pelaporan Bulanan';
    }

    public function getBreadcrumbs(): array
    {
        return [
            filament()->getUrl() => 'Dashboard',
            '#' => 'Informasi',
            '' => 'Panduan Penggunaan',
        ];
    }

    public function getGuideAudienceLabel(): string
    {
        return $this->isOperatorUser()
            ? 'Operator Sekolah'
            : 'Administrator';
    }

    public function getGuideIntro(): string
    {
        return $this->isOperatorUser()
            ? 'Panduan ini menampilkan menu yang digunakan operator sekolah untuk mengelola profil, data siswa, data GTK, data sekolah, validasi, dan pelaporan bulanan.'
            : 'Panduan ini menampilkan seluruh menu administrator, termasuk menu monitoring dinas, pengelolaan sistem, cetak laporan, dan seluruh menu operasional sekolah.';
    }

    public function getGuideSections(): array
    {
        if ($this->isOperatorUser()) {
            return [
                [
                    'group' => 'Panduan Operator Sekolah',
                    'items' => $this->getOperatorGuideSections(),
                ],
            ];
        }

        return [
            [
                'group' => 'Panduan Admin',
                'items' => $this->getAdminGuideSections(),
            ],
            [
                'group' => 'Menu Sekolah',
                'items' => $this->getOperatorGuideSections(),
            ],
        ];
    }

    protected function isOperatorUser(): bool
    {
        return auth()->user()?->hasRole('operator') === true
            && ! auth()->user()?->hasRole(['super_admin', 'admin_dinas', 'pengawas']);
    }

    protected function getAdminGuideSections(): array
    {
        return [
            [
                'title' => 'Dasbor Admin Dinas',
                'menu' => 'Dasbor',
                'description' => 'Melihat ringkasan kondisi data satuan pendidikan, progres pelaporan, grafik siswa, GTK, dan sarana prasarana.',
                'steps' => [
                    'Buka menu Dasbor pada panel dinas.',
                    'Gunakan ringkasan dan grafik untuk memantau kondisi sekolah.',
                    'Pilih sekolah jika ingin masuk ke konteks data sekolah tertentu.',
                ],
                'note' => 'Dashboard admin berfungsi sebagai pusat monitoring lintas sekolah.',
            ],
            [
                'title' => 'Data Master Sekolah',
                'menu' => 'Data Master > Sekolah',
                'description' => 'Mengelola daftar sekolah, identitas sekolah, jenjang, NPSN, dan informasi dasar satuan pendidikan.',
                'steps' => [
                    'Buka menu Sekolah.',
                    'Gunakan pencarian atau filter untuk menemukan sekolah.',
                    'Buka detail sekolah untuk melihat atau memperbarui informasi.',
                ],
                'note' => 'Data master sekolah menjadi dasar tenant dan pelaporan tiap operator.',
            ],
            [
                'title' => 'Pengguna',
                'menu' => 'Sistem > Pengguna',
                'description' => 'Mengelola akun admin dan operator, termasuk status verifikasi operator sekolah.',
                'steps' => [
                    'Buka menu Pengguna.',
                    'Periksa role dan status akun.',
                    'Verifikasi, ubah, atau nonaktifkan akun sesuai kebutuhan.',
                ],
                'note' => 'Pastikan operator aktif sudah terhubung ke sekolah yang benar.',
            ],
            [
                'title' => 'Pemberitahuan',
                'menu' => 'Sistem > Pemberitahuan',
                'description' => 'Membuat dan mengirim pemberitahuan kepada operator sekolah.',
                'steps' => [
                    'Buka menu Pemberitahuan.',
                    'Klik buat data baru.',
                    'Tentukan target operator atau sekolah, isi pesan, lalu simpan.',
                ],
                'note' => 'Gunakan pemberitahuan untuk pengumuman batas waktu dan koreksi data.',
            ],
            [
                'title' => 'Log Aktivitas',
                'menu' => 'Sistem > Log Aktivitas',
                'description' => 'Memantau aktivitas pengguna seperti login, logout, penambahan data, perubahan data, dan penghapusan data.',
                'steps' => [
                    'Buka menu Log Aktivitas.',
                    'Gunakan filter pengguna, aktivitas, atau waktu.',
                    'Buka detail log untuk melihat IP address, user agent, dan perubahan data.',
                ],
                'note' => 'Log membantu audit perubahan data dan aktivitas akun.',
            ],
            [
                'title' => 'Progres Pelaporan',
                'menu' => 'Cetak > Progres Pelaporan',
                'description' => 'Melihat status kelengkapan dan progres laporan bulanan setiap sekolah.',
                'steps' => [
                    'Buka menu Progres Pelaporan.',
                    'Cari sekolah yang ingin dipantau.',
                    'Periksa status validasi dan periode laporan.',
                ],
                'note' => 'Menu ini membantu admin menentukan sekolah yang perlu ditindaklanjuti.',
            ],
            [
                'title' => 'Cetak Laporan Bulanan',
                'menu' => 'Cetak > Cetak Laporan Bulanan',
                'description' => 'Mengunduh laporan bulanan sekolah dalam format PDF berdasarkan periode laporan.',
                'steps' => [
                    'Buka menu Cetak Laporan Bulanan.',
                    'Pilih tahun ajaran atau periode yang dibutuhkan.',
                    'Klik tombol PDF pada laporan sekolah yang ingin dicetak.',
                ],
                'note' => 'Gunakan file PDF untuk arsip dan distribusi resmi.',
            ],
            [
                'title' => 'Cetak Custom',
                'menu' => 'Cetak > Cetak Custom',
                'description' => 'Mengunduh dataset tertentu seperti sekolah, siswa, GTK, dan sarpras dalam format Excel atau PDF.',
                'steps' => [
                    'Buka menu Cetak Custom.',
                    'Pilih jenis dataset dan filter yang diperlukan.',
                    'Klik aksi unduh Excel atau PDF.',
                ],
                'note' => 'Cetak custom cocok untuk kebutuhan rekap dan analisis khusus.',
            ],
            [
                'title' => 'Validasi Data Sekolah',
                'menu' => 'Validasi/Verifikasi > Validasi Data',
                'description' => 'Memeriksa kelengkapan data sekolah tertentu saat admin sedang memilih sekolah.',
                'steps' => [
                    'Pilih sekolah dari selector sekolah.',
                    'Buka menu Validasi Data.',
                    'Periksa setiap langkah validasi dan catat bagian yang perlu diperbaiki.',
                ],
                'note' => 'Pada panel dinas, menu ini tampil setelah sekolah dipilih.',
            ],
        ];
    }

    protected function getOperatorGuideSections(): array
    {
        return [
            [
                'title' => 'Dasbor Operator',
                'menu' => 'Dasbor',
                'description' => 'Melihat ringkasan data sekolah, jumlah GTK, siswa, rombel, gedung, riwayat pelaporan, dan log aktivitas operator.',
                'steps' => [
                    'Buka menu Dasbor.',
                    'Periksa ringkasan jumlah data utama.',
                    'Gunakan tabel Riwayat Pelaporan untuk mengunduh PDF laporan.',
                    'Periksa Log User Operator untuk melihat aktivitas akun terbaru.',
                ],
                'note' => 'Dasbor menjadi titik awal untuk memantau kesiapan laporan sekolah.',
            ],
            [
                'title' => 'Profil Sekolah',
                'menu' => 'Data Sekolah > Profil',
                'description' => 'Melengkapi identitas sekolah, alamat, legalitas, rekening, dan informasi pendukung lainnya.',
                'steps' => [
                    'Buka menu Profil.',
                    'Isi atau perbarui data sekolah sesuai dokumen resmi.',
                    'Simpan perubahan setelah seluruh field penting dilengkapi.',
                ],
                'note' => 'Profil sekolah yang lengkap menjadi syarat validasi laporan.',
            ],
            [
                'title' => 'Sarana dan Prasarana',
                'menu' => 'Data Sekolah > Sarana & Prasarana',
                'description' => 'Mencatat gedung, ruang, jumlah kondisi baik, dan jumlah kondisi rusak.',
                'steps' => [
                    'Buka menu Sarana & Prasarana.',
                    'Tambahkan data ruang atau gedung yang dimiliki sekolah.',
                    'Isi kondisi ruang dengan jumlah yang sesuai.',
                    'Simpan data dan perbarui bila ada perubahan kondisi.',
                ],
                'note' => 'Data sarpras dipakai dalam rekap kondisi fasilitas sekolah.',
            ],
            [
                'title' => 'Rombel',
                'menu' => 'Data Sekolah > Rombel',
                'description' => 'Mengelola rombongan belajar dan menempatkan siswa ke rombel yang sesuai.',
                'steps' => [
                    'Buka menu Rombel.',
                    'Tambahkan rombel sesuai tingkat dan jurusan.',
                    'Gunakan aksi penugasan siswa untuk memasukkan siswa ke rombel.',
                ],
                'note' => 'Rombel diperlukan untuk rekap siswa per kelas dan validasi data siswa.',
            ],
            [
                'title' => 'Mata Pelajaran',
                'menu' => 'Data Sekolah > Mapel',
                'description' => 'Mengelola daftar mata pelajaran yang digunakan pada sebaran jam mengajar.',
                'steps' => [
                    'Buka menu Mapel.',
                    'Tambahkan mata pelajaran sesuai kurikulum sekolah.',
                    'Periksa kembali nama mapel agar tidak terjadi duplikasi.',
                ],
                'note' => 'Mapel harus tersedia sebelum mengisi sebaran jam mengajar.',
            ],
            [
                'title' => 'Keuangan',
                'menu' => 'Data Sekolah > Keuangan',
                'description' => 'Mencatat data keuangan sekolah yang diperlukan untuk laporan bulanan.',
                'steps' => [
                    'Buka menu Keuangan.',
                    'Tambahkan komponen keuangan sesuai periode laporan.',
                    'Periksa nominal dan keterangan sebelum menyimpan.',
                ],
                'note' => 'Data keuangan yang benar membantu laporan bulanan lebih lengkap.',
            ],
            [
                'title' => 'Nominatif Siswa',
                'menu' => 'Data Siswa > Nominatif Siswa',
                'description' => 'Mengelola data peserta didik, termasuk identitas, jenis kelamin, agama, alamat, rombel, dan data pendukung lainnya.',
                'steps' => [
                    'Buka menu Nominatif Siswa.',
                    'Tambahkan siswa secara manual atau gunakan template impor jika tersedia.',
                    'Lengkapi field wajib dan pilih rombel yang sesuai.',
                    'Gunakan pencarian untuk mengubah data siswa tertentu.',
                ],
                'note' => 'Pastikan data siswa lengkap agar validasi bagian siswa lolos.',
            ],
            [
                'title' => 'Nominatif GTK',
                'menu' => 'Data GTK > Nominatif GTK',
                'description' => 'Mengelola data guru dan tenaga kependidikan, termasuk status kepegawaian, jenis GTK, pendidikan, dan data pribadi.',
                'steps' => [
                    'Buka menu Nominatif GTK.',
                    'Tambahkan GTK atau impor data jika tersedia.',
                    'Lengkapi identitas dan status GTK.',
                    'Simpan dan lanjutkan pengisian riwayat pendidikan, rekening, jam mengajar, atau kehadiran.',
                ],
                'note' => 'Data GTK menjadi sumber utama untuk rekap GTK dan laporan tenaga pendidik.',
            ],
            [
                'title' => 'Riwayat Pendidikan GTK',
                'menu' => 'Data GTK > Riwayat Pendidikan',
                'description' => 'Mencatat pendidikan terakhir atau riwayat pendidikan GTK.',
                'steps' => [
                    'Buka menu Riwayat Pendidikan.',
                    'Pilih GTK yang akan dilengkapi.',
                    'Isi jenjang, program studi, institusi, dan informasi ijazah bila tersedia.',
                ],
                'note' => 'Riwayat pendidikan mendukung rekap kualifikasi GTK.',
            ],
            [
                'title' => 'Rekening dan NPWP GTK',
                'menu' => 'Data GTK > Rekening & NPWP',
                'description' => 'Melengkapi data rekening dan NPWP GTK untuk kebutuhan administrasi.',
                'steps' => [
                    'Buka menu Rekening & NPWP.',
                    'Pilih GTK yang akan diperbarui.',
                    'Isi nomor rekening, nama bank, nama pemilik rekening, dan NPWP bila ada.',
                ],
                'note' => 'Periksa kembali angka rekening dan NPWP sebelum menyimpan.',
            ],
            [
                'title' => 'Sebaran Jam Ajar',
                'menu' => 'Data GTK > Jam Mengajar',
                'description' => 'Mengisi pembagian jam mengajar guru berdasarkan rombel dan mata pelajaran.',
                'steps' => [
                    'Buka menu Jam Mengajar.',
                    'Pilih guru, rombel, dan mata pelajaran.',
                    'Isi jumlah jam mengajar.',
                    'Pastikan total jam mengajar sesuai ketentuan sekolah.',
                ],
                'note' => 'Sebaran jam mengajar dipakai dalam validasi data GTK.',
            ],
            [
                'title' => 'Kehadiran GTK',
                'menu' => 'Data GTK > Kehadiran',
                'description' => 'Mencatat rekap kehadiran GTK untuk periode laporan.',
                'steps' => [
                    'Buka menu Kehadiran.',
                    'Pilih GTK dan periode yang akan diisi.',
                    'Isi jumlah hadir, izin, sakit, alfa, atau keterangan lain sesuai form.',
                ],
                'note' => 'Kehadiran GTK harus diisi sebelum laporan dinyatakan lengkap.',
            ],
            [
                'title' => 'Validasi Data',
                'menu' => 'Validasi > Validasi Data',
                'description' => 'Memeriksa kelengkapan seluruh bagian laporan sebelum data dikirim atau digunakan untuk cetak laporan.',
                'steps' => [
                    'Buka menu Validasi Data.',
                    'Ikuti langkah validasi dari profil sekolah sampai data laporan bulanan.',
                    'Klik tautan perbaikan bila ada bagian yang belum lengkap.',
                    'Ulangi validasi setelah data diperbaiki.',
                ],
                'note' => 'Validasi membantu operator menemukan data yang belum lengkap dengan cepat.',
            ],
            [
                'title' => 'Laporan Bulanan',
                'menu' => 'Laporan Bulanan',
                'description' => 'Mengelola data laporan bulanan dan komponen rekap yang terbentuk dari data sekolah, siswa, GTK, sarpras, keuangan, dan kelulusan.',
                'steps' => [
                    'Lengkapi seluruh data master dan data bulanan.',
                    'Jalankan Validasi Data.',
                    'Gunakan riwayat pelaporan pada dasbor untuk melihat laporan yang tersedia.',
                    'Klik PDF untuk mengunduh laporan.',
                ],
                'note' => 'Laporan bulanan akan lebih akurat jika semua menu data telah diperbarui.',
            ],
            [
                'title' => 'Pengaturan Akun',
                'menu' => 'Menu Pengguna > Pengaturan Akun',
                'description' => 'Mengubah informasi akun operator, profil, dan pengaturan terkait akun.',
                'steps' => [
                    'Klik menu pengguna di kanan atas.',
                    'Pilih Pengaturan Akun.',
                    'Perbarui data yang diperlukan lalu simpan.',
                ],
                'note' => 'Jaga keamanan akun dan jangan membagikan kredensial login.',
            ],
        ];
    }
}
