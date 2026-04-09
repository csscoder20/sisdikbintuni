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
use Livewire\Attributes\Url;

class KeadaanSiswa extends Page
{
    use WithPagination;

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

    /**
     * Get data siswa per kelas/rombel dengan pagination menggunakan data rekap
     */
    public function getTabelSiswaPerKelas()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        // Ambil ID Rombel yang dimiliki sekolah ini
        $rombelIds = \App\Models\Rombel::where('sekolah_id', $tenantId)->pluck('id');

        // Ambil data LaporanSiswa yang berkaitan dengan rombel sekolah ini
        $laporanSiswas = LaporanSiswa::whereIn('rombel_id', $rombelIds)
            ->with(['rombel', 'rekap'])
            ->get();

        $data = $laporanSiswas->map(function ($ls) {
            $rekaps = $ls->rekap->groupBy('kategori');
            
            $getRekap = function($cat) use ($rekaps) {
                $item = $rekaps->get($cat)?->first();
                return [
                    'l' => $item?->laki_laki ?? 0,
                    'p' => $item?->perempuan ?? 0,
                    'jml' => $item?->total ?? 0,
                ];
            };

            $awal = $getRekap('awal');
            $mutasi = $getRekap('mutasi');
            $akhir = $getRekap('akhir');
            $mengulang = $getRekap('mengulang');
            $putus = $getRekap('putus'); // Tambahan jika diperlukan

            return [
                'rombel_id' => $ls->rombel_id,
                'nama_rombel' => $ls->rombel?->nama ?? 'Tidak Diketahui',
                'awal_bulan_l' => $awal['l'],
                'awal_bulan_p' => $awal['p'],
                'awal_bulan_jml' => $awal['jml'],
                'mutasi_l' => $mutasi['l'],
                'mutasi_p' => $mutasi['p'],
                'mutasi_jml' => $mutasi['jml'],
                'akhir_bulan_l' => $akhir['l'],
                'akhir_bulan_p' => $akhir['p'],
                'akhir_bulan_jml' => $akhir['jml'],
                'pindah_sekolah_l' => $putus['l'],
                'pindah_sekolah_p' => $putus['p'],
                'pindah_sekolah_jml' => $putus['jml'],
                'mengulang_l' => $mengulang['l'],
                'mengulang_p' => $mengulang['p'],
                'mengulang_jml' => $mengulang['jml'],
            ];
        });

