<?php

namespace App\Filament\Pages;

use App\Models\LaporanSiswa;
use App\Models\LaporanSiswaRekap;
use App\Models\LaporanSiswaKategori;
use App\Models\Laporan;
use App\Models\Rombel;
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

    protected function getActiveLaporanId()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        // If explicitly set to empty string (Sekarang)
        if ($this->selectedLaporanId === "") {
            return null;
        }

        if ($this->selectedLaporanId) {
            return $this->selectedLaporanId;
        }

        // Default behavior: if no selection, show the latest report if it exists
        $lap = Laporan::where('sekolah_id', $tenantId)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->first();

        return $lap?->id;
    }

    protected function getViewData(): array
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $laporanId = $this->getActiveLaporanId();

        $periodes = \App\Models\Laporan::where('sekolah_id', $tenantId)
            ->whereHas('siswa.kategori') // Only show periods that have been validated (have kategori data)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        // 1. Siswa per Kelas
        $siswaPerKelasFull = $this->getSiswaPerKelasCollection($laporanId);
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
            'putus_jml' => $siswaPerKelasFull->sum('putus_jml'),
            'mengulang_l' => $siswaPerKelasFull->sum('mengulang_l'),
            'mengulang_p' => $siswaPerKelasFull->sum('mengulang_p'),
            'mengulang_jml' => $siswaPerKelasFull->sum('mengulang_jml'),
            'akhir_l' => $siswaPerKelasFull->sum('akhir_bulan_l'),
            'akhir_p' => $siswaPerKelasFull->sum('akhir_bulan_p'),
            'akhir_jml' => $siswaPerKelasFull->sum('akhir_bulan_jml'),
        ];

        // 2. Siswa per Umur
        $siswaPerUmurFull = $this->getSiswaPerUmurCollection($laporanId);
        $totalSiswaPerUmur = [];
        for ($age = 13; $age <= 23; $age++) {
            $px = 'umur_' . $age;
            $totalSiswaPerUmur[$px . '_l'] = $siswaPerUmurFull->sum($px . '_l');
            $totalSiswaPerUmur[$px . '_p'] = $siswaPerUmurFull->sum($px . '_p');
            $totalSiswaPerUmur[$px . '_jml'] = $siswaPerUmurFull->sum($px . '_jml');
        }

        // 3. Siswa per Agama
        $siswaPerAgamaFull = $this->getSiswaPerAgamaCollection($laporanId);
        $totalSiswaPerAgama = [];
        foreach (['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'khonghucu'] as $ag) {
            $totalSiswaPerAgama[$ag . '_l'] = $siswaPerAgamaFull->sum($ag . '_l');
            $totalSiswaPerAgama[$ag . '_p'] = $siswaPerAgamaFull->sum($ag . '_p');
            $totalSiswaPerAgama[$ag . '_jml'] = $siswaPerAgamaFull->sum($ag . '_jml');
        }

        // 4. Siswa per Daerah
        $siswaPDaerahFull = $this->getSiswaPDaerahCollection($laporanId);
        $totalSiswaPDaerah = [
            'papua_l' => $siswaPDaerahFull->sum('papua_l'),
            'papua_p' => $siswaPDaerahFull->sum('papua_p'),
            'papua_jml' => $siswaPDaerahFull->sum('papua_jml'),
            'non_papua_l' => $siswaPDaerahFull->sum('non_papua_l'),
            'non_papua_p' => $siswaPDaerahFull->sum('non_papua_p'),
            'non_papua_jml' => $siswaPDaerahFull->sum('non_papua_jml'),
        ];

        // 5. Siswa Disabilitas
        $siswaDisabilitasFull = $this->getSiswaDisabilitasCollection($laporanId);
        $totalSiswaDisabilitas = [
            'l' => $siswaDisabilitasFull->sum('laki_laki'),
            'p' => $siswaDisabilitasFull->sum('perempuan'),
            'total' => $siswaDisabilitasFull->sum('total'),
        ];

        // 6. Siswa Beasiswa
        $siswaBeasiswaFull = $this->getSiswaBeasiswaCollection($laporanId);
        $totalSiswaBeasiswa = [
            'l' => $siswaBeasiswaFull->sum('penerima_l'),
            'p' => $siswaBeasiswaFull->sum('penerima_p'),
            'total' => $siswaBeasiswaFull->sum('penerima_jml'),
        ];

        return array_merge(parent::getViewData(), [
            'periodes' => $periodes,
            'selectedLaporanId' => $laporanId,
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

            'totalSiswa' => $laporanId ? $totalSiswaPerKelas['akhir_jml'] : Siswa::where('sekolah_id', $tenantId)->count(),
            'totalLakiLaki' => $laporanId ? $totalSiswaPerKelas['akhir_l'] : Siswa::where('sekolah_id', $tenantId)->where('jenis_kelamin', 'LIKE', 'L%')->count(),
            'totalPerempuan' => $laporanId ? $totalSiswaPerKelas['akhir_p'] : Siswa::where('sekolah_id', $tenantId)->where('jenis_kelamin', 'LIKE', 'P%')->count(),
            'perPage' => $this->perPage,
        ]);
    }

    /**
     * Get data collections for calculations
     */
    protected function getSiswaPerKelasCollection($laporanId = null)
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $rombels = \App\Models\Rombel::where('sekolah_id', $tenantId)->get();

        if ($laporanId) {
            $laporanSiswas = LaporanSiswa::with(['rombel', 'rekap'])
                ->where('laporan_id', $laporanId)
                ->get()
                ->keyBy('rombel_id');

            $data = $rombels->map(function ($rombel) use ($laporanSiswas) {
                $ls = $laporanSiswas->get($rombel->id);
                
                if ($ls) {
                    $rekaps = $ls->rekap->groupBy('kategori');
                    $getRekap = function ($cat) use ($rekaps) {
                        $item = $rekaps->get($cat)?->first();
                        return ['l' => $item?->laki_laki ?? 0, 'p' => $item?->perempuan ?? 0, 'jml' => $item?->total ?? 0];
                    };
                    $awal = $getRekap('awal_bulan');
                    $mutasi_masuk = $getRekap('mutasi_masuk');
                    $mutasi_keluar = $getRekap('mutasi_keluar');
                    $putus = $getRekap('putus_sekolah');
                    $mengulang = $getRekap('mengulang');
                    $akhir = $getRekap('akhir_bulan');

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
                } else {
                    return [
                        'rombel_id' => $rombel->id,
                        'nama_rombel' => $rombel->nama,
                        'awal_bulan_l' => 0, 'awal_bulan_p' => 0, 'awal_bulan_jml' => 0,
                        'mutasi_l' => 0, 'mutasi_p' => 0, 'mutasi_jml' => 0,
                        'mutasi_keluar_l' => 0, 'mutasi_keluar_p' => 0, 'mutasi_keluar_jml' => 0,
                        'putus_sekolah_l' => 0, 'putus_sekolah_p' => 0, 'putus_sekolah_jml' => 0,
                        'mengulang_l' => 0, 'mengulang_p' => 0, 'mengulang_jml' => 0,
                        'akhir_bulan_l' => 0, 'akhir_bulan_p' => 0, 'akhir_bulan_jml' => 0,
                    ];
                }
            });

            // Tambahkan data TANPA ROMBEL jika ada di snapshot (rombel_id null)
            $lsNone = $laporanSiswas->get(""); // keyBy('rombel_id') handles null as empty string key sometimes or check specifically
            if (!$lsNone) $lsNone = $laporanSiswas->get(null);
            
            if ($lsNone) {
                $rekaps = $lsNone->rekap->groupBy('kategori');
                $getRekap = function ($cat) use ($rekaps) {
                    $item = $rekaps->get($cat)?->first();
                    return ['l' => $item?->laki_laki ?? 0, 'p' => $item?->perempuan ?? 0, 'jml' => $item?->total ?? 0];
                };
                $awal = $getRekap('awal_bulan');
                $mutasi_masuk = $getRekap('mutasi_masuk');
                $mutasi_keluar = $getRekap('mutasi_keluar');
                $putus = $getRekap('putus_sekolah');
                $mengulang = $getRekap('mengulang');
                $akhir = $getRekap('akhir_bulan');

                $data->push([
                    'rombel_id' => 'none',
                    'nama_rombel' => 'TANPA ROMBEL',
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
                ]);
            }

            return $data;
        }

        // LIVE DATA
        $data = $rombels->map(function ($rombel) {
            $laki = $rombel->siswa()->where('jenis_kelamin', 'LIKE', 'L%')->count();
            $perempuan = $rombel->siswa()->where('jenis_kelamin', 'LIKE', 'P%')->count();
            return [
                'rombel_id' => $rombel->id,
                'nama_rombel' => $rombel->nama,
                'awal_bulan_l' => 0,
                'awal_bulan_p' => 0,
                'awal_bulan_jml' => 0,
                'mutasi_l' => 0,
                'mutasi_p' => 0,
                'mutasi_jml' => 0,
                'mutasi_keluar_l' => 0,
                'mutasi_keluar_p' => 0,
                'mutasi_keluar_jml' => 0,
                'putus_sekolah_l' => 0,
                'putus_sekolah_p' => 0,
                'putus_sekolah_jml' => 0,
                'mengulang_l' => 0,
                'mengulang_p' => 0,
                'mengulang_jml' => 0,
                'akhir_bulan_l' => $laki,
                'akhir_bulan_p' => $perempuan,
                'akhir_bulan_jml' => $laki + $perempuan,
            ];
        });

        // Add students without rombel
        $noRombelL = Siswa::where('sekolah_id', $tenantId)->whereDoesntHave('rombel')->where('jenis_kelamin', 'LIKE', 'L%')->count();
        $noRombelP = Siswa::where('sekolah_id', $tenantId)->whereDoesntHave('rombel')->where('jenis_kelamin', 'LIKE', 'P%')->count();

        if ($noRombelL + $noRombelP > 0) {
            $data->push([
                'rombel_id' => 'none',
                'nama_rombel' => 'TANPA ROMBEL',
                'awal_bulan_l' => 0, 'awal_bulan_p' => 0, 'awal_bulan_jml' => 0,
                'mutasi_l' => 0, 'mutasi_p' => 0, 'mutasi_jml' => 0,
                'mutasi_keluar_l' => 0, 'mutasi_keluar_p' => 0, 'mutasi_keluar_jml' => 0,
                'putus_sekolah_l' => 0, 'putus_sekolah_p' => 0, 'putus_sekolah_jml' => 0,
                'mengulang_l' => 0, 'mengulang_p' => 0, 'mengulang_jml' => 0,
                'akhir_bulan_l' => $noRombelL,
                'akhir_bulan_p' => $noRombelP,
                'akhir_bulan_jml' => $noRombelL + $noRombelP,
            ]);
        }

        return $data;
    }

    protected function getSiswaPerUmurCollection($laporanId = null)
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $rombels = \App\Models\Rombel::where('sekolah_id', $tenantId)->get();
        $dataByRombel = collect();

        if ($laporanId) {
            $laporanSiswas = LaporanSiswa::where('laporan_id', $laporanId)->with('kategori', 'rombel')->get()->keyBy('rombel_id');

            foreach ($rombels as $rombel) {
                $item = ['rombel_id' => $rombel->id, 'nama_rombel' => $rombel->nama];
                for ($age = 13; $age <= 23; $age++) {
                    $prefix = 'umur_' . $age;
                    $item[$prefix . '_l'] = 0; $item[$prefix . '_p'] = 0; $item[$prefix . '_jml'] = 0;
                }

                $ls = $laporanSiswas->get($rombel->id);
                if ($ls) {
                    $umurKats = $ls->kategori->where('jenis_kategori', 'umur')->groupBy('sub_kategori');
                    foreach ($umurKats as $sub => $group) {
                        $age = (int) $sub;
                        if ($age >= 13 && $age <= 23) {
                            $prefix = 'umur_' . $age;
                            $item[$prefix . '_l'] = $group->sum('laki_laki');
                            $item[$prefix . '_p'] = $group->sum('perempuan');
                            $item[$prefix . '_jml'] = $group->sum('total');
                        }
                    }
                }
                $dataByRombel->push($item);
            }
            return $dataByRombel;
        }

        // LIVE DATA
        foreach ($rombels as $rombel) {
            $item = ['rombel_id' => $rombel->id, 'nama_rombel' => $rombel->nama];
            for ($age = 13; $age <= 23; $age++) {
                $prefix = 'umur_' . $age;
                $item[$prefix . '_l'] = 0; $item[$prefix . '_p'] = 0; $item[$prefix . '_jml'] = 0;
            }

            $siswas = $rombel->siswa()->whereNotNull('tanggal_lahir')->get();
            foreach ($siswas as $s) {
                $age = \Carbon\Carbon::parse($s->tanggal_lahir)->age;
                if ($age >= 13 && $age <= 23) {
                    $prefix = 'umur_' . $age;
                    if (str_starts_with(strtoupper($s->jenis_kelamin), 'L')) $item[$prefix . '_l']++;
                    else $item[$prefix . '_p']++;
                    $item[$prefix . '_jml']++;
                }
            }
            $dataByRombel->push($item);
        }

        // Students without rombel
        $siswasNoR = Siswa::where('sekolah_id', $tenantId)->whereDoesntHave('rombel')->whereNotNull('tanggal_lahir')->get();
        if ($siswasNoR->count() > 0) {
            $item = ['rombel_id' => 'none', 'nama_rombel' => 'TANPA ROMBEL'];
            for ($age = 13; $age <= 23; $age++) { $prefix = 'umur_' . $age; $item[$prefix . '_l'] = 0; $item[$prefix . '_p'] = 0; $item[$prefix . '_jml'] = 0; }
            foreach ($siswasNoR as $s) {
                $age = \Carbon\Carbon::parse($s->tanggal_lahir)->age;
                if ($age >= 13 && $age <= 23) {
                    $prefix = 'umur_' . $age;
                    if (str_starts_with(strtoupper($s->jenis_kelamin), 'L')) $item[$prefix . '_l']++;
                    else $item[$prefix . '_p']++;
                    $item[$prefix . '_jml']++;
                }
            }
            $dataByRombel->push($item);
        }

        return $dataByRombel;
    }

    protected function getSiswaPerAgamaCollection($laporanId = null)
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $rombels = \App\Models\Rombel::where('sekolah_id', $tenantId)->get();
        $dataByRombel = collect();

        if ($laporanId) {
            $laporanSiswas = LaporanSiswa::where('laporan_id', $laporanId)->with('kategori', 'rombel')->get()->keyBy('rombel_id');

            foreach ($rombels as $rombel) {
                $item = [
                    'rombel_id' => $rombel->id,
                    'nama_rombel' => $rombel->nama,
                    'islam_l' => 0, 'islam_p' => 0, 'islam_jml' => 0,
                    'kristen_l' => 0, 'kristen_p' => 0, 'kristen_jml' => 0,
                    'katolik_l' => 0, 'katolik_p' => 0, 'katolik_jml' => 0,
                    'hindu_l' => 0, 'hindu_p' => 0, 'hindu_jml' => 0,
                    'buddha_l' => 0, 'buddha_p' => 0, 'buddha_jml' => 0,
                    'khonghucu_l' => 0, 'khonghucu_p' => 0, 'khonghucu_jml' => 0,
                ];

                $ls = $laporanSiswas->get($rombel->id);
                if ($ls) {
                    $agamaKats = $ls->kategori->where('jenis_kategori', 'agama')->groupBy('sub_kategori');
                    foreach (['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'khonghucu'] as $ag) {
                        $group = $agamaKats->get($ag);
                        if ($group) {
                            $item[$ag . '_l'] = $group->sum('laki_laki');
                            $item[$ag . '_p'] = $group->sum('perempuan');
                            $item[$ag . '_jml'] = $group->sum('total');
                        }
                    }
                }
                $dataByRombel->push($item);
            }
            return $dataByRombel;
        }

        // LIVE DATA
        foreach ($rombels as $rombel) {
            $item = [
                'rombel_id' => $rombel->id,
                'nama_rombel' => $rombel->nama,
                'islam_l' => 0, 'islam_p' => 0, 'islam_jml' => 0,
                'kristen_l' => 0, 'kristen_p' => 0, 'kristen_jml' => 0,
                'katolik_l' => 0, 'katolik_p' => 0, 'katolik_jml' => 0,
                'hindu_l' => 0, 'hindu_p' => 0, 'hindu_jml' => 0,
                'buddha_l' => 0, 'buddha_p' => 0, 'buddha_jml' => 0,
                'khonghucu_l' => 0, 'khonghucu_p' => 0, 'khonghucu_jml' => 0,
            ];

            $siswas = $rombel->siswa()->get();
            foreach ($siswas as $s) {
                $agama = strtolower($s->agama ?? '');
                $field = null;
                if (str_contains($agama, 'islam')) $field = 'islam';
                elseif (str_contains($agama, 'kristen') || str_contains($agama, 'protestan')) $field = 'kristen';
                elseif (str_contains($agama, 'katolik')) $field = 'katolik';
                elseif (str_contains($agama, 'hindu')) $field = 'hindu';
                elseif (str_contains($agama, 'budha') || str_contains($agama, 'buddha')) $field = 'buddha';
                elseif (str_contains($agama, 'konghucu') || str_contains($agama, 'khonghucu')) $field = 'khonghucu';

                if ($field) {
                    if (str_starts_with(strtoupper($s->jenis_kelamin), 'L')) $item[$field . '_l']++;
                    else $item[$field . '_p']++;
                    $item[$field . '_jml']++;
                }
            }
            $dataByRombel->push($item);
        }

        // Students without rombel
        $siswasNoR = Siswa::where('sekolah_id', $tenantId)->whereDoesntHave('rombel')->get();
        if ($siswasNoR->count() > 0) {
            $item = [
                'rombel_id' => 'none', 'nama_rombel' => 'TANPA ROMBEL',
                'islam_l' => 0, 'islam_p' => 0, 'islam_jml' => 0,
                'kristen_l' => 0, 'kristen_p' => 0, 'kristen_jml' => 0,
                'katolik_l' => 0, 'katolik_p' => 0, 'katolik_jml' => 0,
                'hindu_l' => 0, 'hindu_p' => 0, 'hindu_jml' => 0,
                'buddha_l' => 0, 'buddha_p' => 0, 'buddha_jml' => 0,
                'khonghucu_l' => 0, 'khonghucu_p' => 0, 'khonghucu_jml' => 0,
            ];
            foreach ($siswasNoR as $s) {
                $agama = strtolower($s->agama ?? '');
                $field = null;
                if (str_contains($agama, 'islam')) $field = 'islam';
                elseif (str_contains($agama, 'kristen') || str_contains($agama, 'protestan')) $field = 'kristen';
                elseif (str_contains($agama, 'katolik')) $field = 'katolik';
                elseif (str_contains($agama, 'hindu')) $field = 'hindu';
                elseif (str_contains($agama, 'budha') || str_contains($agama, 'buddha')) $field = 'buddha';
                elseif (str_contains($agama, 'konghucu') || str_contains($agama, 'khonghucu')) $field = 'khonghucu';

                if ($field) {
                    if (str_starts_with(strtoupper($s->jenis_kelamin), 'L')) $item[$field . '_l']++;
                    else $item[$field . '_p']++;
                    $item[$field . '_jml']++;
                }
            }
            $dataByRombel->push($item);
        }

        return $dataByRombel;
    }

    protected function getSiswaPDaerahCollection($laporanId = null)
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $rombels = \App\Models\Rombel::where('sekolah_id', $tenantId)->get();
        $dataByRombel = collect();

        if ($laporanId) {
            $laporanSiswas = LaporanSiswa::where('laporan_id', $laporanId)->with('kategori', 'rombel')->get()->keyBy('rombel_id');

            foreach ($rombels as $rombel) {
                $item = [
                    'rombel_id' => $rombel->id,
                    'nama_rombel' => $rombel->nama,
                    'papua_l' => 0, 'papua_p' => 0, 'papua_jml' => 0,
                    'non_papua_l' => 0, 'non_papua_p' => 0, 'non_papua_jml' => 0,
                ];

                $ls = $laporanSiswas->get($rombel->id);
                if ($ls) {
                    $daerahKats = $ls->kategori->where('jenis_kategori', 'asal_daerah')->groupBy('sub_kategori');
                    foreach (['papua', 'non_papua'] as $sub) {
                        $group = $daerahKats->get($sub);
                        if ($group) {
                            $item[$sub . '_l'] = $group->sum('laki_laki');
                            $item[$sub . '_p'] = $group->sum('perempuan');
                            $item[$sub . '_jml'] = $group->sum('total');
                        }
                    }
                }
                $dataByRombel->push($item);
            }
            return $dataByRombel;
        }

        // LIVE DATA
        foreach ($rombels as $rombel) {
            $item = [
                'rombel_id' => $rombel->id,
                'nama_rombel' => $rombel->nama,
                'papua_l' => 0, 'papua_p' => 0, 'papua_jml' => 0,
                'non_papua_l' => 0, 'non_papua_p' => 0, 'non_papua_jml' => 0,
            ];

            $siswas = $rombel->siswa()->get();
            foreach ($siswas as $s) {
                $daerah = strtolower($s->daerah_asal ?? '');
                $cat = (str_contains($daerah, 'papua') && !str_contains($daerah, 'non')) ? 'papua' : 'non_papua';
                if (str_starts_with(strtoupper($s->jenis_kelamin), 'L')) $item[$cat . '_l']++;
                else $item[$cat . '_p']++;
                $item[$cat . '_jml']++;
            }
            $dataByRombel->push($item);
        }

        // Students without rombel
        $siswasNoR = Siswa::where('sekolah_id', $tenantId)->whereDoesntHave('rombel')->get();
        if ($siswasNoR->count() > 0) {
            $item = [
                'rombel_id' => 'none', 'nama_rombel' => 'TANPA ROMBEL',
                'papua_l' => 0, 'papua_p' => 0, 'papua_jml' => 0,
                'non_papua_l' => 0, 'non_papua_p' => 0, 'non_papua_jml' => 0,
            ];
            foreach ($siswasNoR as $s) {
                $daerah = strtolower($s->daerah_asal ?? '');
                $cat = (str_contains($daerah, 'papua') && !str_contains($daerah, 'non')) ? 'papua' : 'non_papua';
                if (str_starts_with(strtoupper($s->jenis_kelamin), 'L')) $item[$cat . '_l']++;
                else $item[$cat . '_p']++;
                $item[$cat . '_jml']++;
            }
            $dataByRombel->push($item);
        }

        return $dataByRombel;
    }

    protected function getSiswaDisabilitasCollection($laporanId = null)
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

        if ($laporanId) {
            $counts = LaporanSiswaKategori::where('jenis_kategori', 'disabilitas')
                ->whereHas('laporanSiswa', fn($q) => $q->where('laporan_id', $laporanId))
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

        // LIVE DATA
        return collect($categories)->map(function ($label, $key) use ($tenantId) {
            $l = Siswa::where('sekolah_id', $tenantId)->where('disabilitas', $key)->where('jenis_kelamin', 'LIKE', 'L%')->count();
            $p = Siswa::where('sekolah_id', $tenantId)->where('disabilitas', $key)->where('jenis_kelamin', 'LIKE', 'P%')->count();
            return [
                'jenis_disabilitas' => $label,
                'laki_laki' => $l,
                'perempuan' => $p,
                'total' => $l + $p,
            ];
        })->values();
    }

    protected function getSiswaBeasiswaCollection($laporanId = null)
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $categories = [
            'tidak' => 'Tidak',
            'beasiswa_pemerintah_pusat' => 'Beasiswa Pemerintah Pusat',
            'beasiswa_pemerintah_daerah' => 'Beasiswa Pemerintah Daerah',
            'beasisswa_swasta' => 'Beasiswa Swasta',
            'beasiswa_khusus' => 'Beasiswa Khusus',
            'beasiswa_afirmasi' => 'Beasiswa Afirmasi',
            'beasiswa_lainnya' => 'Beasiswa Lainnya'
        ];

        if ($laporanId) {
            $counts = LaporanSiswaKategori::where('jenis_kategori', 'beasiswa')
                ->whereHas('laporanSiswa', fn($q) => $q->where('laporan_id', $laporanId))
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

        // LIVE DATA
        return collect($categories)->map(function ($label, $key) use ($tenantId) {
            $l = Siswa::where('sekolah_id', $tenantId)->where('beasiswa', $key)->where('jenis_kelamin', 'LIKE', 'L%')->count();
            $p = Siswa::where('sekolah_id', $tenantId)->where('beasiswa', $key)->where('jenis_kelamin', 'LIKE', 'P%')->count();
            return [
                'jenis_beasiswa' => $label,
                'penerima_l' => $l,
                'penerima_p' => $p,
                'penerima_jml' => $l + $p,
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

    public function updatedSelectedLaporanId()
    {
        $this->resetPage();
    }

    public function handleLaporanUpdated($laporanId)
    {
        $this->selectedLaporanId = $laporanId;
        $this->resetPage();
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
