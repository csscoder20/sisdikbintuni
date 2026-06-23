<?php

namespace App\Filament\Resources\Pengaduans\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Facades\Filament;

class PengaduansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->striped()
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul Pengaduan')
                    ->searchable(),
                TextColumn::make('deskripsi')
                    ->label('Deskripsi Pengaduan')
                    ->words(15),
                TextColumn::make('created_at')
                    ->label('Tanggal Pengaduan')
                    ->date('d M Y')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->alignCenter()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'diproses' => 'warning',
                        'selesai' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->icon(Heroicon::OutlinedEye)
                        ->modalHeading('Detail Pengaduan/Tiket')
                        ->modalWidth(\Filament\Support\Enums\Width::FourExtraLarge)
                        ->extraAttributes(['x-on:click' => 'close()']),
                    EditAction::make()
                        ->icon(Heroicon::OutlinedPencilSquare)
                        ->modalHeading('Ubah Pengaduan')
                        ->modalSubmitActionLabel('Simpan Perubahan')
                        ->visible(fn($record) => Filament::getCurrentPanel()?->getId() === 'dinas' || $record->status === 'pending'),
                    DeleteAction::make()
                        ->icon(Heroicon::OutlinedTrash)
                        ->modalHeading('Hapus Pengaduan')
                        ->visible(fn($record) => Filament::getCurrentPanel()?->getId() === 'dinas' || $record->status === 'pending'),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('primary'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