        return $this->paginateCollection($data, $this->perPage, $this->pagePerKelas, ['pageName' => 'pagePerKelas']);
    }

    /**
     * Get data siswa berdasarkan umur dengan pagination (Optimized)
     */
    public function getTabelSiswaPerUmur()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        
        // Ambil data rombel sekolah ini
        $rombels = \App\Models\Rombel::where('sekolah_id', $tenantId)->get();
        
        $dataByRombel = collect();

        foreach ($rombels as $rombel) {
            $item = [
                'rombel_id' => $rombel->id,
                'nama_rombel' => $rombel->nama,
            ];
            
            // Inisialisasi kolom umur
            for ($age = 13; $age <= 23; $age++) {
                $prefix = 'umur_' . $age;
                $item[$prefix . '_l'] = 0;
                $item[$prefix . '_p'] = 0;
                $item[$prefix . '_jml'] = 0;
            }

            // Ambil siswa di rombel ini
            $siswas = $rombel->siswa()->whereNotNull('tanggal_lahir')->get();
            
            foreach ($siswas as $s) {
                $umur = \Carbon\Carbon::parse($s->tanggal_lahir)->age;
                if ($umur >= 13 && $umur <= 23) {
                    $prefix = 'umur_' . $umur;
                    if (str_contains(strtolower($s->jenis_kelamin), 'l')) {
                        $item[$prefix . '_l']++;
                    } else {
                        $item[$prefix . '_p']++;
                    }
                    $item[$prefix . '_jml']++;
                }
            }
            $dataByRombel->push($item);
        }

        return $this->paginateCollection($dataByRombel, $this->perPage, $this->pagePerUmur, ['pageName' => 'pagePerUmur']);
    }

    /**
     * Get data siswa berdasarkan agama dengan pagination (Optimized)
     */
    public function getTabelSiswaPerAgama()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $rombels = \App\Models\Rombel::where('sekolah_id', $tenantId)->get();

        $dataByRombel = collect();

        foreach ($rombels as $rombel) {
            $item = [
                'rombel_id' => $rombel->id,
                'nama_rombel' => $rombel->nama,
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
                    if (str_contains(strtolower($s->jenis_kelamin), 'l')) {
                        $item[$field . '_l']++;
                    } else {
                        $item[$field . '_p']++;
                    }
                    $item[$field . '_jml']++;
                }
            }
            $dataByRombel->push($item);
        }

        return $this->paginateCollection($dataByRombel, $this->perPage, $this->pagePerAgama, ['pageName' => 'pagePerAgama']);
    }

    /**
     * Get data siswa berdasarkan daerah asal dengan pagination (Optimized)
     */
    public function getTabelSiswaPDaerah()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;
        $rombels = \App\Models\Rombel::where('sekolah_id', $tenantId)->get();

        $dataByRombel = collect();

        foreach ($rombels as $rombel) {
            $item = [
                'rombel_id' => $rombel->id,
                'nama_rombel' => $rombel->nama,
                'papua_l' => 0, 'papua_p' => 0, 'papua_jml' => 0,
                'non_papua_l' => 0, 'non_papua_p' => 0, 'non_papua_jml' => 0,
            ];

            $siswas = $rombel->siswa()->get();
            foreach ($siswas as $s) {
                $isPapua = str_contains(strtolower($s->daerah_asal ?? ''), 'papua') && !str_contains(strtolower($s->daerah_asal ?? ''), 'non');
                
                $prefix = $isPapua ? 'papua' : 'non_papua';
                if (str_contains(strtolower($s->jenis_kelamin), 'l')) {
                    $item[$prefix . '_l']++;
                } else {
                    $item[$prefix . '_p']++;
                }
                $item[$prefix . '_jml']++;
            }
            $dataByRombel->push($item);
        }

        return $this->paginateCollection($dataByRombel, $this->perPage, $this->pagePerDaerah, ['pageName' => 'pagePerDaerah']);
    }

    /**
     * Get data siswa disabilitas dengan pagination (Optimized & Tenanted)
     */
    public function getTabelSiswaDisabilitas()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        // Filter kategori yang hanya milik laporan sekolah ini
        $data = LaporanSiswaKategori::where('jenis_kategori', 'disabilitas')
            ->whereHas('laporanSiswa.laporan', function($q) use ($tenantId) {
                $q->where('sekolah_id', $tenantId);
            })
            ->get()
            ->groupBy('sub_kategori')
            ->map(function ($items, $key) {
                return [
                    'jenis_disabilitas' => $key,
                    'laki_laki' => $items->sum('laki_laki'),
                    'perempuan' => $items->sum('perempuan'),
                    'total' => $items->sum('total'),
                ];
            });

        return $this->paginateCollection(collect($data->values()), $this->perPage, $this->pagePerDisabilitas, ['pageName' => 'pagePerDisabilitas']);
    }

    /**
     * Get data siswa penerima beasiswa dengan pagination (Optimized & Tenanted)
     */
    public function getTabelSiswaBeasiswa()
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        $data = LaporanSiswaKategori::where('jenis_kategori', 'beasiswa')
            ->whereHas('laporanSiswa.laporan', function($q) use ($tenantId) {
                $q->where('sekolah_id', $tenantId);
            })
            ->get()
            ->map(function ($item) {
                return [
                    'jenis_beasiswa' => $item->sub_kategori,
                    'penerima_l' => $item->laki_laki,
                    'penerima_p' => $item->perempuan,
                    'penerima_jml' => $item->total,
                    'keterangan' => '',
                ];
            });

        return $this->paginateCollection($data, $this->perPage, $this->pagePerBeasiswa, ['pageName' => 'pagePerBeasiswa']);
    }

    protected function getViewData(): array
    {
        $tenantId = \Filament\Facades\Filament::getTenant()?->id;

        return array_merge(parent::getViewData(), [
            'siswaPerKelas' => $this->getTabelSiswaPerKelas(),
            'siswaPerUmur' => $this->getTabelSiswaPerUmur(),
            'siswaPerAgama' => $this->getTabelSiswaPerAgama(),
            'siswaPDaerah' => $this->getTabelSiswaPDaerah(),
            'siswaDisabilitas' => $this->getTabelSiswaDisabilitas(),
            'siswaBeasiswa' => $this->getTabelSiswaBeasiswa(),
            'totalSiswa' => Siswa::where('sekolah_id', $tenantId)->count(),
            'totalLakiLaki' => Siswa::where('sekolah_id', $tenantId)->where('jenis_kelamin', 'LIKE', 'L%')->count(),
            'totalPerempuan' => Siswa::where('sekolah_id', $tenantId)->where('jenis_kelamin', 'LIKE', 'P%')->count(),
            'perPage' => $this->perPage,
        ]);
    }

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
