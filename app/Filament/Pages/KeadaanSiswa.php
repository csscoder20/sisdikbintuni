<?php

namespace App\Filament\Pages;

use App\Models\LaporanSiswa;
use App\Models\LaporanSiswaKategori;
use App\Models\Siswa;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Filament\Actions\ValidateChecklistAction;
use Livewire\Attributes\Url;

class KeadaanSiswa extends Page
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

    public function validateSiswaRombelAction(): \Filament\Actions\Action
    {
        return \App\Filament\Actions\ValidateChecklistAction::make('validateSiswaRombel', 'siswa_rombel', fn() => \App\Models\Siswa::where('sekolah_id', filament()->getTenant()?->id)->exists());
    }

    public function validateSiswaUmurAction(): \Filament\Actions\Action
    {
        return \App\Filament\Actions\ValidateChecklistAction::make('validateSiswaUmur', 'siswa_umur', fn() => \App\Models\Siswa::where('sekolah_id', filament()->getTenant()?->id)->exists());
    }

    public function validateSiswaAgamaAction(): \Filament\Actions\Action
    {
        return \App\Filament\Actions\ValidateChecklistAction::make('validateSiswaAgama', 'siswa_agama', fn() => \App\Models\Siswa::where('sekolah_id', filament()->getTenant()?->id)->exists());
    }

    public function validateSiswaDaerahAction(): \Filament\Actions\Action
    {
        return \App\Filament\Actions\ValidateChecklistAction::make('validateSiswaDaerah', 'siswa_daerah', fn() => \App\Models\Siswa::where('sekolah_id', filament()->getTenant()?->id)->exists());
    }

    public function validateSiswaDisabilitasAction(): \Filament\Actions\Action
    {
        return \App\Filament\Actions\ValidateChecklistAction::make('validateSiswaDisabilitas', 'siswa_disabilitas', fn() => \App\Models\Siswa::where('sekolah_id', filament()->getTenant()?->id)->exists());
    }

    public function validateSiswaBeasiswaAction(): \Filament\Actions\Action
    {
        return \App\Filament\Actions\ValidateChecklistAction::make('validateSiswaBeasiswa', 'siswa_beasiswa', fn() => \App\Models\Siswa::where('sekolah_id', filament()->getTenant()?->id)->exists());
    }

    protected static ?string $navigationLabel = 'Keadaan Siswa';
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Keadaan Siswa';
    protected static ?string $pluralModelLabel = 'Keadaan Siswa';

    protected static string|\UnitEnum|null $navigationGroup = 'Laporan Bulanan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    #[Url]
    public $perPage = 10;
    #[Url]
    public $pagePerKelas = 1;
    #[Url]
    public $pagePerUmur = 1;
    #[Url]
    public $pagePerAgama = 1;
    #[Url]
    public $pagePerDaerah = 1;
    #[Url]
    public $pagePerDisabilitas = 1;
    #[Url]
    public $pagePerBeasiswa = 1;

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-chart-bar';
    }

    public function getView(): string
    {
        return 'filament.pages.keadaan-siswa';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Data Keadaan Siswa';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Analisis Keadaan Siswa';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Menampilkan berbagai data siswa dari berbagai aspek';
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

        // 1. Siswa per Kelas
        $siswaPerKelasFull = $this->getSiswaPerKelasCollection();
        $totalSiswaPerKelas = [
            'awal_l' => $siswaPerKelasFull->sum('awal_bulan_l'),
            'awal_p' => $siswaPerKelasFull->sum('awal_bulan_p'),
            'awal_jml' => $siswaPerKelasFull->sum('awal_bulan_jml'),
            'mutasi_l' => $siswaPerKelasFull->sum('mutasi_l'),
            'mutasi_p' => $siswaPerKelasFull->sum('mutasi_p'),
            'mutasi_jml' => $siswaPerKelasFull->sum('mutasi_jml'),
            'mutasi_keluar_l' => $siswaPerKelasFull->sum('mutasi_keluar_l'),
            'mutasi_keluar_p' => $siswaPerKelasFull->sum('mutasi_keluar_p'),
            'mutasi_keluar_jml' => $siswaPerKelasFull->sum('mutasi_keluar_jml'),
            'putus_l' => $siswaPerKelasFull->sum('putus_sekolah_l'),
            'putus_p' => $siswaPerKelasFull->sum('putus_sekolah_p'),
            'putus_jml' => $siswaPerKelasFull->sum('putus_sekolah_jml'),
            'mengulang_l' => $siswaPerKelasFull->sum('mengulang_l'),
            'mengulang_p' => $siswaPerKelasFull->sum('mengulang_p'),
            'mengulang_jml' => $siswaPerKelasFull->sum('mengulang_jml'),
            'akhir_l' => $siswaPerKelasFull->sum('akhir_bulan_l'),
            'akhir_p' => $siswaPerKelasFull->sum('akhir_bulan_p'),
            'akhir_jml' => $siswaPerKelasFull->sum('akhir_bulan_jml'),
        ];

        // 2. Siswa per Umur
        $siswaPerUmurFull = $this->getSiswaPerUmurCollection();
        $totalSiswaPerUmur = [];
        for ($age = 13; $age <= 23; $age++) {
            $px = 'umur_' . $age;
            $totalSiswaPerUmur[$px . '_l'] = $siswaPerUmurFull->sum($px . '_l');
            $totalSiswaPerUmur[$px . '_p'] = $siswaPerUmurFull->sum($px . '_p');
            $totalSiswaPerUmur[$px . '_jml'] = $siswaPerUmurFull->sum($px . '_jml');
        }

        // 3. Siswa per Agama
        $siswaPerAgamaFull = $this->getSiswaPerAgamaCollection();
        $totalSiswaPerAgama = [];
        foreach (['islam', 'kristen_protestan', 'katolik', 'hindu', 'budha', 'konghucu'] as $ag) {
            $totalSiswaPerAgama[$ag . '_l'] = $siswaPerAgamaFull->sum($ag . '_l');
            $totalSiswaPerAgama[$ag . '_p'] = $siswaPerAgamaFull->sum($ag . '_p');
            $totalSiswaPerAgama[$ag . '_jml'] = $siswaPerAgamaFull->sum($ag . '_jml');
        }

        // 4. Siswa per Daerah
        $siswaPDaerahFull = $this->getSiswaPDaerahCollection();
        $totalSiswaPDaerah = [
            'papua_l' => $siswaPDaerahFull->sum('papua_l'),
            'papua_p' => $siswaPDaerahFull->sum('papua_p'),
            'papua_jml' => $siswaPDaerahFull->sum('papua_jml'),
            'non_papua_l' => $siswaPDaerahFull->sum('non_papua_l'),
            'non_papua_p' => $siswaPDaerahFull->sum('non_papua_p'),
            'non_papua_jml' => $siswaPDaerahFull->sum('non_papua_jml'),
        ];

        // 5. Siswa Disabilitas
        $siswaDisabilitasFull = $this->getSiswaDisabilitasCollection();
        $totalSiswaDisabilitas = [
            'l' => $siswaDisabilitasFull->sum('laki_laki'),
            'p' => $siswaDisabilitasFull->sum('perempuan'),
            'total' => $siswaDisabilitasFull->sum('total'),
        ];

        // 6. Siswa Beasiswa
        $siswaBeasiswaFull = $this->getSiswaBeasiswaCollection();
        $totalSiswaBeasiswa = [
            'l' => $siswaBeasiswaFull->sum('penerima_l'),
            'p' => $siswaBeasiswaFull->sum('penerima_p'),
            'total' => $siswaBeasiswaFull->sum('penerima_jml'),
        ];

        return array_merge(parent::getViewData(), [
            'siswaPerKelas' => $this->paginateCollection($siswaPerKelasFull, $this->perPage, $this->pagePerKelas, ['pageName' => 'pagePerKelas']),
            'siswaPerUmur' => $this->paginateCollection($siswaPerUmurFull, $this->perPage, $this->pagePerUmur, ['pageName' => 'pagePerUmur']),
            'siswaPerAgama' => $this->paginateCollection($siswaPerAgamaFull, $this->perPage, $this->pagePerAgama, ['pageName' => 'pagePerAgama']),
            'siswaPDaerah' => $this->paginateCollection($siswaPDaerahFull, $this->perPage, $this->pagePerDaerah, ['pageName' => 'pagePerDaerah']),
            'siswaDisabilitas' => $siswaDisabilitasFull,
            'siswaBeasiswa' => $siswaBeasiswaFull,
            
            'totalSiswaPerKelas' => $totalSiswaPerKelas,
            'totalSiswaPerUmur' => $totalSiswaPerUmur,
            'totalSiswaPerAgama' => $totalSiswaPerAgama,
            'totalSiswaPDaerah' => $totalSiswaPDaerah,
            'totalSiswaDisabilitas' => $totalSiswaDisabilitas,
            'totalSiswaBeasiswa' => $totalSiswaBeasiswa,

            'totalSiswa' => Siswa::where('sekolah_id', $tenantId)->count(),
            'totalLakiLaki' => Siswa::where('sekolah_id', $tenantId)->where('jenis_kelamin', 'LIKE', 'L%')->count(),
            'totalPerempuan' => Siswa::where('sekolah_id', $tenantId)->where('jenis_kelamin', 'LIKE', 'P%')->count(),
            'perPage' => $this->perPage,
        ]);
    }

    /**
     * Get data collections for calculations
     */
    protected function getSiswaPerKelasCollection()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $rombelIds = \App\Models\Rombel::where('sekolah_id', $tenantId)->pluck('id');
        $laporanSiswas = LaporanSiswa::whereIn('rombel_id', $rombelIds)->with(['rombel', 'rekap'])->get();

        return $laporanSiswas->map(function ($ls) {
            $rekaps = $ls->rekap->groupBy('kategori');
            $getRekap = function($cat) use ($rekaps) {
                $item = $rekaps->get($cat)?->first();
                return ['l' => $item?->laki_laki ?? 0, 'p' => $item?->perempuan ?? 0, 'jml' => $item?->total ?? 0];
            };
            $awal = $getRekap('awal');
            $mutasi_masuk = $getRekap('mutasi');
            $mutasi_keluar = $getRekap('mutasi_keluar');
            $putus = $getRekap('putus');
            $mengulang = $getRekap('mengulang');
            $akhir = $getRekap('akhir');

            return [
                'rombel_id' => $ls->rombel_id,
                'nama_rombel' => $ls->rombel?->nama ?? 'Tidak Diketahui',
                'awal_bulan_l' => $awal['l'],
                'awal_bulan_p' => $awal['p'],
                'awal_bulan_jml' => $awal['jml'],
                'mutasi_l' => $mutasi_masuk['l'],
                'mutasi_p' => $mutasi_masuk['p'],
                'mutasi_jml' => $mutasi_masuk['jml'],
                'mutasi_keluar_l' => $mutasi_keluar['l'],
                'mutasi_keluar_p' => $mutasi_keluar['p'],
                'mutasi_keluar_jml' => $mutasi_keluar['jml'],
                'putus_sekolah_l' => $putus['l'],
                'putus_sekolah_p' => $putus['p'],
                'putus_sekolah_jml' => $putus['jml'],
                'mengulang_l' => $mengulang['l'],
                'mengulang_p' => $mengulang['p'],
                'mengulang_jml' => $mengulang['jml'],
                'akhir_bulan_l' => $akhir['l'],
                'akhir_bulan_p' => $akhir['p'],
                'akhir_bulan_jml' => $akhir['jml'],
            ];
        });
    }

    protected function getSiswaPerUmurCollection()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $rombels = \App\Models\Rombel::where('sekolah_id', $tenantId)->get();
        $dataByRombel = collect();

        foreach ($rombels as $rombel) {
            $item = ['rombel_id' => $rombel->id, 'nama_rombel' => $rombel->nama];
            for ($age = 13; $age <= 23; $age++) {
                $prefix = 'umur_' . $age;
                $item[$prefix . '_l'] = 0; $item[$prefix . '_p'] = 0; $item[$prefix . '_jml'] = 0;
            }
            $siswas = $rombel->siswa()->whereNotNull('tanggal_lahir')->get();
            foreach ($siswas as $s) {
                $umur = \Carbon\Carbon::parse($s->tanggal_lahir)->age;
                if ($umur >= 13 && $umur <= 23) {
                    $prefix = 'umur_' . $umur;
                    if (str_contains(strtolower($s->jenis_kelamin), 'l')) $item[$prefix . '_l']++;
                    else $item[$prefix . '_p']++;
                    $item[$prefix . '_jml']++;
                }
            }
            $dataByRombel->push($item);
        }
        return $dataByRombel;
    }

    protected function getSiswaPerAgamaCollection()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $rombels = \App\Models\Rombel::where('sekolah_id', $tenantId)->get();
        $dataByRombel = collect();

        foreach ($rombels as $rombel) {
            $item = [
                'rombel_id' => $rombel->id, 'nama_rombel' => $rombel->nama,
                'islam_l' => 0, 'islam_p' => 0, 'islam_jml' => 0,
                'kristen_protestan_l' => 0, 'kristen_protestan_p' => 0, 'kristen_protestan_jml' => 0,
                'katolik_l' => 0, 'katolik_p' => 0, 'katolik_jml' => 0,
                'hindu_l' => 0, 'hindu_p' => 0, 'hindu_jml' => 0,
                'budha_l' => 0, 'budha_p' => 0, 'budha_jml' => 0,
                'konghucu_l' => 0, 'konghucu_p' => 0, 'konghucu_jml' => 0,
            ];
            $siswas = $rombel->siswa()->get();
            foreach ($siswas as $s) {
                $agama = strtolower($s->agama);
                $field = '';
                if (str_contains($agama, 'islam')) $field = 'islam';
                elseif (str_contains($agama, 'kristen') || str_contains($agama, 'protestan')) $field = 'kristen_protestan';
                elseif (str_contains($agama, 'katolik')) $field = 'katolik';
                elseif (str_contains($agama, 'hindu')) $field = 'hindu';
                elseif (str_contains($agama, 'budha')) $field = 'budha';
                elseif (str_contains($agama, 'konghucu')) $field = 'konghucu';

                if ($field) {
                    if (str_contains(strtolower($s->jenis_kelamin), 'l')) $item[$field . '_l']++;
                    else $item[$field . '_p']++;
                    $item[$field . '_jml']++;
                }
            }
            $dataByRombel->push($item);
        }
        return $dataByRombel;
    }

    protected function getSiswaPDaerahCollection()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $rombels = \App\Models\Rombel::where('sekolah_id', $tenantId)->get();
        $dataByRombel = collect();

        foreach ($rombels as $rombel) {
            $item = [
                'rombel_id' => $rombel->id, 'nama_rombel' => $rombel->nama,
                'papua_l' => 0, 'papua_p' => 0, 'papua_jml' => 0,
                'non_papua_l' => 0, 'non_papua_p' => 0, 'non_papua_jml' => 0,
            ];
            $siswas = $rombel->siswa()->get();
            foreach ($siswas as $s) {
                $isPapua = str_contains(strtolower($s->daerah_asal ?? ''), 'papua') && !str_contains(strtolower($s->daerah_asal ?? ''), 'non');
                $prefix = $isPapua ? 'papua' : 'non_papua';
                if (str_contains(strtolower($s->jenis_kelamin), 'l')) $item[$prefix . '_l']++;
                else $item[$prefix . '_p']++;
                $item[$prefix . '_jml']++;
            }
            $dataByRombel->push($item);
        }
        return $dataByRombel;
    }

    protected function getSiswaDisabilitasCollection()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        
        $categories = [
            'tidak' => 'Tidak', 
            'tuna_netra' => 'Tuna Netra', 
            'tuna_rungu' => 'Tuna Rungu', 
            'tuna_wicara' => 'Tuna Wicara', 
            'tuna_daksa' => 'Tuna Daksa', 
            'tuna_grahita' => 'Tuna Grahita', 
            'tuna_lainnya' => 'Tuna Lainnya'
        ];

        $counts = LaporanSiswaKategori::where('jenis_kategori', 'disabilitas')
            ->whereHas('laporanSiswa.laporan', fn($q) => $q->where('sekolah_id', $tenantId))
            ->get()->groupBy('sub_kategori');

        return collect($categories)->map(function ($label, $key) use ($counts) {
            $items = $counts->get($key) ?: collect();
            return [
                'jenis_disabilitas' => $label,
                'laki_laki' => $items->sum('laki_laki'),
                'perempuan' => $items->sum('perempuan'),
                'total' => $items->sum('total'),
            ];
        })->values();
    }

    protected function getSiswaBeasiswaCollection()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        
        $categories = [
            'tidak' => 'Tidak', 
            'beasiswa_pemerintah_pusat' => 'Beasiswa Pemerintah Pusat', 
            'beasiswa_pemerintah_daerah' => 'Beasiswa Pemerintah Daerah', 
            'beasisswa_swasta' => 'Beasisswa Swasta', 
            'beasiswa_khusus' => 'Beasiswa Khusus', 
            'beasiswa_afirmasi' => 'Beasiswa Afirmasi', 
            'beasiswa_lainnya' => 'Beasiswa Lainnya'
        ];

        $counts = LaporanSiswaKategori::where('jenis_kategori', 'beasiswa')
            ->whereHas('laporanSiswa.laporan', fn($q) => $q->where('sekolah_id', $tenantId))
            ->get()->groupBy('sub_kategori');

        return collect($categories)->map(function ($label, $key) use ($counts) {
            $items = $counts->get($key) ?: collect();
            return [
                'jenis_beasiswa' => $label,
                'penerima_l' => $items->sum('laki_laki'),
                'penerima_p' => $items->sum('perempuan'),
                'penerima_jml' => $items->sum('total'),
                'keterangan' => '',
            ];
        })->values();
    }

    /**
     * @deprecated Use get...Collection methods
     */
    public function getTabelSiswaPerKelas() {}
    public function getTabelSiswaPerUmur() {}
    public function getTabelSiswaPerAgama() {}
    public function getTabelSiswaPDaerah() {}
    public function getTabelSiswaDisabilitas() {}
    public function getTabelSiswaBeasiswa() {}


    // Method untuk navigasi pagination
    public function changeTablePage(string $property, int $page): void
    {
        $allowed = ['pagePerKelas', 'pagePerUmur', 'pagePerAgama', 'pagePerDaerah', 'pagePerDisabilitas', 'pagePerBeasiswa'];
        if (in_array($property, $allowed)) {
            $this->$property = max(1, $page);
        }
    }

    public $testCounter = 0;
    public function incrementTest(): void
    {
        $this->testCounter++;
    }

    // Method untuk mengubah jumlah item per halaman
    public function updatedPerPage()
    {
        $this->resetPage();
    }

    protected function resetPage()
    {
        $this->pagePerKelas = 1;
        $this->pagePerUmur = 1;
        $this->pagePerAgama = 1;
        $this->pagePerDaerah = 1;
        $this->pagePerDisabilitas = 1;
        $this->pagePerBeasiswa = 1;
    }
}
