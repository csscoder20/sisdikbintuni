<?php

namespace App\Filament\Pages;

use App\Models\Gtk;
use App\Models\GtkPendidikan;
use App\Models\KehadiranGtk;
use App\Models\Laporan;
use App\Models\LaporanGedung;
use App\Models\LaporanKeuangan;
use App\Models\Mapel;
use App\Models\Mengajar;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Siswa;
use Carbon\Carbon;
use Filament\Support\Icons\Heroicon;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\WithPagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ValidasiData extends Page
{
    use WithPagination;
    protected static ?string $navigationLabel = 'Validasi Data';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;
    protected static ?int    $navigationSort  = 10;
    protected static string|\UnitEnum|null $navigationGroup = 'Validasi/Verifikasi';
    protected static ?string $slug  = 'validasi-data';
    protected static ?string $title = 'VALIDASI DATA';
    protected string $view = 'filament.pages.validasi-data';

    public int   $currentStep = 1;
    public int   $totalSteps  = 11;
    public array $stepStatuses = [];

    /* ------------------------------------------------------------------ */
    /* Required fields for Profil Sekolah (label => field)                 */
    /* ------------------------------------------------------------------ */
    protected array $requiredProfilFields = [
        'nama'                 => 'Nama Sekolah',
        'npsn'                 => 'NPSN',
        'nss'                  => 'NSS',
        'npwp'                 => 'NPWP',
        'email'                => 'Email Sekolah',
        'jenjang'              => 'Jenjang',
        'akreditasi'           => 'Akreditasi',
        'tahun_berdiri'        => 'Tahun Berdiri',
        'nomor_sk_pendirian'   => 'Nomor SK Pendirian',
        'tanggal_sk_pendirian' => 'Tanggal SK Pendirian',
        'provinsi'             => 'Provinsi',
        'kabupaten'            => 'Kabupaten',
        'kecamatan'            => 'Kecamatan',
        'desa'                 => 'Desa/Kelurahan',
        'alamat'               => 'Alamat',
        'status_tanah'         => 'Status Tanah',
        'luas_tanah'           => 'Luas Tanah',
        'nama_yayasan'         => 'Nama Penyelenggara/Yayasan',
        'alamat_yayasan'       => 'Alamat Yayasan',
        'nomor_sk_yayasan'     => 'Nomor SK Yayasan',
        'nama_rekening_bop'    => 'Nama Rekening BOP',
        'nomor_rekening_bop'   => 'Nomor Rekening BOP',
        'nama_bank_bop'        => 'Nama Bank BOP',
        'nama_rekening_bosp'   => 'Nama Rekening BOSP',
        'nomor_rekening_bosp'  => 'Nomor Rekening BOSP',
        'nama_bank_bosp'       => 'Nama Bank BOSP',
    ];

    /* ------------------------------------------------------------------ */
    /* Access                                                               */
    /* ------------------------------------------------------------------ */
    public static function canAccess(): bool
    {
        return auth()->check()
            && auth()->user()->hasRole(['operator', 'super_admin', 'admin_dinas']);
    }

    /* ------------------------------------------------------------------ */
    /* Lifecycle                                                            */
    /* ------------------------------------------------------------------ */
    public function mount(): void
    {
        $this->computeStatuses();
    }

    public function updatedCurrentStep(): void
    {
        $this->resetPage();
    }

    /* ------------------------------------------------------------------ */
    /* Helpers                                                              */
    /* ------------------------------------------------------------------ */
    protected function getSchoolId(): ?int
    {
        $tenant = Filament::getTenant();
        if ($tenant instanceof Sekolah) {
            return $tenant->id;
        }
        return auth()->user()?->sekolah?->id;
    }

    protected function computeStatuses(): void
    {
        $id = $this->getSchoolId();

        $this->stepStatuses = [
            1  => $this->checkStep1($id),   // Profil
            2  => $this->checkStep2($id),   // Sarpras
            3  => $this->checkMapel($id),   // Mapel
            4  => $this->checkStep3($id),   // Rombel
            5  => $this->checkKeuangan($id),// Keuangan
            6  => $this->checkStep4($id),   // Nominatif Siswa
            7  => $this->checkStep5($id),   // Nominatif GTK
            8  => $this->checkPendidikanGtk($id), // Riwayat Pendidikan
            9  => $this->checkRekeningGtk($id),   // Rekening & NPWP
            10 => $this->checkStep6($id),   // Sebaran Jam
            11 => $this->checkStep7($id),   // Kehadiran GTK
        ];
    }

    public function getExpectedWorkingDays(): array
    {
        $days = [];
        $today = now();
        $start = now()->startOfMonth();
        $end = $today; // Check up to today

        for ($date = $start; $date->lte($end); $date->addDay()) {
            if (!$date->isSunday()) {
                $days[] = $date->copy();
            }
        }
        return $days;
    }

    public function isGtkAttendanceComplete(Gtk $g): bool
    {
        $expectedDays = $this->getExpectedWorkingDays();
        if (empty($expectedDays)) return true;

        $filledDates = \App\Models\GtkKehadiran::where('gtk_id', $g->id)
            ->whereBetween('tgl_presensi', [now()->startOfMonth(), now()])
            ->whereIn('presensi', ['H', 'I', 'S', 'A', 'L'])
            ->pluck('tgl_presensi')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->toArray();

        foreach ($expectedDays as $date) {
            if (!in_array($date->format('Y-m-d'), $filledDates)) {
                return false;
            }
        }

        return true;
    }

    /* ------------------------------------------------------------------ */
    /* Step validators                                                      */
    /* ------------------------------------------------------------------ */
    protected function checkStep1(?int $id): bool
    {
        if (!$id) return false;
        $s = Sekolah::find($id);
        if (!$s) return false;
        foreach (array_keys($this->requiredProfilFields) as $field) {
            if (empty($s->$field)) return false;
        }
        return true;
    }

    protected function checkStep2(?int $id): bool
    {
        if (!$id) return false;
        return LaporanGedung::whereHas('laporan', fn($q) => $q->where('sekolah_id', $id))->exists();
    }

    protected function checkStep3(?int $id): bool
    {
        if (!$id) return false;
        return Rombel::where('sekolah_id', $id)->exists() && empty($this->getEmptyRombels());
    }

    protected function checkStep4(?int $id): bool
    {
        if (!$id) return false;
        return Siswa::where('sekolah_id', $id)->exists() && empty($this->getIncompleteSiswaInfo());
    }

    protected function checkStep5(?int $id): bool
    {
        if (!$id) return false;
        return Gtk::where('sekolah_id', $id)->exists() && empty($this->getIncompleteGtkInfo());
    }

    protected function checkMapel(?int $id): bool
    {
        if (!$id) return false;
        return Mengajar::whereHas('gtk', fn($q) => $q->where('sekolah_id', $id))
            ->whereNotNull('mapel_id')
            ->exists();
    }

    protected function checkKeuangan(?int $id): bool
    {
        if (!$id) return false;
        return LaporanKeuangan::whereHas('laporan', fn($q) => $q->where('sekolah_id', $id))
            ->exists();
    }

    protected function checkPendidikanGtk(?int $id): bool
    {
        if (!$id) return false;
        return Gtk::where('sekolah_id', $id)->exists() && !count($this->getGtkTanpaPendidikan());
    }

    protected function checkRekeningGtk(?int $id): bool
    {
        if (!$id) return false;
        return Gtk::where('sekolah_id', $id)->exists() && !count($this->getGtkTanpaRekening());
    }

    protected function checkStep6(?int $id): bool
    {
        if (!$id) return false;
        return Mengajar::whereHas('gtk', fn($q) => $q->where('sekolah_id', $id))->exists() && empty($this->getGtkBelowMinJam());
    }

    protected function checkStep7(?int $id): bool
    {
        if (!$id) return false;
        
        $gtks = Gtk::where('sekolah_id', $id)->get();
        if ($gtks->isEmpty()) return false;

        foreach ($gtks as $g) {
            if (!$this->isGtkAttendanceComplete($g)) {
                return false;
            }
        }

        return true;
    }

    /* ------------------------------------------------------------------ */
    /* Navigation actions                                                   */
    /* ------------------------------------------------------------------ */
    public function nextStep(): void
    {
        $this->computeStatuses();
        if (($this->stepStatuses[$this->currentStep] ?? false) && $this->currentStep < $this->totalSteps) {
            $this->currentStep++;
            $this->resetPage();
        }
    }

    public function prevStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->resetPage();
        }
    }

    /* ------------------------------------------------------------------ */
    /* Final submit                                                         */
    /* ------------------------------------------------------------------ */
    public function submitValidasi(): void
    {
        $this->computeStatuses();

        if (!collect($this->stepStatuses)->every(fn($s) => $s)) {
            Notification::make()
                ->title('Validasi Gagal')
                ->body('Masih ada data yang belum lengkap. Periksa setiap langkah.')
                ->danger()
                ->send();
            return;
        }

        $id    = $this->getSchoolId();
        $month = (int) date('m');
        $year  = (int) date('Y');

        Laporan::updateOrCreate(
            ['sekolah_id' => $id, 'bulan' => $month, 'tahun' => $year],
            [
                'is_identitas_sekolah_valid' => true,
                'is_kondisi_sarpras_valid'   => true,
                'is_siswa_rombel_valid'      => true,
                'is_kondisi_siswa_valid'     => true,
                'is_nominatif_siswa_valid'   => true,
                'is_kondisi_gtk_valid'       => true,
                'is_nominatif_gtk_valid'     => true,
                'is_sebaran_jam_valid'       => true,
                'is_rekap_kehadiran_valid'   => true,
            ]
        );

        Notification::make()
            ->title('Validasi Berhasil!')
            ->body('Data periode ' . Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') . ' berhasil divalidasi dan disimpan.')
            ->success()
            ->send();
    }

    /* ------------------------------------------------------------------ */
    /* Data getters for view                                                */
    /* ------------------------------------------------------------------ */
    public function getMissingProfilFields(): array
    {
        $s = Sekolah::find($this->getSchoolId());
        if (!$s) return array_values($this->requiredProfilFields);

        $missing = [];
        foreach ($this->requiredProfilFields as $field => $label) {
            if (empty($s->$field)) {
                $missing[] = $label;
            }
        }
        return $missing;
    }

    public function getProfilRows(): \Illuminate\Pagination\LengthAwarePaginator
    {
        $s = Sekolah::find($this->getSchoolId());
        if (!$s) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }

        $rows = [];
        foreach ($this->requiredProfilFields as $field => $label) {
            $val = $s->$field ?? null;
            if ($val && $field === 'tanggal_sk_pendirian') {
                try {
                    $val = \Carbon\Carbon::parse($val)->translatedFormat('d F Y');
                } catch (\Exception $e) {
                    // Do nothing, leave it as is
                }
            }
            $rows[] = [
                'label' => $label,
                'value' => $val,
                'ok'    => !empty($s->$field),
            ];
        }

        $page = $this->getPage();
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $items = array_slice($rows, $offset, $perPage);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items, count($rows), $perPage, $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
    }

    public function getSarprasList(): LengthAwarePaginator
    {
        return LaporanGedung::whereHas('laporan', fn($q) => $q->where('sekolah_id', $this->getSchoolId()))
            ->orderBy('id', 'asc')
            ->paginate(10);
    }

    public function getSarprasCount(): int
    {
        return LaporanGedung::whereHas('laporan', fn($q) => $q->where('sekolah_id', $this->getSchoolId()))->count();
    }

    public function getRombelList(): LengthAwarePaginator
    {
        return Rombel::where('sekolah_id', $this->getSchoolId())
            ->withCount('siswa')
            ->orderBy('id', 'asc')
            ->paginate(10);
    }

    public function getRombelCount(): int
    {
        return Rombel::where('sekolah_id', $this->getSchoolId())->count();
    }

    public function getTotalSiswaRombel(): int
    {
        return Siswa::whereHas('rombel', fn($q) => $q->where('sekolah_id', $this->getSchoolId()))->count();
    }

    public function getEmptyRombels(): array
    {
        return Rombel::where('sekolah_id', $this->getSchoolId())
            ->withCount('siswa')
            ->get()
            ->filter(fn($r) => $r->siswa_count === 0)
            ->pluck('nama')
            ->toArray();
    }

    public function getSiswaList(): LengthAwarePaginator
    {
        return Siswa::where('sekolah_id', $this->getSchoolId())
            ->orderBy('id', 'asc')
            ->paginate(10);
    }

    public function getSiswaCount(): int
    {
        return Siswa::where('sekolah_id', $this->getSchoolId())->count();
    }

    protected array $requiredSiswaFields = [
        'nisn'          => 'NISN',
        'nik'           => 'NIK',
        'tempat_lahir'  => 'Tempat Lahir',
        'tanggal_lahir' => 'Tanggal Lahir',
        'jenis_kelamin' => 'Jenis Kelamin',
        'agama'         => 'Agama',
        'alamat'        => 'Alamat',
        'daerah_asal'   => 'Daerah Asal',
    ];

    public function getIncompleteSiswaInfo(): array
    {
        $id    = $this->getSchoolId();
        $siswas = Siswa::where('sekolah_id', $id)->get();
        $incomplete = [];
        foreach ($siswas as $s) {
            $missing = [];
            foreach ($this->requiredSiswaFields as $field => $label) {
                if (empty($s->$field)) $missing[] = $label;
            }
            if (!empty($missing)) {
                $incomplete[] = ['nama' => $s->nama, 'missing' => $missing];
            }
        }
        return $incomplete;
    }

    public function isSiswaComplete(Siswa $s): bool
    {
        foreach (array_keys($this->requiredSiswaFields) as $field) {
            if (empty($s->$field)) return false;
        }
        return true;
    }

    public function getGtkList(): LengthAwarePaginator
    {
        return Gtk::where('sekolah_id', $this->getSchoolId())
            ->orderBy('id', 'asc')
            ->paginate(10);
    }

    public function getGtkPendidikanList(): LengthAwarePaginator
    {
        return Gtk::where('sekolah_id', $this->getSchoolId())
            ->with('pendidikan')
            ->orderBy('id', 'asc')
            ->paginate(10);
    }

    public function getGtkKeuanganList(): LengthAwarePaginator
    {
        return Gtk::where('sekolah_id', $this->getSchoolId())
            ->orderBy('id', 'asc')
            ->paginate(10);
    }

    public function getGtkCount(): int
    {
        return Gtk::where('sekolah_id', $this->getSchoolId())->count();
    }

    protected array $requiredGtkFields = [
        'nik'                  => 'NIK',
        'tempat_lahir'         => 'Tempat Lahir',
        'tanggal_lahir'        => 'Tanggal Lahir',
        'jenis_kelamin'        => 'Jenis Kelamin',
        'agama'                => 'Agama',
        'alamat'               => 'Alamat',
        'daerah_asal'          => 'Daerah Asal',
        'jenis_gtk'            => 'Jenis GTK',
        'status_kepegawaian'   => 'Status Kepegawaian',
        'pendidikan_terakhir'  => 'Pendidikan Terakhir',
    ];

    public function getIncompleteGtkInfo(): array
    {
        $id  = $this->getSchoolId();
        $gtks = Gtk::where('sekolah_id', $id)->get();
        $incomplete = [];
        foreach ($gtks as $g) {
            $missing = [];
            foreach ($this->requiredGtkFields as $field => $label) {
                if (empty($g->$field)) $missing[] = $label;
            }
            if (!empty($missing)) {
                $incomplete[] = ['nama' => $g->nama, 'missing' => $missing];
            }
        }
        return $incomplete;
    }

    public function isGtkComplete(Gtk $g): bool
    {
        foreach (array_keys($this->requiredGtkFields) as $field) {
            if (empty($g->$field)) return false;
        }
        return true;
    }

    public function getSebaranList(): LengthAwarePaginator
    {
        $id = $this->getSchoolId();
        return Gtk::where('sekolah_id', $id)
            ->whereHas('mengajar')
            ->withSum('mengajar as total_jam', 'jumlah_jam')
            ->withCount('mengajar as jumlah_entri')
            ->orderBy('id', 'asc')
            ->paginate(10);
    }

    public function getSebaranCount(): int
    {
        $id = $this->getSchoolId();
        return Gtk::where('sekolah_id', $id)->whereHas('mengajar')->count();
    }

    public function getGtkBelowMinJam(): array
    {
        $id = $this->getSchoolId();
        return Gtk::where('sekolah_id', $id)
            ->where('jenis_gtk', '!=', 'Tenaga Administrasi')
            ->withSum('mengajar as total_jam', 'jumlah_jam')
            ->get()
            ->filter(fn($g) => ($g->total_jam ?? 0) < 24)
            ->map(fn($g) => ['nama' => $g->nama, 'jam' => $g->total_jam ?? 0])
            ->values()
            ->toArray();
    }

    public function getGtkWithEmptyMengajar(): array
    {
        $id = $this->getSchoolId();
        return Gtk::where('sekolah_id', $id)
            ->where('jenis_gtk', '!=', 'Tenaga Administrasi')
            ->whereHas('mengajar', fn($q) => $q->whereNull('rombel_id')->orWhereNull('mapel_id'))
            ->pluck('nama')
            ->toArray();
    }

    public function getKehadiranList(): LengthAwarePaginator
    {
        return KehadiranGtk::with('gtk')
            ->whereHas('gtk', fn($q) => $q->where('sekolah_id', $this->getSchoolId()))
            ->orderBy('id', 'asc')
            ->paginate(10);
    }

    public function getKehadiranCount(): int
    {
        return KehadiranGtk::whereHas('gtk', fn($q) => $q->where('sekolah_id', $this->getSchoolId()))->count();
    }

    public function getGtkWithoutKehadiran(): array
    {
        $id = $this->getSchoolId();
        $gtks = Gtk::where('sekolah_id', $id)->get();
        $incomplete = [];

        foreach ($gtks as $g) {
            if (!$this->isGtkAttendanceComplete($g)) {
                $incomplete[] = $g->nama;
            }
        }
        return $incomplete;
    }

    public function getMapelList(): LengthAwarePaginator
    {
        $id = $this->getSchoolId();
        $sekolah = Sekolah::find($id);
        $query = Mapel::query();
        if ($sekolah && $sekolah->jenjang) {
            $query->where('jenjang', $sekolah->jenjang);
        }
        $query->withExists(['mengajars' => function ($q) use ($id) {
            $q->whereHas('gtk', fn ($sq) => $sq->where('sekolah_id', $id));
        }]);
        return $query->orderBy('id', 'asc')->paginate(10);
    }

    public function getMapelCount(): int
    {
        $sekolah = Sekolah::find($this->getSchoolId());
        $query = Mapel::query();
        if ($sekolah && $sekolah->jenjang) {
            $query->where('jenjang', $sekolah->jenjang);
        }
        return $query->count();
    }

    public function getLaporanKeuanganList(): LengthAwarePaginator
    {
        return LaporanKeuangan::with('laporan')
            ->whereHas('laporan', fn($q) => $q->where('sekolah_id', $this->getSchoolId()))
            ->orderBy('tanggal')
            ->orderBy('id')
            ->paginate(10);
    }

    public function getLaporanKeuanganCount(): int
    {
        return LaporanKeuangan::whereHas('laporan', fn($q) => $q->where('sekolah_id', $this->getSchoolId()))->count();
    }

    public function getGtkTanpaPendidikan(): array
    {
        $id = $this->getSchoolId();
        return Gtk::where('sekolah_id', $id)
            ->where(function ($query) {
                $query->whereDoesntHave('pendidikan')
                    ->orWhereHas('pendidikan', function ($q) {
                        $q->where(function ($sq) {
                            $fields = ['thn_tamat_s1', 'jurusan_s1', 'perguruan_tinggi_s1'];
                            foreach ($fields as $f) {
                                $sq->orWhereNull($f)
                                   ->orWhere($f, '')
                                   ->orWhere($f, '-');
                            }
                        });
                    });
            })
            ->pluck('nama')
            ->toArray();
    }

    public function getGtkTanpaRekening(): array
    {
        $id = $this->getSchoolId();
        return Gtk::where('sekolah_id', $id)
            ->where(function ($query) {
                foreach (['no_rek_gaji', 'nama_bank_gaji', 'no_rek_tunjangan', 'nama_bank_tunjangan', 'npwp'] as $field) {
                    $query->orWhereNull($field)->orWhere($field, '');
                }
            })
            ->pluck('nama')
            ->toArray();
    }

    public function getGtkTanpaPendidikanDetailed(): array
    {
        return $this->getGtkTanpaPendidikan();
    }

    public function getGtkTanpaRekeningDetailed(): array
    {
        return $this->getGtkTanpaRekening();
    }

    public function getCurrentPeriod(): string
    {
        return Carbon::now()->translatedFormat('F Y');
    }

    public function getStepLabels(): array
    {
        return [
            1  => 'Profil',
            2  => 'Sarana & Prasarana',
            3  => 'Mapel',
            4  => 'Rombel',
            5  => 'Keuangan',
            6  => 'Nominatif Siswa',
            7  => 'Nominatif GTK',
            8  => 'Riwayat Pendidikan GTK',
            9  => 'Rekening & NPWP',
            10 => 'Sebaran Jam Mengajar',
            11 => 'Kehadiran GTK',
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Validasi Data — ' . $this->getCurrentPeriod();
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('periode')
                ->label('Periode Validasi: ' . $this->getCurrentPeriod())
                ->color('success')
                ->icon('heroicon-o-calendar')
                ->extraAttributes([
                    'style' => 'cursor: default; pointer-events: none; border-radius: 999px;',
                ]),
        ];
    }
}
