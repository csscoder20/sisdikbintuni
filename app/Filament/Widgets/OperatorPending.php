<?php

namespace App\Filament\Widgets;

use App\Models\OperatorSekolah;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class OperatorPending extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1;

    protected static ?string $heading = 'Operator Sekolah Menunggu Verifikasi';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OperatorSekolah::query()->whereHas('user', fn (Builder $query) => $query->where('status', 'pending'))
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
                Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->icon('heroicon-m-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Aktivasi Akun Operator')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui akun operator ini?')
                    ->action(fn (OperatorSekolah $record) => $record->user->update(['status' => 'approved'])),
            ]);
    }
}