<?php

namespace App\Filament\Resources\Sekolahs\Pages;

use App\Filament\Resources\Sekolahs\SekolahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSekolahs extends ListRecords
{
    protected static string $resource = SekolahResource::class;

    public function mount(): void
    {
        parent::mount();

        if (auth()->user()->role === 'operator') {
            $sekolahId = auth()->user()->sekolah_id;

            if ($sekolahId) {
                redirect()->to(SekolahResource::getUrl('edit', ['record' => $sekolahId]));
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
