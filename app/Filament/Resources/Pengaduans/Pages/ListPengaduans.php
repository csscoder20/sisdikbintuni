<?php

namespace App\Filament\Resources\Pengaduans\Pages;

use App\Filament\Resources\Pengaduans\PengaduanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPengaduans extends ListRecords
{
    protected static string $resource = PengaduanResource::class;

    protected function getHeaderActions(): array
    {
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
                })
                ->visible(fn() => \Filament\Facades\Filament::getCurrentPanel()?->getId() !== 'dinas'),
        ];
    }
}
