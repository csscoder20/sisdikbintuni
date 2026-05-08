<?php

namespace App\Filament\Widgets;

use App\Models\ActivityLog;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OperatorActivityLogWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;
    protected static ?string $heading = 'Log Aktivitas Operator Terakhir';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ActivityLog::query()
                    ->whereHas('user.roles', fn ($query) => $query->where('name', 'operator'))
                    ->latest('created_at')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Operator'),
                Tables\Columns\TextColumn::make('event')
                    ->label('Aktivitas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'login' => 'success',
                        'logout' => 'warning',
                        'created' => 'info',
                        'updated' => 'primary',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->description),
            ])
            ->paginated(false);
    }
}