<?php

namespace App\Filament\Pages;

use App\Models\Gtk;
use App\Models\LaporanGtk;
use App\Models\LaporanGtkKategori;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Laporan;

class KeadaanGtk extends Page
{
    use WithPagination;

    #[Url]
    public $selectedLaporanId;

    protected $listeners = ['laporanUpdated' => 'handleLaporanUpdated'];

    public function getLaporanStatus(string $type): bool
    {
        $sekolahId = filament()->getTenant()?->id;
        $laporan = \App\Models\Laporan::where([
            'sekolah_id' => $sekolahId,
            'bulan' => (int) date('m'),
            'tahun' => (int) date('Y'),
        ])->first();

        $column = "is_{$type}_valid";
        return $laporan ? (bool) $laporan->$column : false;
    }

    protected static ?string $navigationLabel = 'Keadaan GTK';
    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Keadaan GTK';
    protected static ?string $pluralModelLabel = 'Keadaan GTK';
    
    protected static string | \UnitEnum | null $navigationGroup = 'Laporan Bulanan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    #[Url]
    public $perPage = 10;
    #[Url]
    public $pageAgama = 1;
    #[Url]
    public $pageDaerah = 1;
    #[Url]
    public $pageStatus = 1;
    #[Url]
    public $pageUmur = 1;
    #[Url]
    public $pagePendidikan = 1;
    #[Url]
    public $pageJenis = 1;
    #[Url]
    public $pagePangkat = 1;


    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-users';
    }

    /**
     * Helper method untuk membuat paginator dari collection
     */
    protected function paginateCollection($items, $perPage, $page, $options = [])
    {
        $page = $page ?: 1;
        $perPage = $perPage ?: 10;
        $offset = ($page - 1) * $perPage;
        $paginatedItems = $items->slice($offset, $perPage)->values();

        return new LengthAwarePaginator(
            $paginatedItems,
            $items->count(),
            $perPage,
            $page,
            array_merge($options, ['path' => request()->url(), 'query' => request()->query()])
        );
    }

    protected function getActiveLaporanId(): ?int
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        if ($this->selectedLaporanId === '') {
            return null;
        }

        if ($this->selectedLaporanId) {
            return (int) $this->selectedLaporanId;
        }

        return Laporan::where('sekolah_id', $tenantId)
            ->whereHas('gtk.kategori')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->value('id');
    }

    protected function getLaporanGtkRows(int $laporanId)
    {
        return LaporanGtk::with('kategori')
            ->where('laporan_id', $laporanId)
            ->orderByRaw("CASE jenis_gtk WHEN 'kepala_sekolah' THEN 1 WHEN 'guru' THEN 2 WHEN 'tenaga_administrasi' THEN 3 ELSE 4 END")
            ->get();
    }

    protected function getKategoriJumlah(LaporanGtk $laporanGtk, string $jenisKategori, string $subKategori): int
    {
        return (int) ($laporanGtk->kategori
            ->first(fn ($kategori) => $kategori->jenis_kategori === $jenisKategori && $kategori->sub_kategori === $subKategori)
            ?->jumlah ?? 0);
    }

    protected function getJenisGtkLabel(?string $jenisGtk): string
    {
        return match ($jenisGtk) {
            'kepala_sekolah' => 'Kepala Sekolah',
            'tenaga_administrasi' => 'Tenaga Administrasi',
            'guru' => 'Guru',
            default => $jenisGtk ?: 'Lainnya',
        };
    }

    protected function getGtkAgeRanges(): array
    {
        return [
            'umur_20_29' => ['label' => '20-29 Thn', 'min' => 20, 'max' => 29],
            'umur_30_39' => ['label' => '30-39 Thn', 'min' => 30, 'max' => 39],
            'umur_40_49' => ['label' => '40-49 Thn', 'min' => 40, 'max' => 49],
            'umur_50_59' => ['label' => '50-59 Thn', 'min' => 50, 'max' => 59],
            'umur_60_ke_atas' => ['label' => '60+ Thn', 'min' => 60, 'max' => null],
        ];
    }

    protected function getAgeRangeKey(int $age): ?string
    {
        foreach ($this->getGtkAgeRanges() as $key => $range) {
            if ($age >= $range['min'] && ($range['max'] === null || $age <= $range['max'])) {
                return $key;
            }
        }

        return null;
    }

    protected function getCurrentPeriodEndDate(): \Carbon\Carbon
    {
        return now()->endOfMonth();
    }

    protected function getViewData(): array
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $laporanId = $this->getActiveLaporanId();

        $periodes = Laporan::where('sekolah_id', $tenantId)
            ->whereHas('gtk.kategori')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        // 1. GTK by Agama
        $gtkAgamaFull = $this->getGtkAgamaCollection($laporanId);
        $totalGtkAgama = [];
        foreach (['islam', 'kristen_protestan', 'katolik', 'hindu', 'budha', 'konghucu'] as $ag) {
            $totalGtkAgama[$ag . '_l'] = $gtkAgamaFull->sum($ag . '_l');
            $totalGtkAgama[$ag . '_p'] = $gtkAgamaFull->sum($ag . '_p');
            $totalGtkAgama[$ag . '_jml'] = $gtkAgamaFull->sum($ag . '_jml');
        }

        // 2. GTK by Daerah
        $gtkDaerahFull = $this->getGtkDaerahCollection($laporanId);
        $totalGtkDaerah = [
            'papua_l' => $gtkDaerahFull->sum('papua_l'),
            'papua_p' => $gtkDaerahFull->sum('papua_p'),
            'papua_jml' => $gtkDaerahFull->sum('papua_jml'),
            'non_papua_l' => $gtkDaerahFull->sum('non_papua_l'),
            'non_papua_p' => $gtkDaerahFull->sum('non_papua_p'),
            'non_papua_jml' => $gtkDaerahFull->sum('non_papua_jml'),
        ];

        // 3. Status Kepegawaian
        $gtkStatusFull = $this->getGtkStatusCollection($laporanId);
        $totalGtkStatus = [
            'pppk' => $gtkStatusFull->sum('pppk'),
            'honorer' => $gtkStatusFull->sum('honorer_sekolah'),
        ];
        foreach (['i_a', 'i_b', 'i_c', 'i_d', 'ii_a', 'ii_b', 'ii_c', 'ii_d', 'iii_a', 'iii_b', 'iii_c', 'iii_d', 'iv_a', 'iv_b', 'iv_c', 'iv_d', 'iv_e'] as $gol) {
            $totalGtkStatus['gol_' . $gol] = $gtkStatusFull->sum('gol_' . $gol);
        }

        // 4. Umur
        $gtkUmurFull = $this->getGtkUmurCollection($laporanId);
        $totalGtkUmur = [];
        foreach (array_keys($this->getGtkAgeRanges()) as $px) {
            $totalGtkUmur[$px . '_l'] = $gtkUmurFull->sum($px . '_l');
            $totalGtkUmur[$px . '_p'] = $gtkUmurFull->sum($px . '_p');
            $totalGtkUmur[$px . '_jml'] = $gtkUmurFull->sum($px . '_jml');
        }

        // 5. Pendidikan
        $gtkPendidikanFull = $this->getGtkPendidikanCollection($laporanId);
        $totalGtkPendidikan = [
            'slta' => $gtkPendidikanFull->sum('slta'),
            'di' => $gtkPendidikanFull->sum('di'),
            'dii' => $gtkPendidikanFull->sum('dii'),
            'diii' => $gtkPendidikanFull->sum('diii'),
            's1' => $gtkPendidikanFull->sum('s1'),
            's2' => $gtkPendidikanFull->sum('s2'),
            's3' => $gtkPendidikanFull->sum('s3'),
        ];

        // 6. Jenis Kelamin (By Jenis)
        $gtkByJenisFull = $this->getGtkByJenisCollection($laporanId);
        $totalGtkByJenis = [
            'l' => $gtkByJenisFull->sum('laki_laki'),
            'p' => $gtkByJenisFull->sum('perempuan'),
            'total' => $gtkByJenisFull->sum('total'),
        ];

        return array_merge(parent::getViewData(), [
            'periodes' => $periodes,
            'selectedLaporanId' => $laporanId,
            'gtkAgama' => $this->paginateCollection($gtkAgamaFull, $this->perPage, $this->pageAgama, ['pageName' => 'pageAgama']),
            'gtkDaerah' => $this->paginateCollection($gtkDaerahFull, $this->perPage, $this->pageDaerah, ['pageName' => 'pageDaerah']),
            'gtkStatusKepegawaian' => $this->paginateCollection($gtkStatusFull, $this->perPage, $this->pageStatus, ['pageName' => 'pageStatus']),
            'gtkUmur' => $this->paginateCollection($gtkUmurFull, $this->perPage, $this->pageUmur, ['pageName' => 'pageUmur']),
            'gtkPendidikan' => $this->paginateCollection($gtkPendidikanFull, $this->perPage, $this->pagePendidikan, ['pageName' => 'pagePendidikan']),
            'gtkByJenis' => $this->paginateCollection($gtkByJenisFull, $this->perPage, $this->pageJenis, ['pageName' => 'pageJenis']),
            'gtkByPangkat' => $this->getTabelGtkByPangkat(), // Pangkat is already small and sorted

            'totalGtkAgama' => $totalGtkAgama,
            'totalGtkDaerah' => $totalGtkDaerah,
            'totalGtkStatus' => $totalGtkStatus,
            'totalGtkUmur' => $totalGtkUmur,
            'totalGtkPendidikan' => $totalGtkPendidikan,
            'totalGtkByJenis' => $totalGtkByJenis,
            'gtkAgeRanges' => $this->getGtkAgeRanges(),
            'perPage' => $this->perPage,


        ]);
    }

    public function getTabelGtkByPangkat()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        return Gtk::where('sekolah_id', $tenantId)
            ->select('pangkat_gol_terakhir', 
                DB::raw('COUNT(*) as total'))
            ->whereNotNull('pangkat_gol_terakhir')
            ->groupBy('pangkat_gol_terakhir')
            ->orderBy('pangkat_gol_terakhir')
            ->get();
    }

    protected function getGtkAgamaCollection(?int $laporanId = null)
    {
        if ($laporanId) {
            return $this->getLaporanGtkRows($laporanId)->map(function (LaporanGtk $laporanGtk) {
                $row = (object) ['jenis_gtk' => $this->getJenisGtkLabel($laporanGtk->jenis_gtk)];

                foreach (['islam', 'kristen_protestan', 'katolik', 'hindu', 'budha', 'konghucu'] as $agama) {
                    $row->{$agama . '_l'} = $this->getKategoriJumlah($laporanGtk, 'agama', "{$agama}_l");
                    $row->{$agama . '_p'} = $this->getKategoriJumlah($laporanGtk, 'agama', "{$agama}_p");
                    $storedTotal = $this->getKategoriJumlah($laporanGtk, 'agama', "{$agama}_jml");
                    $row->{$agama . '_jml'} = $storedTotal ?: $row->{$agama . '_l'} + $row->{$agama . '_p'};
                }

                return $row;
            });
        }

        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        return Gtk::where('sekolah_id', $tenantId)
            ->select('jenis_gtk', 
                DB::raw("SUM(CASE WHEN agama ILIKE '%islam%' AND jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as islam_l"),
                DB::raw("SUM(CASE WHEN agama ILIKE '%islam%' AND jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as islam_p"),
                DB::raw("SUM(CASE WHEN agama ILIKE '%islam%' THEN 1 ELSE 0 END) as islam_jml"),
                DB::raw("SUM(CASE WHEN (agama ILIKE '%kristen%' OR agama ILIKE '%protestan%') AND jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as kristen_protestan_l"),
                DB::raw("SUM(CASE WHEN (agama ILIKE '%kristen%' OR agama ILIKE '%protestan%') AND jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as kristen_protestan_p"),
                DB::raw("SUM(CASE WHEN (agama ILIKE '%kristen%' OR agama ILIKE '%protestan%') THEN 1 ELSE 0 END) as kristen_protestan_jml"),
                DB::raw("SUM(CASE WHEN agama ILIKE '%katolik%' AND jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as katolik_l"),
                DB::raw("SUM(CASE WHEN agama ILIKE '%katolik%' AND jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as katolik_p"),
                DB::raw("SUM(CASE WHEN agama ILIKE '%katolik%' THEN 1 ELSE 0 END) as katolik_jml"),
                DB::raw("SUM(CASE WHEN agama ILIKE '%hindu%' AND jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as hindu_l"),
                DB::raw("SUM(CASE WHEN agama ILIKE '%hindu%' AND jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as hindu_p"),
                DB::raw("SUM(CASE WHEN agama ILIKE '%hindu%' THEN 1 ELSE 0 END) as hindu_jml"),
                DB::raw("SUM(CASE WHEN agama ILIKE '%budha%' AND jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as budha_l"),
                DB::raw("SUM(CASE WHEN agama ILIKE '%budha%' AND jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as budha_p"),
                DB::raw("SUM(CASE WHEN agama ILIKE '%budha%' THEN 1 ELSE 0 END) as budha_jml"),
                DB::raw("SUM(CASE WHEN agama ILIKE '%konghucu%' AND jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as konghucu_l"),
                DB::raw("SUM(CASE WHEN agama ILIKE '%konghucu%' AND jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as konghucu_p"),
                DB::raw("SUM(CASE WHEN agama ILIKE '%konghucu%' THEN 1 ELSE 0 END) as konghucu_jml")
            )->groupBy('jenis_gtk')
            ->orderByRaw("CASE WHEN jenis_gtk ILIKE '%kepala%' THEN 1 WHEN jenis_gtk ILIKE '%guru%' THEN 2 WHEN jenis_gtk ILIKE '%administrasi%' THEN 3 ELSE 4 END")
            ->get();
    }

    protected function getGtkDaerahCollection(?int $laporanId = null)
    {
        if ($laporanId) {
            return $this->getLaporanGtkRows($laporanId)->map(function (LaporanGtk $laporanGtk) {
                $row = (object) ['jenis_gtk' => $this->getJenisGtkLabel($laporanGtk->jenis_gtk)];

                foreach (['papua', 'non_papua'] as $daerah) {
                    $row->{$daerah . '_l'} = $this->getKategoriJumlah($laporanGtk, 'daerah', "{$daerah}_l");
                    $row->{$daerah . '_p'} = $this->getKategoriJumlah($laporanGtk, 'daerah', "{$daerah}_p");
                    $storedTotal = $this->getKategoriJumlah($laporanGtk, 'daerah', "{$daerah}_jml");
                    $row->{$daerah . '_jml'} = $storedTotal ?: $row->{$daerah . '_l'} + $row->{$daerah . '_p'};
                }

                return $row;
            });
        }

        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        return Gtk::where('sekolah_id', $tenantId)
            ->select('jenis_gtk', 
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%papua%' AND daerah_asal NOT ILIKE '%non%') AND jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as papua_l"),
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%papua%' AND daerah_asal NOT ILIKE '%non%') AND jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as papua_p"),
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%papua%' AND daerah_asal NOT ILIKE '%non%') THEN 1 ELSE 0 END) as papua_jml"),
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%non%' OR daerah_asal NOT ILIKE '%papua%') AND jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as non_papua_l"),
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%non%' OR daerah_asal NOT ILIKE '%papua%') AND jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as non_papua_p"),
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%non%' OR daerah_asal NOT ILIKE '%papua%') THEN 1 ELSE 0 END) as non_papua_jml")
            )->groupBy('jenis_gtk')
            ->orderByRaw("CASE WHEN jenis_gtk ILIKE '%kepala%' THEN 1 WHEN jenis_gtk ILIKE '%guru%' THEN 2 WHEN jenis_gtk ILIKE '%administrasi%' THEN 3 ELSE 4 END")
            ->get();
    }

    protected function getGtkStatusCollection(?int $laporanId = null)
    {
        if ($laporanId) {
            return $this->getLaporanGtkRows($laporanId)->map(function (LaporanGtk $laporanGtk) {
                $row = (object) ['jenis_gtk' => $this->getJenisGtkLabel($laporanGtk->jenis_gtk)];

                foreach ([
                    'gol_i_a', 'gol_i_b', 'gol_i_c', 'gol_i_d',
                    'gol_ii_a', 'gol_ii_b', 'gol_ii_c', 'gol_ii_d',
                    'gol_iii_a', 'gol_iii_b', 'gol_iii_c', 'gol_iii_d',
                    'gol_iv_a', 'gol_iv_b', 'gol_iv_c', 'gol_iv_d', 'gol_iv_e',
                    'pppk', 'honorer_sekolah',
                ] as $status) {
                    $row->{$status} = $this->getKategoriJumlah($laporanGtk, 'status_kepegawaian', $status);
                }

                return $row;
            });
        }

        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        return Gtk::where('sekolah_id', $tenantId)
            ->select('jenis_gtk', 
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'I/a' THEN 1 ELSE 0 END) as gol_i_a"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'I/b' THEN 1 ELSE 0 END) as gol_i_b"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'I/c' THEN 1 ELSE 0 END) as gol_i_c"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'I/d' THEN 1 ELSE 0 END) as gol_i_d"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'II/a' THEN 1 ELSE 0 END) as gol_ii_a"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'II/b' THEN 1 ELSE 0 END) as gol_ii_b"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'II/c' THEN 1 ELSE 0 END) as gol_ii_c"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'II/d' THEN 1 ELSE 0 END) as gol_ii_d"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'III/a' THEN 1 ELSE 0 END) as gol_iii_a"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'III/b' THEN 1 ELSE 0 END) as gol_iii_b"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'III/c' THEN 1 ELSE 0 END) as gol_iii_c"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'III/d' THEN 1 ELSE 0 END) as gol_iii_d"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'IV/a' THEN 1 ELSE 0 END) as gol_iv_a"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'IV/b' THEN 1 ELSE 0 END) as gol_iv_b"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'IV/c' THEN 1 ELSE 0 END) as gol_iv_c"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'IV/d' THEN 1 ELSE 0 END) as gol_iv_d"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pns%' AND pangkat_gol_terakhir = 'IV/e' THEN 1 ELSE 0 END) as gol_iv_e"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pppk%' THEN 1 ELSE 0 END) as pppk"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%honorer%' OR status_kepegawaian ILIKE '%GTY%' OR status_kepegawaian ILIKE '%PTY%' THEN 1 ELSE 0 END) as honorer_sekolah")
            )->groupBy('jenis_gtk')
            ->orderByRaw("CASE WHEN jenis_gtk ILIKE '%kepala%' THEN 1 WHEN jenis_gtk ILIKE '%guru%' THEN 2 WHEN jenis_gtk ILIKE '%administrasi%' THEN 3 ELSE 4 END")
            ->get();
    }

    protected function getGtkUmurCollection(?int $laporanId = null)
    {
        if ($laporanId) {
            return $this->getLaporanGtkRows($laporanId)->map(function (LaporanGtk $laporanGtk) {
                $row = (object) ['jenis_gtk' => $this->getJenisGtkLabel($laporanGtk->jenis_gtk)];

                foreach (array_keys($this->getGtkAgeRanges()) as $prefix) {
                    $row->{$prefix . '_l'} = $this->getKategoriJumlah($laporanGtk, 'umur', "{$prefix}_l");
                    $row->{$prefix . '_p'} = $this->getKategoriJumlah($laporanGtk, 'umur', "{$prefix}_p");
                    $storedTotal = $this->getKategoriJumlah($laporanGtk, 'umur', "{$prefix}_jml");
                    $row->{$prefix . '_jml'} = $storedTotal ?: $row->{$prefix . '_l'} + $row->{$prefix . '_p'};
                }

                return $row;
            });
        }

        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $gtks = Gtk::where('sekolah_id', $tenantId)
            ->orderByRaw("CASE WHEN jenis_gtk ILIKE '%kepala%' THEN 1 WHEN jenis_gtk ILIKE '%guru%' THEN 2 WHEN jenis_gtk ILIKE '%administrasi%' THEN 3 ELSE 4 END")
            ->get();
        $periodEndDate = $this->getCurrentPeriodEndDate();
        $dataByJenis = collect();
        foreach ($gtks as $g) {
            $jenis = $g->jenis_gtk ?? 'Lainnya';
            if (!$dataByJenis->has($jenis)) {
                $item = (object)['jenis_gtk' => $jenis];
                foreach (array_keys($this->getGtkAgeRanges()) as $prefix) {
                    $item->{$prefix . '_l'} = 0; $item->{$prefix . '_p'} = 0; $item->{$prefix . '_jml'} = 0;
                }
                $dataByJenis->put($jenis, $item);
            }
            if ($g->tanggal_lahir) {
                $umur = \Carbon\Carbon::parse($g->tanggal_lahir)->diffInYears($periodEndDate);
                $prefix = $this->getAgeRangeKey($umur);
                if ($prefix) {
                    $row = $dataByJenis->get($jenis);
                    if (str_contains(strtolower($g->jenis_kelamin), 'l')) $row->{$prefix . '_l'}++;
                    else $row->{$prefix . '_p'}++;
                    $row->{$prefix . '_jml'}++;
                }
            }
        }
        return $dataByJenis->values();
    }

    protected function getGtkPendidikanCollection(?int $laporanId = null)
    {
        if ($laporanId) {
            return $this->getLaporanGtkRows($laporanId)->map(function (LaporanGtk $laporanGtk) {
                $row = (object) ['jenis_gtk' => $this->getJenisGtkLabel($laporanGtk->jenis_gtk)];

                foreach (['slta', 'di', 'dii', 'diii', 's1', 's2', 's3'] as $pendidikan) {
                    $row->{$pendidikan} = $this->getKategoriJumlah($laporanGtk, 'pendidikan', $pendidikan);
                }

                return $row;
            });
        }

        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        return Gtk::where('sekolah_id', $tenantId)
            ->select('jenis_gtk', 
                DB::raw("SUM(CASE WHEN pendidikan_terakhir ILIKE '%slta%' OR pendidikan_terakhir ILIKE '%sma%' OR pendidikan_terakhir ILIKE '%smk%' THEN 1 ELSE 0 END) as slta"),
                DB::raw("SUM(CASE WHEN pendidikan_terakhir = 'D1' THEN 1 ELSE 0 END) as di"),
                DB::raw("SUM(CASE WHEN pendidikan_terakhir = 'D2' THEN 1 ELSE 0 END) as dii"),
                DB::raw("SUM(CASE WHEN (pendidikan_terakhir = 'D3' OR pendidikan_terakhir = 'DIII') THEN 1 ELSE 0 END) as diii"),
                DB::raw("SUM(CASE WHEN pendidikan_terakhir ILIKE '%s1%' OR pendidikan_terakhir ILIKE '%d4%' THEN 1 ELSE 0 END) as s1"),
                DB::raw("SUM(CASE WHEN pendidikan_terakhir ILIKE '%s2%' THEN 1 ELSE 0 END) as s2"),
                DB::raw("SUM(CASE WHEN pendidikan_terakhir ILIKE '%s3%' THEN 1 ELSE 0 END) as s3")
            )->groupBy('jenis_gtk')
            ->orderByRaw("CASE WHEN jenis_gtk ILIKE '%kepala%' THEN 1 WHEN jenis_gtk ILIKE '%guru%' THEN 2 WHEN jenis_gtk ILIKE '%administrasi%' THEN 3 ELSE 4 END")
            ->get();
    }

    protected function getGtkByJenisCollection(?int $laporanId = null)
    {
        if ($laporanId) {
            return $this->getLaporanGtkRows($laporanId)->map(function (LaporanGtk $laporanGtk) {
                $laki = $this->getKategoriJumlah($laporanGtk, 'agama', 'islam_l')
                    + $this->getKategoriJumlah($laporanGtk, 'agama', 'kristen_protestan_l')
                    + $this->getKategoriJumlah($laporanGtk, 'agama', 'katolik_l')
                    + $this->getKategoriJumlah($laporanGtk, 'agama', 'hindu_l')
                    + $this->getKategoriJumlah($laporanGtk, 'agama', 'budha_l')
                    + $this->getKategoriJumlah($laporanGtk, 'agama', 'konghucu_l');
                $perempuan = $this->getKategoriJumlah($laporanGtk, 'agama', 'islam_p')
                    + $this->getKategoriJumlah($laporanGtk, 'agama', 'kristen_protestan_p')
                    + $this->getKategoriJumlah($laporanGtk, 'agama', 'katolik_p')
                    + $this->getKategoriJumlah($laporanGtk, 'agama', 'hindu_p')
                    + $this->getKategoriJumlah($laporanGtk, 'agama', 'budha_p')
                    + $this->getKategoriJumlah($laporanGtk, 'agama', 'konghucu_p');

                return (object) [
                    'jenis_gtk' => $this->getJenisGtkLabel($laporanGtk->jenis_gtk),
                    'laki_laki' => $laki,
                    'perempuan' => $perempuan,
                    'total' => $laki + $perempuan,
                ];
            });
        }

        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        return Gtk::where('sekolah_id', $tenantId)
            ->select('jenis_gtk', 
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as laki_laki"),
                DB::raw("SUM(CASE WHEN jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as perempuan"))
            ->whereNotNull('jenis_gtk')
            ->groupBy('jenis_gtk')
            ->orderByRaw("CASE WHEN jenis_gtk ILIKE '%kepala%' THEN 1 WHEN jenis_gtk ILIKE '%guru%' THEN 2 WHEN jenis_gtk ILIKE '%administrasi%' THEN 3 ELSE 4 END")
            ->get();
    }

    /**
     * @deprecated Use Collection methods
     */
    public function getTabelGtkAgama() {}
    public function getTabelGtkDaerah() {}
    public function getTabelGtkStatusKepegawaian() {}
    public function getTabelGtkUmur() {}
    public function getTabelGtkPendidikan() {}
    public function getTabelGtkByJenis() {}

    public function changeTablePage(string $property, int $page): void
    {
        $allowed = ['pageAgama', 'pageDaerah', 'pageStatus', 'pageUmur', 'pagePendidikan', 'pageJenis', 'pagePangkat'];
        if (in_array($property, $allowed)) {
            $this->$property = max(1, $page);
        }
    }

    public function updatedSelectedLaporanId(): void
    {
        $this->resetTablePages();
    }

    public function handleLaporanUpdated($laporanId): void
    {
        $this->selectedLaporanId = $laporanId;
        $this->resetTablePages();
    }

    public function updatedPerPage(): void
    {
        $this->resetTablePages();
    }

    protected function resetTablePages(): void
    {
        $this->pageAgama = 1;
        $this->pageDaerah = 1;
        $this->pageStatus = 1;
        $this->pageUmur = 1;
        $this->pagePendidikan = 1;
        $this->pageJenis = 1;
        $this->pagePangkat = 1;
    }

    public function getView(): string
    {
        return 'filament.pages.keadaan-gtk';
    }

    public function getTitle(): string
    {
        return 'Keadaan GTK';
    }

    public function getHeading(): string
    {
        return 'Keadaan GTK';
    }
}
