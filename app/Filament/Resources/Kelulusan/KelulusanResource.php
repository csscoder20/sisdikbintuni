<?php

namespace App\Filament\Resources\Kelulusan;

use App\Filament\Resources\Kelulusan\Pages;
use App\Filament\Resources\Kelulusan\Schemas\KelulusanForm;
use App\Filament\Resources\Kelulusan\Tables\KelulusanTable;
use App\Models\Kelulusan;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class KelulusanResource extends Resource
{
    protected static ?string $slug = 'kelulusan';

    protected static ?string $model = Kelulusan::class;

    protected static ?string $modelLabel = 'Kelulusan';

    protected static ?string $pluralModelLabel = 'Kelulusan';


    // Fixed: Remove the union type syntax, just use string|null

    protected static string | \UnitEnum | null $navigationGroup = 'Laporan Bulanan';

    protected static ?int $navigationSort = 6;

    protected static bool $isScopedToTenant = true;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $recordTitleAttribute = 'Data Kelulusan';

    public static function form(Schema $schema): Schema
    {
        return KelulusanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                TextEntry::make('tahun')->label('Tahun')
                    ->placeholder('-'),
                TextEntry::make('jumlah_peserta_ujian')
                    ->label('Jumlah Peserta Ujian')
                    ->placeholder('-'),
                TextEntry::make('jumlah_lulus')
                    ->label('Jumlah Lulus')->placeholder('-'),
                TextEntry::make('persentase_kelulusan')
                    ->label('Persentase Kelulusan')
                    ->suffix('%')
                    ->placeholder('-'),
                TextEntry::make('jumlah_lanjut_pt')
                    ->label('Jumlah Lanjut ke Perguruan Tinggi')
                    ->placeholder('-'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return KelulusanTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelulusan::route('/'),
        ];
    }
}
