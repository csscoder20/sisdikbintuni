<?php

namespace App\Filament\Pages;

use App\Models\SiswaKelasRombel;
use App\Models\SiswaUmur;
use App\Models\SiswaAgama;
use App\Models\SiswaDaerah;
use App\Models\SiswaDisabilitas;
use App\Models\SiswaBeasiswa;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class KeadaanSiswa extends Page
{
    protected static ?string $navigationLabel = 'KEADAAN SISWA';
    protected static ?int $navigationSort = 2;
    protected static string | \UnitEnum | null $navigationGroup = 'LAPORAN BULANAN';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->role === 'operator';
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

    public function getTabelSiswaPerKelas()
    {
        return SiswaKelasRombel::all();
    }

    public function getTabelSiswaPerUmur()
    {
        return SiswaUmur::all();
    }

    public function getTabelSiswaPerAgama()
    {
        return SiswaAgama::all();
    }

    public function getTabelSiswaPDaerah()
    {
        return SiswaDaerah::all();
    }

    public function getTabelSiswaDisabilitas()
    {
        return SiswaDisabilitas::all();
    }

    public function getTabelSiswaBeasiswa()
    {
        return SiswaBeasiswa::all();
    }

    protected function getViewData(): array
    {
        return array_merge(parent::getViewData(), [
            'siswaPerKelas' => $this->getTabelSiswaPerKelas(),
            'siswaPerUmur' => $this->getTabelSiswaPerUmur(),
            'siswaPerAgama' => $this->getTabelSiswaPerAgama(),
            'siswaPDaerah' => $this->getTabelSiswaPDaerah(),
            'siswaDisabilitas' => $this->getTabelSiswaDisabilitas(),
            'siswaBeasiswa' => $this->getTabelSiswaBeasiswa(),
        ]);
    }
}
