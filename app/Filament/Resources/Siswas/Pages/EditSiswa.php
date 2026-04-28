<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;


class EditSiswa extends EditRecord
{
    protected static string $resource = SiswaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            ForceDeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $rombel = $this->record->rombel()->first();
        if ($rombel) {
            $data['rombel'] = $rombel->id;
            $data['tahun_ajaran'] = $rombel->pivot->tahun_ajaran;
        }

        return $data;
    }
}
