<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\ImportAction;
use App\Filament\Imports\GtkPendidikanImporter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GtkRiwayatPendidikansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gtk.nama')
                    ->label('Nama GTK')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('thn_tamat_sd')
                    ->label('Tamat SD')
                    ->sortable(),
                TextColumn::make('thn_tamat_smp')
                    ->label('Tamat SMP')
                    ->sortable(),
                TextColumn::make('thn_tamat_sma')
                    ->label('Tamat SMA')
                    ->sortable(),
                
                // Fields available in toggle menu
                TextColumn::make('thn_tamat_d1')
                    ->label('Tamat D1')
                    ->sortable(),
                TextColumn::make('jurusan_d1')
                    ->label('Jurusan D1'),
                TextColumn::make('perguruan_tinggi_d1')
                    ->label('PT D1'),
                
                TextColumn::make('thn_tamat_d2')
                    ->label('Tamat D2')
                    ->sortable(),
                TextColumn::make('jurusan_d2')
                    ->label('Jurusan D2'),
                TextColumn::make('perguruan_tinggi_d2')
                    ->label('PT D2'),
                
                TextColumn::make('thn_tamat_d3')
                    ->label('Tamat D3')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jurusan_d3')
                    ->label('Jurusan D3')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('perguruan_tinggi_d3')
                    ->label('PT D3')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('thn_tamat_s1')
                    ->label('Tamat S1')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jurusan_s1')
                    ->label('Jurusan S1')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('perguruan_tinggi_s1')
                    ->label('PT S1')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('thn_tamat_s2')
                    ->label('Tamat S2')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jurusan_s2')
                    ->label('Jurusan S2')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('perguruan_tinggi_s2')
                    ->label('PT S2')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('thn_tamat_s3')
                    ->label('Tamat S3')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jurusan_s3')
                    ->label('Jurusan S3')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('perguruan_tinggi_s3')
                    ->label('PT S3')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('thn_akta4')
                    ->label('Tamat Akta IV')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jurusan_akta4')
                    ->label('Jurusan Akta IV')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('perguruan_tinggi_akta4')
                    ->label('PT Akta IV')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('gelar_akademik')
                    ->label('Gelar Akademik')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->icon(Heroicon::OutlinedEye),
                    EditAction::make()
                        ->icon(Heroicon::OutlinedPencilSquare),
                    DeleteAction::make()
                        ->icon(Heroicon::OutlinedTrash),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('primary')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
