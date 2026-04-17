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
use App\Filament\Actions\ValidateChecklistAction;
use Livewire\Attributes\Url;
use Illuminate\Pagination\LengthAwarePaginator;

class KeadaanGtk extends Page
{
    use WithPagination;

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

    public function validateGtkAgamaAction(): \Filament\Actions\Action
    {
        return \App\Filament\Actions\ValidateChecklistAction::make('validateGtkAgama', 'gtk_agama', fn() => \App\Models\Gtk::where('sekolah_id', filament()->getTenant()?->id)->exists());
    }

    public function validateGtkDaerahAction(): \Filament\Actions\Action
    {
        return \App\Filament\Actions\ValidateChecklistAction::make('validateGtkDaerah', 'gtk_daerah', fn() => \App\Models\Gtk::where('sekolah_id', filament()->getTenant()?->id)->exists());
    }

    public function validateGtkStatusAction(): \Filament\Actions\Action
    {
        return \App\Filament\Actions\ValidateChecklistAction::make('validateGtkStatus', 'gtk_status', fn() => \App\Models\Gtk::where('sekolah_id', filament()->getTenant()?->id)->exists());
    }

    public function validateGtkUmurAction(): \Filament\Actions\Action
    {
        return \App\Filament\Actions\ValidateChecklistAction::make('validateGtkUmur', 'gtk_umur', fn() => \App\Models\Gtk::where('sekolah_id', filament()->getTenant()?->id)->exists());
    }

    public function validateGtkPendidikanAction(): \Filament\Actions\Action
    {
        return \App\Filament\Actions\ValidateChecklistAction::make('validateGtkPendidikan', 'gtk_pendidikan', fn() => \App\Models\Gtk::where('sekolah_id', filament()->getTenant()?->id)->exists());
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


    protected function getViewData(): array
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        // 1. GTK by Agama
        $gtkAgamaFull = $this->getGtkAgamaCollection();
        $totalGtkAgama = [];
        foreach (['islam', 'kristen_protestan', 'katolik', 'hindu', 'budha', 'konghucu'] as $ag) {
            $totalGtkAgama[$ag . '_l'] = $gtkAgamaFull->sum($ag . '_l');
            $totalGtkAgama[$ag . '_p'] = $gtkAgamaFull->sum($ag . '_p');
            $totalGtkAgama[$ag . '_jml'] = $gtkAgamaFull->sum($ag . '_jml');
        }

        // 2. GTK by Daerah
        $gtkDaerahFull = $this->getGtkDaerahCollection();
        $totalGtkDaerah = [
            'papua_l' => $gtkDaerahFull->sum('papua_l'),
            'papua_p' => $gtkDaerahFull->sum('papua_p'),
            'papua_jml' => $gtkDaerahFull->sum('papua_jml'),
            'non_papua_l' => $gtkDaerahFull->sum('non_papua_l'),
            'non_papua_p' => $gtkDaerahFull->sum('non_papua_p'),
            'non_papua_jml' => $gtkDaerahFull->sum('non_papua_jml'),
        ];

        // 3. Status Kepegawaian
        $gtkStatusFull = $this->getGtkStatusCollection();
        $totalGtkStatus = [
            'pppk' => $gtkStatusFull->sum('pppk'),
            'honorer' => $gtkStatusFull->sum('honorer_sekolah'),
        ];
        foreach (['i_a', 'i_b', 'i_c', 'i_d', 'ii_a', 'ii_b', 'ii_c', 'ii_d', 'iii_a', 'iii_b', 'iii_c', 'iii_d', 'iv_a', 'iv_b', 'iv_c', 'iv_d', 'iv_e'] as $gol) {
            $totalGtkStatus['gol_' . $gol] = $gtkStatusFull->sum('gol_' . $gol);
        }

        // 4. Umur
        $gtkUmurFull = $this->getGtkUmurCollection();
        $totalGtkUmur = [];
        for ($age = 13; $age <= 23; $age++) {
            $px = 'umur_' . $age;
            $totalGtkUmur[$px . '_l'] = $gtkUmurFull->sum($px . '_l');
            $totalGtkUmur[$px . '_p'] = $gtkUmurFull->sum($px . '_p');
            $totalGtkUmur[$px . '_jml'] = $gtkUmurFull->sum($px . '_jml');
        }

        // 5. Pendidikan
        $gtkPendidikanFull = $this->getGtkPendidikanCollection();
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
        $gtkByJenisFull = $this->getGtkByJenisCollection();
        $totalGtkByJenis = [
            'l' => $gtkByJenisFull->sum('laki_laki'),
            'p' => $gtkByJenisFull->sum('perempuan'),
            'total' => $gtkByJenisFull->sum('total'),
        ];

        return array_merge(parent::getViewData(), [
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

    protected function getGtkAgamaCollection()
    {
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
            )->groupBy('jenis_gtk')->get();
    }

    protected function getGtkDaerahCollection()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        return Gtk::where('sekolah_id', $tenantId)
            ->select('jenis_gtk', 
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%papua%' AND daerah_asal NOT ILIKE '%non%') AND jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as papua_l"),
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%papua%' AND daerah_asal NOT ILIKE '%non%') AND jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as papua_p"),
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%papua%' AND daerah_asal NOT ILIKE '%non%') THEN 1 ELSE 0 END) as papua_jml"),
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%non%' OR daerah_asal NOT ILIKE '%papua%') AND jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as non_papua_l"),
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%non%' OR daerah_asal NOT ILIKE '%papua%') AND jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as non_papua_p"),
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%non%' OR daerah_asal NOT ILIKE '%papua%') THEN 1 ELSE 0 END) as non_papua_jml")
            )->groupBy('jenis_gtk')->get();
    }

    protected function getGtkStatusCollection()
    {
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
            )->groupBy('jenis_gtk')->get();
    }

    protected function getGtkUmurCollection()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $gtks = Gtk::where('sekolah_id', $tenantId)->get();
        $dataByJenis = collect();
        foreach ($gtks as $g) {
            $jenis = $g->jenis_gtk ?? 'Lainnya';
            if (!$dataByJenis->has($jenis)) {
                $item = (object)['jenis_gtk' => $jenis];
                for ($age = 13; $age <= 23; $age++) {
                    $prefix = 'umur_' . $age;
                    $item->{$prefix . '_l'} = 0; $item->{$prefix . '_p'} = 0; $item->{$prefix . '_jml'} = 0;
                }
                $dataByJenis->put($jenis, $item);
            }
            if ($g->tanggal_lahir) {
                $umur = \Carbon\Carbon::parse($g->tanggal_lahir)->age;
                if ($umur >= 13 && $umur <= 23) {
                    $prefix = 'umur_' . $umur; $row = $dataByJenis->get($jenis);
                    if (str_contains(strtolower($g->jenis_kelamin), 'l')) $row->{$prefix . '_l'}++;
                    else $row->{$prefix . '_p'}++;
                    $row->{$prefix . '_jml'}++;
                }
            }
        }
        return $dataByJenis->values();
    }

    protected function getGtkPendidikanCollection()
    {
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
            )->groupBy('jenis_gtk')->get();
    }

    protected function getGtkByJenisCollection()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        return Gtk::where('sekolah_id', $tenantId)
            ->select('jenis_gtk', 
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as laki_laki"),
                DB::raw("SUM(CASE WHEN jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as perempuan"))
            ->whereNotNull('jenis_gtk')->groupBy('jenis_gtk')->get();
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