<?php

namespace App\Livewire;

use App\Models\Sekolah;
use Livewire\Component;
use Filament\Facades\Filament;

class SchoolSelector extends Component
{
    public ?int $selectedSekolahId = null;
    public string $search = '';

    public function mount()
    {
        $this->selectedSekolahId = session('dinas_selected_sekolah_id');
    }

    public function updatedSelectedSekolahId($value)
    {
        session(['dinas_selected_sekolah_id' => $value]);
        
        // Refresh to apply filters
        return redirect(request()->header('Referer'));
    }

    public function selectSekolah($id)
    {
        $this->selectedSekolahId = $id;
        $this->updatedSelectedSekolahId($id);
    }

    public function render()
    {
        // Only show if in dinas panel and user is admin_dinas or super_admin
        if (Filament::getCurrentPanel()?->getId() !== 'dinas') {
            return <<<'HTML'
                <div></div>
            HTML;
        }

        $sekolahs = Sekolah::query()
            ->when($this->search, fn($q) => $q->where('nama', 'like', "%{$this->search}%"))
            ->orderBy('nama')
            ->get();

        $selectedSekolah = $this->selectedSekolahId ? Sekolah::find($this->selectedSekolahId) : null;

        return view('livewire.school-selector', [
            'sekolahs' => $sekolahs,
            'selectedSekolahNama' => $selectedSekolah?->nama ?? '-- Pilih Sekolah --',
        ]);
    }
}
