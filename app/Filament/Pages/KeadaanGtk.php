<?php

namespace App\Filament\Pages;

use App\Models\GtkAgama;
use App\Models\GtkDaerah;
use App\Models\GtkStatusKepegawaian;
use App\Models\GtkUmur;
use App\Models\GtkPendidikan;
use Filament\Pages\Page;

class KeadaanGtk extends Page
{
    protected static ?string $navigationLabel = 'KEADAAN GTK';
    protected static string | \UnitEnum | null $navigationGroup = 'LAPORAN BULANAN';
    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->role === 'operator';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-users';
    }


    protected function getViewData(): array
    {
        return array_merge(parent::getViewData(), [
            'gtkAgama' => $this->getTabelGtkAgama(),
            'gtkDaerah' => $this->getTabelGtkDaerah(),
            'gtkStatusKepegawaian' => $this->getTabelGtkStatusKepegawaian(),
            'gtkUmur' => $this->getTabelGtkUmur(),
            'gtkPendidikan' => $this->getTabelGtkPendidikan(),
        ]);
    }

    public function getTabelGtkAgama()

    {

        return GtkAgama::all();
    }


    public function getTabelGtkDaerah()
    {
        return GtkDaerah::all();
    }

    public function getTabelGtkStatusKepegawaian()
    {
        return GtkStatusKepegawaian::all();
    }

    public function getTabelGtkUmur()
    {
        return GtkUmur::all();
    }

    public function getTabelGtkPendidikan()
    {
        return GtkPendidikan::all();
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
