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

class KeadaanGtk extends Page
{
    use WithPagination;

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

    /**
     * Get data GTK berdasarkan agama, di-group per Jenis GTK
     */
    public function getTabelGtkAgama()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        $data = Gtk::where('sekolah_id', $tenantId)
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
            )
            ->groupBy('jenis_gtk')
            ->get();

        return $this->paginateCollection($data, $this->perPage, $this->pageAgama, ['pageName' => 'pageAgama']);
    }

    /**
     * Get data GTK berdasarkan daerah asal, di-group per Jenis GTK
     */
    public function getTabelGtkDaerah()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        $data = Gtk::where('sekolah_id', $tenantId)
            ->select('jenis_gtk', 
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%papua%' AND daerah_asal NOT ILIKE '%non%') AND jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as papua_l"),
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%papua%' AND daerah_asal NOT ILIKE '%non%') AND jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as papua_p"),
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%papua%' AND daerah_asal NOT ILIKE '%non%') THEN 1 ELSE 0 END) as papua_jml"),
                
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%non%' OR daerah_asal NOT ILIKE '%papua%') AND jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as non_papua_l"),
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%non%' OR daerah_asal NOT ILIKE '%papua%') AND jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as non_papua_p"),
                DB::raw("SUM(CASE WHEN (daerah_asal ILIKE '%non%' OR daerah_asal NOT ILIKE '%papua%') THEN 1 ELSE 0 END) as non_papua_jml")
            )
            ->groupBy('jenis_gtk')
            ->get();

        return $this->paginateCollection($data, $this->perPage, $this->pageDaerah, ['pageName' => 'pageDaerah']);
    }

    /**
     * Get data GTK berdasarkan status kepegawaian, di-group per Jenis GTK
     */
    public function getTabelGtkStatusKepegawaian()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        $data = Gtk::where('sekolah_id', $tenantId)
            ->select('jenis_gtk', 
                DB::raw("SUM(CASE WHEN (status_kepegawaian ILIKE '%pns%' AND status_kepegawaian NOT ILIKE '%pppk%') THEN 1 ELSE 0 END) as pns"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%pppk%' THEN 1 ELSE 0 END) as pppk"),
                DB::raw("SUM(CASE WHEN status_kepegawaian ILIKE '%honorer%' THEN 1 ELSE 0 END) as honorer_sekolah")
            )
            ->groupBy('jenis_gtk')
            ->get();

        return $this->paginateCollection($data, $this->perPage, $this->pageStatus, ['pageName' => 'pageStatus']);
    }

    /**
     * Get data GTK berdasarkan umur, di-group per Jenis GTK (Sesuai loop view 13-23)
     */
    public function getTabelGtkUmur()
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
                    $item->{$prefix . '_l'} = 0;
                    $item->{$prefix . '_p'} = 0;
                    $item->{$prefix . '_jml'} = 0;
                }
                $dataByJenis->put($jenis, $item);
            }

            if ($g->tanggal_lahir) {
                $umur = \Carbon\Carbon::parse($g->tanggal_lahir)->age;
                if ($umur >= 13 && $umur <= 23) {
                    $prefix = 'umur_' . $umur;
                    $row = $dataByJenis->get($jenis);
                    if (str_contains(strtolower($g->jenis_kelamin), 'l')) {
                        $row->{$prefix . '_l'}++;
                    } else {
                        $row->{$prefix . '_p'}++;
                    }
                    $row->{$prefix . '_jml'}++;
                }
            }
        }

        return $this->paginateCollection($dataByJenis->values(), $this->perPage, $this->pageUmur, ['pageName' => 'pageUmur']);
    }

    /**
     * Get data GTK berdasarkan pendidikan terakhir, di-group per Jenis GTK
     */
    public function getTabelGtkPendidikan()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        $data = Gtk::where('sekolah_id', $tenantId)
            ->select('jenis_gtk', 
                DB::raw("SUM(CASE WHEN pendidikan_terakhir ILIKE '%slta%' OR pendidikan_terakhir ILIKE '%sma%' OR pendidikan_terakhir ILIKE '%smk%' THEN 1 ELSE 0 END) as slta"),
                DB::raw("SUM(CASE WHEN pendidikan_terakhir = 'D1' THEN 1 ELSE 0 END) as di"),
                DB::raw("SUM(CASE WHEN pendidikan_terakhir = 'D2' THEN 1 ELSE 0 END) as dii"),
                DB::raw("SUM(CASE WHEN (pendidikan_terakhir = 'D3' OR pendidikan_terakhir = 'DIII') THEN 1 ELSE 0 END) as diii"),
                DB::raw("SUM(CASE WHEN pendidikan_terakhir ILIKE '%s1%' OR pendidikan_terakhir ILIKE '%d4%' THEN 1 ELSE 0 END) as s1"),
                DB::raw("SUM(CASE WHEN pendidikan_terakhir ILIKE '%s2%' THEN 1 ELSE 0 END) as s2"),
                DB::raw("SUM(CASE WHEN pendidikan_terakhir ILIKE '%s3%' THEN 1 ELSE 0 END) as s3")
            )
            ->groupBy('jenis_gtk')
            ->get();

        return $this->paginateCollection($data, $this->perPage, $this->pagePendidikan, ['pageName' => 'pagePendidikan']);
    }

    /**
     * Get data GTK berdasarkan jenis GTK
     * Tambahan method untuk analisis berdasarkan jenis_gtk
     */
    public function getTabelGtkByJenis()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        
        $data = Gtk::where('sekolah_id', $tenantId)
            ->select('jenis_gtk', 
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN jenis_kelamin LIKE 'L%' THEN 1 ELSE 0 END) as laki_laki"),
                DB::raw("SUM(CASE WHEN jenis_kelamin LIKE 'P%' THEN 1 ELSE 0 END) as perempuan"))
            ->whereNotNull('jenis_gtk')
            ->groupBy('jenis_gtk')
            ->get();
            
        return $this->paginateCollection($data, $this->perPage, $this->pageJenis, ['pageName' => 'pageJenis']);
    }

    /**
     * Get data GTK berdasarkan pangkat/golongan
     * Tambahan method untuk analisis kepangkatan
     */
    public function getTabelGtkByPangkat()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        $data = Gtk::where('sekolah_id', $tenantId)
            ->select('pangkat_gol_terakhir', 
                DB::raw('COUNT(*) as total'))
            ->whereNotNull('pangkat_gol_terakhir')
            ->groupBy('pangkat_gol_terakhir')
            ->orderBy('pangkat_gol_terakhir')
            ->get();

        return $this->paginateCollection($data, $this->perPage, $this->pagePangkat, ['pageName' => 'pagePangkat']);
    }

    protected function getViewData(): array
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        return array_merge(parent::getViewData(), [
            'gtkAgama' => $this->getTabelGtkAgama(),
            'gtkDaerah' => $this->getTabelGtkDaerah(),
            'gtkStatusKepegawaian' => $this->getTabelGtkStatusKepegawaian(),
            'gtkUmur' => $this->getTabelGtkUmur(),
            'gtkPendidikan' => $this->getTabelGtkPendidikan(),
            'totalGtk' => Gtk::where('sekolah_id', $tenantId)->count(),
            'totalGtkLakiLaki' => Gtk::where('sekolah_id', $tenantId)->where('jenis_kelamin', 'LIKE', 'L%')->count(),
            'totalGtkPerempuan' => Gtk::where('sekolah_id', $tenantId)->where('jenis_kelamin', 'LIKE', 'P%')->count(),
            'gtkByJenis' => $this->getTabelGtkByJenis(),
            'gtkByPangkat' => $this->getTabelGtkByPangkat(),
        ]);
    }

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

    public function getSubheading(): ?string
    {
        return 'Menampilkan data mengenai Guru dan Tenaga Kependidikan';
    }
}