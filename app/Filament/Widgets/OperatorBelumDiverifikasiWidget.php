<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class OperatorBelumDiverifikasiWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->whereHas('roles', fn($query) => $query->where('name', 'operator'))
                    ->where('status', 'pending')
            )
            ->heading('Menunggu Verifikasi (Operator)')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('sekolah.nama')
                    ->label('Asal Sekolah'),
                TextColumn::make('created_at')
                    ->label('Waktu Daftar')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->actions([
                Action::make('Verifikasi')
                    ->url(fn () => \App\Filament\Resources\Users\UserResource::getUrl('index'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
            ]);
    }
}
