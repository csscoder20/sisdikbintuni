<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
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
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Role')
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
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    Action::make('verify')
                        ->label('Verifikasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->hidden(fn ($record) => $record->status === 'active' || $record->hasRole(['admin_dinas', 'super_admin']))
                        ->requiresConfirmation()
                        ->modalHeading('Verifikasi Operator')
                        ->modalDescription('Dengan mengaktifkan user ini, yang bersangkutan akan mendapatkan notifikasi via email untuk dapat login ke system. Lanjutkan?')
                        ->action(function ($record) {
                            $record->update(['status' => 'active']);

                            try {
                                Mail::to($record->email)->send(new OperatorVerified($record));
                            } catch (\Exception $e) {
                                // Silent fail if mail not configured, or handle as needed
                            }

                            Notification::make()
                                ->title('User Berhasil Diverifikasi')
                                ->body('Status user telah diubah menjadi Aktif.')
                                ->success()
                                ->send();
                        }),
                    Action::make('deactivate')
                        ->label('Nonaktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->hidden(fn ($record) => $record->status === 'rejected')
                        ->requiresConfirmation()
                        ->modalHeading('Nonaktifkan User')
                        ->modalDescription('Apakah Anda yakin ingin menonaktifkan user ini? User yang dinonaktifkan tidak akan bisa login ke sistem.')
                        ->action(function ($record) {
                            $record->update(['status' => 'rejected']);

                            Notification::make()
                                ->title('User Dinonaktifkan')
                                ->body("Status {$record->name} telah diubah menjadi Tidak Aktif.")
                                ->success()
                                ->send();
                        }),
                ])
                ->label('')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(Size::Small)
                ->color('primary')
                // ->button()
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
