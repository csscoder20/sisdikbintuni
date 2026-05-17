<?php
 
namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Sekolah;
use App\Models\Laporan;
use Livewire\WithPagination;

class RiwayatLaporan extends Page
{
    use WithPagination;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clock';

    protected string $view = 'filament.pages.riwayat-laporan';

    protected static ?string $navigationLabel = 'Riwayat Laporan';

    protected static ?string $title = 'Riwayat Laporan Bulanan';

    protected static ?int $navigationSort = 3;

    protected static string | \UnitEnum | null $navigationGroup = 'Cetak';

    public $tahunAjaran;
    public $search = '';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole(['super_admin', 'admin_dinas']);
    }

    public function getBreadcrumbs(): array
    {
        return [
            filament()->getUrl() => 'Dashboard',
            '#' => 'Cetak',
            '' => 'Riwayat Laporan',
        ];
    }

    public function mount(): void
    {
        $currentMonth = (int) date('m');
        $currentYear = (int) date('Y');
        
        if ($currentMonth >= 7) {
            $this->tahunAjaran = $currentYear . '/' . ($currentYear + 1);
        } else {
            $this->tahunAjaran = ($currentYear - 1) . '/' . $currentYear;
        }
    }

    public function getTahunAjaranOptions(): array
    {
        $currentYear = (int) date('Y');
        $options = [];
        // Generate academic years from 3 years ago to 2 years ahead
        for ($i = -3; $i <= 2; $i++) {
            $year1 = $currentYear + $i;
            $year2 = $year1 + 1;
            $key = "{$year1}/{$year2}";
            $options[$key] = "Tahun Ajaran {$year1}/{$year2}";
        }
        return $options;
    }

    public function getPeriods(): array
    {
        if (empty($this->tahunAjaran) || !str_contains($this->tahunAjaran, '/')) {
            return [];
        }
        
        [$year1, $year2] = explode('/', $this->tahunAjaran);
        $year1 = (int) $year1;
        $year2 = (int) $year2;
        
        return [
            ['month' => 7, 'year' => $year1, 'label' => 'JULI ' . $year1],
            ['month' => 8, 'year' => $year1, 'label' => 'AGUSTUS ' . $year1],
            ['month' => 9, 'year' => $year1, 'label' => 'SEPTEMBER ' . $year1],
            ['month' => 10, 'year' => $year1, 'label' => 'OKTOBER ' . $year1],
            ['month' => 11, 'year' => $year1, 'label' => 'NOVEMBER ' . $year1],
            ['month' => 12, 'year' => $year1, 'label' => 'DESEMBER ' . $year1],
            ['month' => 1, 'year' => $year2, 'label' => 'JANUARI ' . $year2],
            ['month' => 2, 'year' => $year2, 'label' => 'FEBRUARI ' . $year2],
            ['month' => 3, 'year' => $year2, 'label' => 'MARET ' . $year2],
            ['month' => 4, 'year' => $year2, 'label' => 'APRIL ' . $year2],
            ['month' => 5, 'year' => $year2, 'label' => 'MEI ' . $year2],
            ['month' => 6, 'year' => $year2, 'label' => 'JUNI ' . $year2],
        ];
    }

    public function updatedTahunAjaran(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function getSekolahs()
    {
        $query = Sekolah::whereIn('jenjang', ['SMA', 'SMK', 'sma', 'smk']);

        if (!empty($this->search)) {
            $searchTerm = '%' . strtolower($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(nama) LIKE ?', [$searchTerm])
                  ->orWhereRaw('LOWER(npsn) LIKE ?', [$searchTerm]);
            });
        }

        return $query->orderBy('nama')
            ->paginate(10);
    }

    public function getLaporanData()
    {
        if (empty($this->tahunAjaran) || !str_contains($this->tahunAjaran, '/')) {
            return [];
        }
        
        [$year1, $year2] = explode('/', $this->tahunAjaran);
        $year1 = (int) $year1;
        $year2 = (int) $year2;
        
        $laporans = Laporan::whereIn('tahun', [$year1, $year2])
            ->get();
            
        $grouped = [];
        foreach ($laporans as $laporan) {
            $key = "{$laporan->sekolah_id}_{$laporan->tahun}_{$laporan->bulan}";
            $grouped[$key] = $laporan;
        }
        
        return $grouped;
    }
}
