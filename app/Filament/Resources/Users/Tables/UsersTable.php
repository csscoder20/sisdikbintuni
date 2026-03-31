<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Mail\OperatorVerified;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

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
                TextColumn::make('role')
                    ->badge()
                    ->colors([
                        'primary' => 'operator',
                        'success' => 'admin',
                    ]),
                TextColumn::make('sekolah.nama_sekolah')
                    ->label('Sekolah')
                    ->searchable(),
                TextColumn::make('nohp')
                    ->label('No HP')
                    ->searchable(),
                IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean(),
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
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn ($record) => $record->is_verified || $record->role === 'admin')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Operator')
                    ->modalDescription('Dengan mengaktifkan user ini, yang bersangkutan akan mendapatkan notifikasi via email untuk dapat login ke system. Lanjutkan?')
                    ->action(function ($record) {
                        $record->update(['is_verified' => true]);

                        Mail::to($record->email)->send(new OperatorVerified($record));

                        Notification::make()
                            ->title('User Berhasil Diverifikasi')
                            ->body('Notifikasi email telah dikirim ke ' . $record->email)
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
