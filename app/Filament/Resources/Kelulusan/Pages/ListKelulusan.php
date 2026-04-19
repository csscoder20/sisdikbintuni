<?php
namespace App\Filament\Resources\Kelulusan\Pages;
use App\Filament\Resources\Kelulusan\KelulusanResource;
use App\Filament\Actions\ValidateChecklistAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListKelulusan extends ListRecords {
    protected static string $resource = KelulusanResource::class;

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
                ->label('Tambah Data Kelulusan')
                ->modalHeading('Tambah Data Kelulusan')
                ->modalSubmitActionLabel('Simpan Data Kelulusan')
                ->createAnother(false),
            ValidateChecklistAction::make('validateKelulusan', 'kelulusan', fn() => \App\Models\Kelulusan::where('sekolah_id', filament()->getTenant()?->id)->exists()),
        ];
    }
}
