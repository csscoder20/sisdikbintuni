<?php

namespace App\Filament\Resources\Notifikasis\Pages;

use App\Filament\Resources\Notifikasis\NotifikasiResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use App\Models\User;
use Illuminate\Support\Str;

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
        $isReleaseNote = $record->type === 'release_note';

        $usersQuery = User::whereHas('roles', fn($q) => $q->where('name', 'operator'));

        if ($recipientType === 'schools') {
            $usersQuery->whereHas('operatorSekolah', fn($q) => $q->whereIn('sekolah_id', $targetIds));
        } elseif ($recipientType === 'users') {
            $usersQuery->whereIn('id', $targetIds);
        }

        $targets = $usersQuery->get();

        foreach ($targets as $user) {
            $notification = Notification::make()
                ->title($record->subject)
                ->body(Str::limit(strip_tags($record->content), 80));

            if ($isReleaseNote) {
                $notification
                    ->icon('heroicon-o-rocket-launch')
                    ->color('success')
                    ->actions([
                        Action::make('detail')
                            ->label('Lihat Detail')
                            ->button()
                            ->color('success')
                            ->dispatch('openNotifikasiDetail', ['id' => $record->id])
                            ->markAsRead(),
                    ]);
            } else {
                $notification->info();
            }

            $notification->sendToDatabase($user);
        }

    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Pemberitahuan Berhasil Dikirim');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Kirim Pemberitahuan')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }
}
