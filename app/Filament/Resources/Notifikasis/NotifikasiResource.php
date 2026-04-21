<?php

namespace App\Filament\Resources\Notifikasis;

use App\Filament\Resources\Notifikasis\Pages;
use App\Filament\Resources\Notifikasis\Schemas\NotifikasiForm;
use App\Filament\Resources\Notifikasis\Tables\NotifikasisTable;
use App\Models\Notifikasi;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;

class NotifikasiResource extends Resource
{
    protected static ?string $model = Notifikasi::class;

    protected static ?string $slug = 'notifikasi';

    protected static ?string $modelLabel = 'Pemberitahuan Masal';

    protected static ?string $pluralModelLabel = 'Pemberitahuan Masal';

    protected static ?int $navigationSort = 1;

    protected static string | \UnitEnum | null $navigationGroup = 'Sistem';

    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Megaphone;

        public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->check() && auth()->user()->hasRole('admin_dinas')) {
            $query->whereHas('sender', function ($q) {
                $q->whereDoesntHave('roles', function ($rq) {
                    $rq->where('name', 'super_admin');
                });
            });
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && !auth()->user()->hasRole('operator');
    }


    public static function form(Schema $schema): Schema
    {
        return NotifikasiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotifikasisTable::configure($table);
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
            'index' => Pages\ListNotifikasis::route('/'),
            'create' => Pages\CreateNotifikasi::route('/create'),
        ];
    }
}
