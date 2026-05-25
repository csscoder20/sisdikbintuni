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
    public $selectedAssigned = [];
    public $selectedUnassigned = [];
    public $selectAllAssignedToggle = false;
    public $selectAllUnassignedToggle = false;

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
        $this->deselectAllUnassigned();
    }

    public function updatedSearchAssigned()
    {
        $this->resetPage('assignedPage');
        $this->deselectAllAssigned();
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

        // Clear selection if bulk operation
        if (is_array($siswaIds) && count($siswaIds) > 1) {
            $this->deselectAllUnassigned();
        }
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

        // Clear selection if bulk operation
        if (is_array($siswaIds) && count($siswaIds) > 1) {
            $this->deselectAllAssigned();
        }
    }

    public function toggleAssignedCheckbox($siswaId)
    {
        if (in_array($siswaId, $this->selectedAssigned)) {
            $this->selectedAssigned = array_filter($this->selectedAssigned, fn($id) => $id !== $siswaId);
        } else {
            $this->selectedAssigned[] = $siswaId;
        }
    }

    public function toggleUnassignedCheckbox($siswaId)
    {
        if (in_array($siswaId, $this->selectedUnassigned)) {
            $this->selectedUnassigned = array_filter($this->selectedUnassigned, fn($id) => $id !== $siswaId);
        } else {
            $this->selectedUnassigned[] = $siswaId;
        }
    }

    public function selectAllAssigned()
    {
        $sekolahId = $this->record->sekolah_id;
        $query = $this->record->siswa()
            ->wherePivot('tahun_ajaran', $this->tahunAjaran);

        if ($this->searchAssigned) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->searchAssigned . '%')
                    ->orWhere('nisn', 'like', '%' . $this->searchAssigned . '%');
            });
        }

        // Select only the IDs on the current paginated page (not all matching rows)
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage('assignedPage');
        $perPage = 12; // must match the pagination used in getViewData()
        $paginator = $query->paginate($perPage, ['*'], 'assignedPage', $page);

        $this->selectedAssigned = collect($paginator->items())->pluck('id')->toArray();
    }

    public function deselectAllAssigned()
    {
        $this->selectedAssigned = [];
        $this->selectAllAssignedToggle = false;
    }

    public function selectAllUnassigned()
    {
        $sekolahId = $this->record->sekolah_id;
        $query = Siswa::where('sekolah_id', $sekolahId)
            ->whereDoesntHave('rombel', function ($query) {
                $query->where('siswa_rombel.tahun_ajaran', $this->tahunAjaran);
            });

        if ($this->searchUnassigned) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->searchUnassigned . '%')
                    ->orWhere('nisn', 'like', '%' . $this->searchUnassigned . '%');
            });
        }

        // Select only the IDs on the current paginated page (not all matching rows)
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage('unassignedPage');
        $perPage = 12; // must match the pagination used in getViewData()
        $paginator = $query->paginate($perPage, ['*'], 'unassignedPage', $page);

        $this->selectedUnassigned = collect($paginator->items())->pluck('id')->toArray();
    }

    public function deselectAllUnassigned()
    {
        $this->selectedUnassigned = [];
        $this->selectAllUnassignedToggle = false;
    }

    public function bulkMoveToRombel()
    {
        if (empty($this->selectedUnassigned)) {
            Notification::make()
                ->title('Pilih siswa terlebih dahulu')
                ->warning()
                ->send();
            return;
        }

        $this->moveToRombel($this->selectedUnassigned);
        $this->deselectAllUnassigned();
    }

    public function bulkRemoveFromRombel()
    {
        if (empty($this->selectedAssigned)) {
            Notification::make()
                ->title('Pilih siswa terlebih dahulu')
                ->warning()
                ->send();
            return;
        }

        $this->removeFromRombel($this->selectedAssigned);
        $this->deselectAllAssigned();
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
            $queryUnassigned->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->searchUnassigned . '%')
                    ->orWhere('nisn', 'like', '%' . $this->searchUnassigned . '%');
            });
        }

        // Query Enrolled Students
        $queryAssigned = $this->record->siswa()
            ->wherePivot('tahun_ajaran', $this->tahunAjaran);

        if ($this->searchAssigned) {
            $queryAssigned->where(function ($q) {
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
