<?php

namespace App\Filament\Resources\GtkKeuangan;

use App\Filament\Resources\GtkKeuangan\Pages;
use App\Models\Gtk;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Resources\GtkKeuangan\Schemas\GtkKeuanganForm;
use App\Filament\Resources\GtkKeuangan\Tables\GtkKeuangansTable;

use App\Filament\Traits\HasDinasFilter;

class GtkKeuanganResource extends Resource
{
    use HasDinasFilter;
    protected static ?string $model = Gtk::class;

    protected static ?string $slug = 'rekening-npwp-gtk';

    protected static ?string $modelLabel = 'Rekening & NPWP';
    protected static ?string $pluralModelLabel = 'Rekening & NPWP';

    protected static ?string $navigationLabel = 'Rekening & NPWP';
    protected static string | \UnitEnum | null $navigationGroup = 'Data GTK';
    protected static ?int $navigationSort = 3;
    protected static bool $isScopedToTenant = true;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Schema $schema): Schema
    {
        return GtkKeuanganForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GtkKeuangansTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGtkKeuangans::route('/'),
        ];
    }
}
