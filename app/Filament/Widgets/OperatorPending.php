<?php

namespace App\Filament\Widgets;

use App\Models\OperatorSekolah;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class OperatorPending extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Operator Sekolah Menunggu Verifikasi';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OperatorSekolah::query()->where('status', 'pending')
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Operator')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('sekolah.nama')
                    ->label('Sekolah')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Mendaftar Pada')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                // Memanggil langsung class Action dari namespace Tables
                \Filament\Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->icon('heroicon-m-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Aktivasi Akun Operator')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui akun operator ini?')
                    ->action(fn (OperatorSekolah $record) => $record->update(['status' => 'approved'])),
            ]);
    }
}