<?php

namespace App\Livewire;

use Livewire\Component;
use App\Filament\Traits\HasLaporanBulananLogic;
use App\Models\Sekolah;

class ReportPreview extends Component
{
    use HasLaporanBulananLogic;

    public $activeSection = null;
    public $showSectionModal = false;

    public function mount($schoolId)
    {
        $this->schoolId = $schoolId;
        $this->isPreview = true;
        $this->initializeLaporanBulanan();
    }

    public function openSection($key)
    {
        $this->activeSection = $key;
        $this->showSectionModal = true;
    }

    public function closeSection()
    {
        $this->showSectionModal = false;
        $this->activeSection = null;
    }

    public function render()
    {
        return view('livewire.report-preview');
    }
}
