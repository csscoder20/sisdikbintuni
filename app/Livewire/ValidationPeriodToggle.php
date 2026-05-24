<?php

namespace App\Livewire;

use App\Support\ValidationPeriod;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Livewire\Component;

class ValidationPeriodToggle extends Component
{
    public bool $active = true;
    public bool $targetActive = true;

    public function mount(): void
    {
        $this->active = ValidationPeriod::isActive();
    }

    public function requestToggle(): void
    {
        $this->targetActive = ! $this->active;

        $this->dispatch('validation-period-toggle-confirm', [
            'targetActive' => $this->targetActive,
            'title' => $this->targetActive ? 'Buka Periode Validasi?' : 'Tutup Periode Validasi?',
            'message' => $this->targetActive
                ? 'Operator sekolah akan dapat kembali melakukan tambah, edit, hapus, upload data, dan menyimpan validasi. Pastikan periode memang sudah siap dibuka.'
                : 'Aksi tambah, edit, hapus, upload data, dan validasi di panel operator akan disembunyikan sementara. Data tetap bisa dilihat, dicetak, dan diunduh.',
            'confirmText' => $this->targetActive ? 'Ya, Buka Periode' : 'Ya, Tutup Periode',
            'icon' => $this->targetActive ? 'question' : 'warning',
            'confirmColor' => $this->targetActive ? '#16a34a' : '#dc2626',
        ]);
    }

    public function cancelToggle(): void
    {
        $this->targetActive = $this->active;
    }

    public function confirmToggle(): void
    {
        ValidationPeriod::setActive($this->targetActive);

        $this->active = $this->targetActive;

        Notification::make()
            ->title($this->active ? 'Periode validasi dibuka.' : 'Periode validasi ditutup.')
            ->body($this->active ? 'Operator sekolah dapat melakukan input, upload, dan validasi kembali.' : 'Aksi tambah, edit, hapus, upload, dan validasi di panel operator disembunyikan sementara.')
            ->success()
            ->send();
    }

    public function render()
    {
        $user = auth()->user();

        if (
            Filament::getCurrentPanel()?->getId() !== 'dinas'
            || ! $user?->hasAnyRole(['admin_dinas', 'super_admin'])
        ) {
            return <<<'HTML'
                <div></div>
            HTML;
        }

        return view('livewire.validation-period-toggle');
    }
}
