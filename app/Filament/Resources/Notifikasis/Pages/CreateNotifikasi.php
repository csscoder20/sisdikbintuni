<?php

namespace App\Filament\Resources\Notifikasis\Pages;

use App\Filament\Resources\Notifikasis\NotifikasiResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\User;

class CreateNotifikasi extends CreateRecord
{
    protected static string $resource = NotifikasiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['sender_id'] = auth()->id();
        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $recipientType = $record->recipient_type;
        $targetIds = $record->target_ids;

        $usersQuery = User::whereHas('roles', fn($q) => $q->where('name', 'operator'));

        if ($recipientType === 'schools') {
            $usersQuery->whereHas('operatorSekolah', fn($q) => $q->whereIn('sekolah_id', $targetIds));
        } elseif ($recipientType === 'users') {
            $usersQuery->whereIn('id', $targetIds);
        }

        $targets = $usersQuery->get();

        foreach ($targets as $user) {
            Notification::make()
                ->title($record->subject)
                ->body(strip_tags($record->content))
                ->info()
                ->sendToDatabase($user);
        }

        Notification::make()
            ->title('Pemberitahuan Berhasil Dikirim')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
