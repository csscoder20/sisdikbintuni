<?php

namespace App\Filament\Widgets;

use App\Models\Laporan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LaporanTerbaruWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 1;
    protected static ?string $heading = 'Monitoring Laporan Bulanan (Terbaru)';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Laporan::query()->latest('updated_at')->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('sekolah.nama')
                    ->label('Sekolah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('periode')
                    ->label('Periode')
                    ->getStateUsing(fn (Laporan $record) => $record->bulan . '/' . $record->tahun),
                Tables\Columns\IconColumn::make('is_identitas_sekolah_valid')
                    ->label('Profil')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_kondisi_siswa_valid')
                    ->label('Siswa')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_kondisi_gtk_valid')
                    ->label('GTK')
                    ->boolean(),
            ])
            ->paginated(false);
    }
}