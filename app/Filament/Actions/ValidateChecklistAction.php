<?php

namespace App\Filament\Actions;

use App\Models\Laporan;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ValidateChecklistAction
{
    public static function make(string $name, string $type, ?\Closure $hasDataChecker = null): Action
    {
        return Action::make($name)
            ->label(fn ($livewire) => $livewire->getLaporanStatus($type) ? 'Valid' : 'Validasi')
            ->icon(fn ($livewire) => $livewire->getLaporanStatus($type) ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-triangle')
            ->color(fn ($livewire) => $livewire->getLaporanStatus($type) ? 'gray' : 'warning')
            ->disabled(fn ($livewire) => $livewire->getLaporanStatus($type))
            ->requiresConfirmation(function ($livewire) use ($type, $hasDataChecker) {
                $hasData = $hasDataChecker ? app()->call($hasDataChecker) : true;
                return $hasData && !$livewire->getLaporanStatus($type);
            })
            ->extraAttributes(fn ($livewire) => [
                'style' => $livewire->getLaporanStatus($type) ? 'cursor: not-allowed !important;' : '',
                'title' => $livewire->getLaporanStatus($type) ? 'Data ini sudah divalidasi' : '',
                'id' => "btn-validate-{$name}",
                'wire:loading.attr' => 'data-dummy',
                'wire:loading.class' => '',
            ])
            ->modalHeading(function () use ($hasDataChecker) {
                $hasData = $hasDataChecker ? app()->call($hasDataChecker) : true;
                return $hasData ? 'Validasi Data' : '';
            })
            ->modalDescription(function () use ($hasDataChecker) {
                $hasData = $hasDataChecker ? app()->call($hasDataChecker) : true;
                return $hasData ? 'Apakah Anda yakin seluruh data sudah benar? Tindakan ini tidak dapat dibatalkan.' : '';
            })
            ->action(function (Action $action) use ($type, $hasDataChecker) {
                if ($hasDataChecker && !app()->call($hasDataChecker)) {
                    Notification::make()
                        ->title('Data Belum Ada')
                        ->body('Belum ada data pada tabel ini. Silakan tambahkan data terlebih dahulu sebelum melakukan validasi.')
                        ->warning()
                        ->send();
                    
                    $action->halt();
                }

                $sekolahId = filament()->getTenant()?->id ?? Auth::user()->sekolah_id;
                
                // Determine current period
                $month = (int) date('m');
                $year = (int) date('Y');

                // Find or create report for current period
                $laporan = Laporan::firstOrCreate(
                    [
                        'sekolah_id' => $sekolahId,
                        'bulan' => $month,
                        'tahun' => $year,
                    ]
                );

                // Map type to column name
                $column = "is_" . Str::snake($type) . "_valid";
                
                $laporan->update([
                    $column => true,
                ]);

                Notification::make()
                    ->title("Data berhasil divalidasi untuk periode {$month}/{$year}")
                    ->success()
                    ->send();
            });
    }
}
