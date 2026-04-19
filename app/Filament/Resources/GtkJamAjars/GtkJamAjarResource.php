<?php

namespace App\Filament\Resources\GtkJamAjars;

use App\Filament\Resources\GtkJamAjars\Pages\ListGtkJamAjars;
use App\Filament\Resources\GtkJamAjars\Schemas\GtkJamAjarForm;
use App\Filament\Resources\GtkJamAjars\Tables\GtkJamAjarsTable;
use App\Models\GtkJamAjar;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GtkJamAjarResource extends Resource
{
    protected static ?string $slug = 'sebaran-jam-ajar';

    protected static ?string $model = \App\Models\Mengajar::class;

    protected static ?string $modelLabel = 'Sebaran Jam Mengajar';

    protected static ?string $pluralModelLabel = 'Sebaran Jam Mengajar';
    protected static ?int $navigationSort = 4;

    protected static string | \UnitEnum | null $navigationGroup = 'Laporan Bulanan';

    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $recordTitleAttribute = 'SEBARAN JAM AJAR';

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
    }



    public static function form(Schema $schema): Schema
    {
        return GtkJamAjarForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                TextEntry::make('gtk.nama')->label('Nama GTK')->placeholder('-'),
                TextEntry::make('rombel.nama')->label('Rombel')->placeholder('-'),
                TextEntry::make('mapel.nama_mapel')->label('Mata Pelajaran')->placeholder('-'),
                TextEntry::make('jumlah_jam')->label('Jumlah Jam')->placeholder('-'),
                TextEntry::make('semester')->label('Semester')->placeholder('-'),
                TextEntry::make('tahun_ajaran')->label('Tahun Ajaran')->placeholder('-'),
            ])->columns(2);
    }



    public static function table(Table $table): Table
    {
        return GtkJamAjarsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('gtk', function (Builder $query) {
                $query->where('sekolah_id', filament()->getTenant()->id);
            });
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGtkJamAjars::route('/'),
        ];
    }
}
