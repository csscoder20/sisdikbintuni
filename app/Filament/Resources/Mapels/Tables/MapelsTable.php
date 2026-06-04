<?php

namespace App\Filament\Resources\Mapels\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\ImportAction;
use App\Filament\Imports\MapelImporter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;


class MapelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->striped()
            ->columns([
                TextColumn::make('kode_mapel')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_mapel')
                    ->label('Nama Mata Pelajaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jjp')
                    ->alignCenter()
                    ->label('Jumlah Jam Pelajaran')
                    ->formatStateUsing(fn($state) => $state !== null && $state !== '' ? (int) $state : '-')
                    ->sortable(),
                TextColumn::make('jenjang')
                    ->label('Jenjang')
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => strtoupper($state))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tingkat')
                    ->label('Tingkat')
                    ->alignCenter()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tingkat')
                    ->label('Tingkat')
                    ->options([
                        '10' => '10',
                        '11' => '11',
                        '12' => '12',
                    ]),
                SelectFilter::make('jjp')
                    ->label('Jam Pelajaran')
                    ->options(function (HasTable $livewire): array {
                        $query = (clone $livewire->getFilteredTableQuery())
                            ->whereNotNull('jjp');

                        $tingkat = $livewire->getTableFilterState('tingkat')['value'] ?? null;

                        if (filled($tingkat)) {
                            $query->where('tingkat', $tingkat);
                        }

                        return $query
                            ->pluck('jjp')
                            ->map(fn ($value): int => (int) trim((string) $value))
                            ->filter(fn (int $value): bool => $value > 0)
                            ->unique()
                            ->sort()
                            ->mapWithKeys(fn (int $value): array => [(string) $value => (string) $value])
                            ->all();
                    }),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                    EditAction::make()
                        ->icon(Heroicon::OutlinedPencilSquare),
                    DeleteAction::make()
                        ->icon(Heroicon::OutlinedTrash),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('primary'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
