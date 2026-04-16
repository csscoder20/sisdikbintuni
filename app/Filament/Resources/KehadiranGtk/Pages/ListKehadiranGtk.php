<?php
namespace App\Filament\Resources\KehadiranGtk\Pages;
use App\Filament\Resources\KehadiranGtk\KehadiranGtkResource;
use App\Filament\Actions\ValidateChecklistAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListKehadiranGtk extends ListRecords {
    protected static string $resource = KehadiranGtkResource::class;

    public function getLaporanStatus(string $type): bool
    {
        $sekolahId = filament()->getTenant()?->id;
        $laporan = \App\Models\Laporan::where([
            'sekolah_id' => $sekolahId,
            'bulan' => (int) date('m'),
            'tahun' => (int) date('Y'),
        ])->first();

        $column = "is_{$type}_valid";
        return $laporan ? (bool) $laporan->$column : false;
    }

    protected function getHeaderActions(): array {
        return [
            CreateAction::make()
                ->label('Tambah Data')
                ->modalHeading('Tambah Rekap Kehadiran')
                ->modalSubmitActionLabel('Tambah Data')
                ->createAnother(false),
            ValidateChecklistAction::make('validateRekapKehadiran', 'rekap_kehadiran', fn() => \App\Models\KehadiranGtk::whereHas('gtk', fn($q) => $q->where('sekolah_id', filament()->getTenant()?->id))->exists()),
        ];
    }
}
