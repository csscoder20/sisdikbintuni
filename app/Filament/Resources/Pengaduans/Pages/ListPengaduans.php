<?php

namespace App\Filament\Resources\Pengaduans\Pages;

use App\Filament\Resources\Pengaduans\PengaduanResource;
use App\Models\Pengaduan;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Schema;

class ListPengaduans extends ListRecords
{
    protected static string $resource = PengaduanResource::class;

    protected function getHeaderActions(): array
    {
        $isDinas = filament()->getCurrentPanel()?->getId() === 'dinas';

        if ($isDinas) {
            return [];
        }

        return [
            CreateAction::make()
                ->label('Tambah Pengaduan')
                ->modalHeading('Buat Pengaduan/Tiket')
                ->modalSubmitActionLabel('Kirim Pengaduan')
                ->createAnother(false)
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();
                    $data['sekolah_id'] = auth()->user()->sekolah_id ?? null;
                    return $data;
                }),
        ];
    }
}
