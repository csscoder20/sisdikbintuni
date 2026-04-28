<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $slug = 'user';

    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Pengguna';

    protected static string | \UnitEnum | null $navigationGroup = 'Sistem';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        if (auth()->check() && auth()->user()->hasRole('admin_dinas')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'super_admin');
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
        return UserForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                Section::make('Ringkasan Data')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')->label('Nama Lengkap')->placeholder('-'),
                        TextEntry::make('email')->label('Alamat Surel')->placeholder('-'),
                        TextEntry::make('roles_ringkas')
                            ->label('Peran')
                            ->state(fn (User $record): ?string => $record->roles->pluck('name')->implode(', ') ?: null)
                            ->placeholder('-'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'active' => 'Aktif',
                                'pending' => 'Menunggu Verifikasi',
                                'rejected' => 'Tidak Aktif',
                                default => $state ?: '-',
                            }),
                        TextEntry::make('sekolah.nama')->label('Asal Sekolah')->placeholder('-'),
                        TextEntry::make('email_verified_at')->label('Surel Terverifikasi Pada')->dateTime('d/m/Y H:i')->placeholder('-'),
                    ]),
            ]);
    }



    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
        ];
    }
}
