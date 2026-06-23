<?php

namespace App\Livewire;

use App\Models\Notifikasi;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Attributes\On;
use Livewire\Component;

class NotifikasiModal extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?int $notifikasiId = null;

    /**
     * Dipanggil saat Filament Notification action men-dispatch event ini.
     */
    #[On('openNotifikasiDetail')]
    public function openDetail(int $id): void
    {
        $this->notifikasiId = $id;
        // Close the database-notifications slide-over
        $this->dispatch('close-modal', id: 'database-notifications');
        $this->mountAction('viewDetail');
    }

    public function viewDetailAction(): Action
    {
        $notifikasi = $this->notifikasiId
            ? Notifikasi::withoutTrashed()->find($this->notifikasiId)
            : null;

        $isReleaseNote = $notifikasi?->type === 'release_note';

        return Action::make('viewDetail')
            ->label('Detail')
            ->extraAttributes(['style' => 'display:none!important;'])
            ->modalHeading(function () use ($notifikasi, $isReleaseNote) {
                $prefix = $isReleaseNote ? 'Rilis Note — ' : ' ';
                return $prefix . ($notifikasi?->subject ?? 'Detail Pemberitahuan');
            })
            ->modalDescription(function () use ($notifikasi) {
                if (! $notifikasi) return null;
                return \Carbon\Carbon::parse($notifikasi->created_at)
                    ->translatedFormat('d F Y, H:i') . ' WIT  ·  Pengirim: ' . ($notifikasi->sender?->name ?? 'Sistem');
            })
            ->modalContent(function () use ($notifikasi) {
                return view('notifikasi.modal-content', compact('notifikasi'));
            })
            ->modalWidth('2xl')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup')
            ->color($isReleaseNote ? 'success' : 'info');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.notifikasi-modal');
    }
}
