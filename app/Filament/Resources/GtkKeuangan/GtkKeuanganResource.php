<?php

namespace App\Filament\Resources\GtkKeuangan;

use App\Filament\Resources\GtkKeuangan\Pages;
use App\Filament\Resources\GtkKeuangan\Schemas\GtkKeuanganForm;
use App\Filament\Resources\GtkKeuangan\Tables\GtkKeuanganTable;
use App\Models\GtkKeuangan;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class GtkKeuanganResource extends Resource
{
    protected static ?string $slug = 'rekening-npwp-gtk';

    protected static ?string $model = GtkKeuangan::class;

    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Rekening & NPWP';

    protected static ?string $pluralModelLabel = 'Rekening & NPWP';

    // Fixed: Remove the union type syntax, just use string|null

    protected static string | \UnitEnum | null $navigationGroup = 'Data GTK';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static bool $isScopedToTenant = false;

    protected static ?string $recordTitleAttribute = 'NOMINATIF GTK';

    public static function form(Schema $schema): Schema
    {
        return GtkKeuanganForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                TextEntry::make('gtk.nama')->label('Nama GTK')->prefix(': ')->placeholder('-'),
                TextEntry::make('nama_bank')->label('Nama Bank')->prefix(': ')->placeholder('-'),
                TextEntry::make('nomor_rekening')->label('Nomor Rekening')->prefix(': ')->placeholder('-'),
                TextEntry::make('npwp')->label('NPWP')->prefix(': ')->placeholder('-'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return GtkKeuanganTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('gtk', function (Builder $query) {
                $query->where('sekolah_id', filament()->getTenant()->id);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGtkKeuangan::route('/'),
        ];
    }
}
