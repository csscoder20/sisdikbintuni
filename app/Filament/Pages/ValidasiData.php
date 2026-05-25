<?php

namespace App\Filament\Pages;

use App\Models\Gtk;
use App\Models\GtkPendidikan;
use App\Models\KehadiranGtk;
use App\Filament\Actions\ValidateChecklistAction;
use App\Models\Laporan;
use App\Models\LaporanGedung;
use App\Models\LaporanKeuangan;
use App\Models\LaporanSiswa;
use App\Models\LaporanSiswaKategori;
use App\Models\LaporanSiswaRekap;
use App\Models\Mapel;
use App\Models\Mengajar;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Support\ValidationPeriod;
use Carbon\Carbon;
use Filament\Support\Icons\Heroicon;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
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

    #[Url]
    public int   $currentStep = 1;
    public int   $totalSteps  = 12;
    public array $stepStatuses = [];
    public array $bypassedSteps = [];
    public bool  $isSubmitted = false;

    /**
     * Mengontrol apakah menu navigasi ini muncul di sidebar.
     */
    public static function shouldRegisterNavigation(): bool
    {
        try {
            $panelId = Filament::getCurrentPanel()?->getId();
            if ($panelId === 'dinas') {
                return !empty(session('dinas_selected_sekolah_id'));
            }
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

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
        $id = $this->getSchoolId();
        $month = (int) date('m');
        $year  = (int) date('Y');

        $laporan = Laporan::where('sekolah_id', $id)->where('bulan', $month)->where('tahun', $year)->first();
        if ($laporan && ($laporan->status === 'valid' || $laporan->tanggal_submit)) {
            $this->isSubmitted = true;
        }

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

        // Ambil ID sekolah dari session jika di panel dinas
        if (Filament::getCurrentPanel()?->getId() === 'dinas') {
            return session('dinas_selected_sekolah_id');
        }

        return auth()->user()?->sekolah?->id;
    }

    protected function computeStatuses(): void
    {
        $id = $this->getSchoolId();

        $month = (int) date('m');
        $year  = (int) date('Y');
        $laporan = Laporan::where('sekolah_id', $id)->where('bulan', $month)->where('tahun', $year)->first();

        $s1  = $this->checkStep1($id);
        $s2  = $this->checkStep2($id);
        $s3  = $this->checkMapel($id);
        $s4  = $this->checkStep3($id);
        $s5  = $this->checkKeuangan($id);
        $s6  = $this->checkStep4($id);
        $s7  = $this->checkStep5($id);
        $s8  = $this->checkPendidikanGtk($id);
        $s9  = $this->checkRekeningGtk($id);
        $s10 = $this->checkStep6($id);
        $s11 = $this->checkStep7($id);

        if ($laporan) {
            if ($laporan->is_identitas_sekolah_valid) $s1 = true;
            if ($laporan->is_kondisi_sarpras_valid) $s2 = true;
            if ($laporan->is_mapel_valid) $s3 = true;
            if ($laporan->is_siswa_rombel_valid) $s4 = true;
            if ($laporan->is_keuangan_valid) $s5 = true;
            if ($laporan->is_nominatif_siswa_valid) $s6 = true;
            if ($laporan->is_nominatif_gtk_valid) $s7 = true;
            if ($laporan->is_gtk_pendidikan_valid) $s8 = true;
            if ($laporan->is_rekening_npwp_valid) $s9 = true;
            if ($laporan->is_sebaran_jam_valid) $s10 = true;
            if ($laporan->is_rekap_kehadiran_valid) $s11 = true;
        }

        $this->stepStatuses = [
            1  => $s1,
            2  => $s2,
            3  => $s3,
            4  => $s4,
            5  => $s5,
            6  => $s6,
            7  => $s7,
            8  => $s8,
            9  => $s9,
            10 => $s10,
            11 => $s11,
            12 => true, // Pernyataan selalu valid
        ];

        foreach ($this->bypassedSteps as $step) {
            $this->stepStatuses[$step] = true;
        }
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
    public function getMissingMessageForStep(int $step): ?string
    {
        switch ($step) {
            case 1:
                $missing = $this->getMissingProfilFields();
                if (!empty($missing)) {
                    return "Masih terdapat data pada Profil Sekolah yang kolom <b>" . implode(', ', $missing) . "</b> masih kosong.";
                }
                break;
            case 2:
                if ($this->getSarprasCount() == 0) {
                    return "Belum ada data pada tabel Sarana & Prasarana.";
                }
                break;
            case 3:
                if ($this->getMapelCount() == 0) {
                    return "Belum ada data pada tabel Mata Pelajaran.";
                } elseif (!\App\Models\Mengajar::whereHas('gtk', fn($q) => $q->where('sekolah_id', $this->getSchoolId()))->whereNotNull('mapel_id')->exists()) {
                    return "Belum ada guru yang ditugaskan ke mata pelajaran.";
                }
                break;
            case 4:
                if ($this->getRombelCount() == 0) {
                    return "Belum ada data pada tabel Rombel.";
                } else {
                    $empty = $this->getEmptyRombels();
                    if (!empty($empty)) {
                        return "Masih terdapat Rombel yang belum memiliki siswa.";
                    }
                }
                break;
            case 5:
                if ($this->getLaporanKeuanganCount() == 0) {
                    return "Belum ada data Keuangan.";
                }
                break;
            case 6:
                if ($this->getSiswaCount() == 0) {
                    return "Belum ada data pada tabel Nominatif Siswa.";
                } else {
                    $inc = $this->getIncompleteSiswaInfo();
                    if (!empty($inc)) {
                        $allMissingCols = [];
                        foreach ($inc as $i) {
                            foreach ($i['missing'] as $m) {
                                $allMissingCols[] = $m;
                            }
                        }
                        $uniqueCols = array_unique($allMissingCols);
                        $colsString = implode(', ', $uniqueCols);
                        return "Masih terdapat data pada tabel Nominatif Siswa yang kolom <b>{$colsString}</b> masih kosong.";
                    }
                }
                break;
            case 7:
                if ($this->getGtkCount() == 0) {
                    return "Belum ada data pada tabel Nominatif GTK.";
                } else {
                    $inc = $this->getIncompleteGtkInfo();
                    if (!empty($inc)) {
                        $allMissingCols = [];
                        foreach ($inc as $i) {
                            foreach ($i['missing'] as $m) {
                                $allMissingCols[] = $m;
                            }
                        }
                        $uniqueCols = array_unique($allMissingCols);
                        $colsString = implode(', ', $uniqueCols);
                        return "Masih terdapat data pada tabel Nominatif GTK yang kolom <b>{$colsString}</b> masih kosong.";
                    }
                }
                break;
            case 8:
                $tanpaPend = $this->getGtkTanpaPendidikan();
                if (!empty($tanpaPend)) {
                    return "Masih terdapat data GTK yang belum melengkapi <b>Riwayat Pendidikan</b>.";
                }
                break;
            case 9:
                $tanpaRek = $this->getGtkTanpaRekening();
                if (!empty($tanpaRek)) {
                    return "Masih terdapat data GTK yang belum melengkapi data <b>Rekening atau NPWP</b>.";
                }
                break;
            case 10:
                $below = $this->getGtkBelowMinJam();
                if (!empty($below)) {
                    return "Masih terdapat data GTK yang jumlah jam mengajarnya <b>kurang dari 24 jam</b>.";
                }
                break;
            case 11:
                $tanpaHadir = $this->getGtkWithoutKehadiran();
                if (!empty($tanpaHadir)) {
                    return "Masih terdapat data GTK yang <b>belum lengkap rekap kehadirannya</b>.";
                }
                break;
        }
        return "Terdapat data yang belum lengkap pada langkah ini.";
    }

    public function nextStep(): void
    {
        $this->computeStatuses();

        if (!($this->stepStatuses[$this->currentStep] ?? false)) {
            $msg = $this->getMissingMessageForStep($this->currentStep);
            $this->dispatch('swal-confirm-next', message: $msg);
            return;
        }

        $this->saveProgressToDatabase();

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
            $this->resetPage();
        }
    }

    public function forceNextStep(): void
    {
        if (!in_array($this->currentStep, $this->bypassedSteps)) {
            $this->bypassedSteps[] = $this->currentStep;
        }

        $this->computeStatuses();
        $this->saveProgressToDatabase();

        if ($this->currentStep < $this->totalSteps) {
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
        if (ValidationPeriod::isLockedForOperatorPanel()) {
            Notification::make()
                ->title('Periode validasi sedang ditutup.')
                ->body(ValidationPeriod::lockMessage())
                ->danger()
                ->send();
            return;
        }

        if ($this->isSubmitted) return;

        $this->computeStatuses();

        $incompleteSteps = collect($this->stepStatuses)
            ->filter(fn($s, $k) => !$s && $k < 12)
            ->keys()
            ->map(fn($k) => $this->getStepLabels()[$k])
            ->implode(', ');

        if (!empty($incompleteSteps)) {
            $msg = "Masih ada langkah yang belum valid: <b>{$incompleteSteps}</b>.<br><br>Apakah Anda yakin ingin menyelesaikan validasi dengan data yang ada?";
            $this->dispatch('swal-confirm-submit', message: $msg);
            return;
        }

        $this->dispatch('swal-confirm-submit', message: 'Apakah Anda yakin semua data yang dimasukkan sudah benar? Data akan disimpan sebagai laporan resmi.');
    }

    public function forceSubmit(): void
    {
        if (ValidationPeriod::isLockedForOperatorPanel()) {
            Notification::make()
                ->title('Periode validasi sedang ditutup.')
                ->body(ValidationPeriod::lockMessage())
                ->danger()
                ->send();
            return;
        }

        if (!in_array($this->currentStep, $this->bypassedSteps)) {
            $this->bypassedSteps[] = $this->currentStep;
        }

        $this->computeStatuses();
        $this->saveProgressToDatabase();

        $id = $this->getSchoolId();
        $month = (int) date('m');
        $year  = (int) date('Y');

        $laporan = Laporan::updateOrCreate(
            ['sekolah_id' => $id, 'bulan' => $month, 'tahun' => $year],
            ['status' => 'valid', 'tanggal_submit' => now()]
        );

        if ($this->stepStatuses[6] ?? false) {
            ValidateChecklistAction::persistNominatifSiswaSnapshot($laporan);
        }

        $this->isSubmitted = true;

        $period = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');

        $this->dispatch('swal-success', message: "Semua langkah valid! data Anda sudah dikirim ke database sebagai laporan periode {$period}");
    }

    protected function saveProgressToDatabase(): void
    {
        $id = $this->getSchoolId();
        if (!$id) return;

        $month = (int) date('m');
        $year  = (int) date('Y');

        Laporan::updateOrCreate(
            ['sekolah_id' => $id, 'bulan' => $month, 'tahun' => $year],
            [
                'is_identitas_sekolah_valid' => $this->stepStatuses[1] ?? false,
                'is_kondisi_sarpras_valid'   => $this->stepStatuses[2] ?? false,
                'is_mapel_valid'             => $this->stepStatuses[3] ?? false,
                'is_siswa_rombel_valid'      => $this->stepStatuses[4] ?? false,
                'is_keuangan_valid'          => $this->stepStatuses[5] ?? false,
                'is_kondisi_siswa_valid'     => $this->stepStatuses[6] ?? false,
                'is_nominatif_siswa_valid'   => $this->stepStatuses[6] ?? false,
                'is_kondisi_gtk_valid'       => $this->stepStatuses[7] ?? false,
                'is_nominatif_gtk_valid'     => $this->stepStatuses[7] ?? false,
                'is_gtk_pendidikan_valid'    => $this->stepStatuses[8] ?? false,
                'is_rekening_npwp_valid'     => $this->stepStatuses[9] ?? false,
                'is_sebaran_jam_valid'       => $this->stepStatuses[10] ?? false,
                'is_rekap_kehadiran_valid'   => $this->stepStatuses[11] ?? false,
            ]
        );
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
            $items,
            count($rows),
            $perPage,
            $page,
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
            $q->whereHas('gtk', fn($sq) => $sq->where('sekolah_id', $id));
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
            12 => 'Pernyataan',
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Validasi Data — ' . $this->getCurrentPeriod();
    }

    public function isValidationPeriodLocked(): bool
    {
        return ValidationPeriod::isLockedForOperatorPanel();
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
