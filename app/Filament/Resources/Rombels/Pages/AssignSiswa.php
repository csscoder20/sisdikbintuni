<?php

namespace App\Filament\Resources\Rombels\Pages;

use App\Filament\Resources\Rombels\RombelResource;
use App\Models\Rombel;
use App\Models\Siswa;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

use Livewire\WithPagination;

class AssignSiswa extends Page
{
    use InteractsWithRecord;
    use WithPagination;

    protected static string $resource = RombelResource::class;

    protected string $view = 'filament.resources.rombels.pages.assign-siswa';

    public $unassignedCount = 0;
    public $assignedCount = 0;
    public $tahunAjaran;
    public $searchUnassigned = '';
    public $searchAssigned = '';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        
        $year = now()->year;
        $month = now()->month;
        $this->tahunAjaran = ($month >= 7) ? "$year/" . ($year + 1) : ($year - 1) . "/$year";

        $this->updateCounts();
    }

    public function updateCounts()
    {
        $sekolahId = $this->record->sekolah_id;

        $this->unassignedCount = Siswa::where('sekolah_id', $sekolahId)
            ->whereDoesntHave('rombel', function ($query) {
                $query->where('siswa_rombel.tahun_ajaran', $this->tahunAjaran);
            })->count();

        $this->assignedCount = $this->record->siswa()
            ->wherePivot('tahun_ajaran', $this->tahunAjaran)->count();
    }

    public function updatedSearchUnassigned()
    {
        $this->resetPage('unassignedPage');
    }

    public function updatedSearchAssigned()
    {
        $this->resetPage('assignedPage');
    }

    public function moveToRombel($siswaIds)
    {
        $ids = is_array($siswaIds) ? $siswaIds : [$siswaIds];
        
        // Enforce 1 rombel per year: remove these students from any other rombels in the same year
        \Illuminate\Support\Facades\DB::table('siswa_rombel')
            ->whereIn('siswa_id', $ids)
            ->where('tahun_ajaran', $this->tahunAjaran)
            ->delete();

        $syncData = [];
        foreach ($ids as $id) {
            $syncData[$id] = ['tahun_ajaran' => $this->tahunAjaran];
        }

        $this->record->siswa()->attach($syncData);
        
        $this->updateCounts();
        
        $count = count($ids);
        Notification::make()
            ->title($count > 1 ? "$count siswa berhasil ditambahkan" : "Siswa berhasil ditambahkan")
            ->success()
            ->send();

        $this->dispatch('clear-selection');
    }

    public function removeFromRombel($siswaIds)
    {
        $ids = is_array($siswaIds) ? $siswaIds : [$siswaIds];

        $this->record->siswa()
            ->wherePivot('tahun_ajaran', $this->tahunAjaran)
            ->detach($ids);
            
        $this->updateCounts();

        $count = count($ids);
        Notification::make()
            ->title($count > 1 ? "$count siswa berhasil dikeluarkan" : "Siswa berhasil dikeluarkan")
            ->info()
            ->send();

        $this->dispatch('clear-selection');
    }

    protected function getViewData(): array
    {
        $sekolahId = $this->record->sekolah_id;

        // Query Available Students
        $queryUnassigned = Siswa::where('sekolah_id', $sekolahId)
            ->whereDoesntHave('rombel', function ($query) {
                $query->where('siswa_rombel.tahun_ajaran', $this->tahunAjaran);
            });

        if ($this->searchUnassigned) {
            $queryUnassigned->where(function($q) {
                $q->where('nama', 'like', '%' . $this->searchUnassigned . '%')
                  ->orWhere('nisn', 'like', '%' . $this->searchUnassigned . '%');
            });
        }

        // Query Enrolled Students
        $queryAssigned = $this->record->siswa()
            ->wherePivot('tahun_ajaran', $this->tahunAjaran);
        
        if ($this->searchAssigned) {
            $queryAssigned->where(function($q) {
                $q->where('nama', 'like', '%' . $this->searchAssigned . '%')
                  ->orWhere('nisn', 'like', '%' . $this->searchAssigned . '%');
            });
        }

        return [
            'unassignedSiswa' => $queryUnassigned->paginate(12, ['*'], 'unassignedPage'),
            'assignedSiswa' => $queryAssigned->paginate(12, ['*'], 'assignedPage'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->color('gray')
                ->url(RombelResource::getUrl('index')),
        ];
    }

    public function getTitle(): string
    {
        return "Kelola Siswa : " . $this->record->nama;
    }
}
