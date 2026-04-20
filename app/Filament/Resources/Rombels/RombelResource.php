<?php

namespace App\Filament\Resources\Rombels;

use App\Filament\Resources\Rombels\Pages\ListRombels;
use App\Filament\Resources\Rombels\Schemas\RombelForm;
use App\Filament\Resources\Rombels\Tables\RombelsTable;
use App\Models\Rombel;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RombelResource extends Resource
{
    protected static ?string $slug = 'rombel';

    protected static ?string $model = Rombel::class;

    protected static ?string $modelLabel = 'Rombel';

    protected static ?string $pluralModelLabel = 'Rombel';

    protected static ?int $navigationSort = 2;
    protected static string | \UnitEnum | null $navigationGroup = 'Data Sekolah';

    protected static bool $isScopedToTenant = true;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
    }



    public static function form(Schema $schema): Schema
    {
        return RombelForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                TextEntry::make('nama')->label('Nama Rombel')->prefix(': ')->placeholder('-'),
                TextEntry::make('tingkat')->label('Tingkat')->prefix(': ')->placeholder('-'),
                TextEntry::make('sekolah.nama')->label('Sekolah')->prefix(': ')->placeholder('-'),
            ])->columns(2);
    }



    public static function table(Table $table): Table
    {
        return RombelsTable::configure($table);
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
            'index' => ListRombels::route('/'),
        ];
    }
}
