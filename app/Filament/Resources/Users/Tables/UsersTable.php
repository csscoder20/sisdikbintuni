<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Mail\OperatorVerified;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Size;
class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Alamat Surel')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Peran')
                    ->badge()
                    ->colors([
                        'primary' => 'operator',
                        'success' => 'admin_dinas',
                        'warning' => 'super_admin',
                    ]),
                TextColumn::make('sekolah.nama')
                    ->label('Sekolah')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'pending',
                        'danger' => 'rejected',
                    ]),
                TextColumn::make('email_verified_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->modalWidth(\Filament\Support\Enums\Width::FiveExtraLarge)
                        ->icon(Heroicon::OutlinedEye),
                    EditAction::make()
                        ->icon(Heroicon::OutlinedPencilSquare),
                    DeleteAction::make()
                        ->icon(Heroicon::OutlinedTrash),
                    Action::make('verify')
                        ->label('Verifikasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->hidden(fn ($record) => $record->status === 'active' || $record->hasRole(['admin_dinas', 'super_admin']))
                        ->requiresConfirmation()
                        ->modalHeading('Verifikasi Operator')
                        ->modalDescription('Dengan mengaktifkan pengguna ini, yang bersangkutan akan menerima notifikasi melalui email dan dapat masuk ke sistem. Lanjutkan?')
                        ->action(function ($record) {
                            $record->update(['status' => 'active']);

                            try {
                                Mail::to($record->email)->send(new OperatorVerified($record));
                            } catch (\Exception $e) {
                                // Silent fail if mail not configured, or handle as needed
                            }

                            Notification::make()
                                ->title('Pengguna Berhasil Diverifikasi')
                                ->body('Status pengguna telah diubah menjadi aktif.')
                                ->success()
                                ->send();
                        }),
                    Action::make('deactivate')
                        ->label('Nonaktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->hidden(fn ($record) => $record->status === 'rejected')
                        ->requiresConfirmation()
                        ->modalHeading('Nonaktifkan Pengguna')
                        ->modalDescription('Apakah Anda yakin ingin menonaktifkan pengguna ini? Pengguna yang dinonaktifkan tidak dapat masuk ke sistem.')
                        ->action(function ($record) {
                            $record->update(['status' => 'rejected']);

                            Notification::make()
                                ->title('Pengguna Dinonaktifkan')
                                ->body("Status {$record->name} telah diubah menjadi Tidak Aktif.")
                                ->success()
                                ->send();
                        }),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('primary')
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
