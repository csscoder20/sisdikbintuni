<?php

namespace App\Filament\Resources\CetakLaporan;

use App\Models\Sekolah;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Facades\Filament;
use App\Filament\Resources\CetakLaporan\Pages;

class CetakLaporanResource extends Resource
{

    protected static ?string $model = Sekolah::class;

    protected static ?string $slug = 'cetak-laporan-bulanan';

    protected static ?string $navigationLabel = 'Progres Pelaporan';

    protected static ?string $modelLabel = 'Cetak Laporan';

    protected static ?string $pluralModelLabel = 'Progres Pelaporan';

    protected static ?int $navigationSort = 1;

    protected static string | \UnitEnum | null $navigationGroup = 'Cetak';

    protected static string | \BackedEnum | null $navigationIcon = Heroicon::PresentationChartLine;

    public static function canViewAny(): bool
    {
        return auth()->check() && (auth()->user()->hasRole(['super_admin', 'admin_dinas', 'pengawas']));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Sekolah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('npsn')
                    ->label('NPSN')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('jenjang')
                    ->label('Jenjang')
                    ->badge()
                    ->formatStateUsing(fn($state) => strtoupper($state)),
                TextColumn::make('periode')
                    ->label('Periode Laporan')
                    ->state(function (Sekolah $record) {
                        $laporan = $record->laporan()->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->first();
                        if ($laporan) {
                            return \Carbon\Carbon::createFromDate($laporan->tahun, $laporan->bulan, 1)->translatedFormat('F Y');
                        }
                        return '-';
                    })
                    ->badge()
                    ->color('info'),
                TextColumn::make('alamat_lengkap')
                    ->label('Alamat')
                    ->state(function (Sekolah $record) {
                        $parts = array_filter([
                            $record->alamat,
                            $record->desa,
                            $record->kecamatan,
                            $record->kabupaten,
                            $record->provinsi,
                        ]);
                        return implode(', ', $parts);
                    })
                    ->wrap()
                    ->searchable(query: function (\Illuminate\Database\Eloquent\Builder $query, string $search) {
                        $query->where('alamat', 'like', "%{$search}%")
                            ->orWhere('desa', 'like', "%{$search}%")
                            ->orWhere('kecamatan', 'like', "%{$search}%")
                            ->orWhere('kabupaten', 'like', "%{$search}%")
                            ->orWhere('provinsi', 'like', "%{$search}%");
                    }),
                TextColumn::make('laporan_status')
                    ->label('Progres Validasi')
                    ->state(function (Sekolah $record) {
                        $stats = $record->getValidationProgress();
                        return "{$stats['done']}/{$stats['total']} (" . $stats['percentage'] . "%)";
                    })
                    ->badge()
                    ->color(function ($state) {
                        if (str_contains($state, '100%')) return 'success';
                        if (str_starts_with($state, '0/')) return 'danger';
                        return 'warning';
                    }),
            ])
            ->actions([
                Action::make('cetak')
                    ->label('Cetak Laporan')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->visible(fn(Sekolah $record) => $record->getValidationProgress()['percentage'] === 100)
                    ->modalHeading(fn(Sekolah $record) => "Pratinjau Laporan - " . $record->nama)
                    ->modalWidth('5xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->extraModalFooterActions([
                        \Filament\Actions\Action::make('download_pdf')
                            ->label('Download PDF')
                            ->color('info')
                            ->icon('heroicon-m-document-arrow-down')
                            ->url(fn(Sekolah $record) => route('cetak-laporan.pdf', $record))
                            ->openUrlInNewTab(),
                    ])
                    ->modalContent(fn(Sekolah $record) => view('livewire.report-preview-container', ['schoolId' => $record->id])),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCetakLaporans::route('/'),
        ];
    }
}
