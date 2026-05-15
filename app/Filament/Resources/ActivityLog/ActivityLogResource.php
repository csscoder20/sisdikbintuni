<?php

namespace App\Filament\Resources\ActivityLog;

use App\Filament\Resources\ActivityLog\Pages\ListActivityLogs;
use App\Filament\Resources\ActivityLog\Schemas\ActivityLogForm;
use App\Filament\Resources\ActivityLog\Tables\ActivityLogsTable;
use App\Models\ActivityLog;
use BackedEnum;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $slug = 'activity-log';

    protected static ?string $model = ActivityLog::class;

    protected static ?string $modelLabel = 'Log Aktivitas';

    protected static ?string $pluralModelLabel = 'Log Aktivitas';

    protected static string | \UnitEnum | null $navigationGroup = 'Sistem';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static ?string $recordTitleAttribute = 'description';

    public static function canViewAny(): bool
    {
        return auth()->check() && 
               auth()->user()->isSuperAdmin() && 
               filament()->getCurrentPanel()?->getId() === 'dinas';
    }


    public static function form(Schema $schema): Schema
    {
        return ActivityLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ringkasan Data')
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'xl' => 4,
                    ])
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Waktu')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('-'),
                        TextEntry::make('user.name')
                            ->label('Pengguna')
                            ->placeholder('-'),
                        TextEntry::make('event')
                            ->label('Aktivitas')
                            ->badge()
                            ->color(fn(?string $state): string => match ($state) {
                                'login' => 'success',
                                'logout' => 'warning',
                                'created' => 'info',
                                'updated' => 'primary',
                                'deleted' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn(?string $state): string => match ($state) {
                                'login' => 'Login',
                                'logout' => 'Logout',
                                'created' => 'Ditambahkan',
                                'updated' => 'Diperbarui',
                                'deleted' => 'Dihapus',
                                default => $state ? ucfirst($state) : '-',
                            }),
                        TextEntry::make('description')
                            ->label('Deskripsi')
                            ->placeholder('-'),
                    ]),
                Section::make('Objek & Perangkat')
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        TextEntry::make('subject_type')
                            ->label('Model')
                            ->formatStateUsing(fn(?string $state): string => $state ? class_basename($state) : '-')
                            ->badge()
                            ->color('gray'),
                        TextEntry::make('subject_id')
                            ->label('ID Subjek')
                            ->placeholder('-')
                            ->copyable(),
                        TextEntry::make('ip_address')
                            ->label('Alamat IP')
                            ->placeholder('-')
                            ->copyable(),
                        TextEntry::make('user_agent')
                            ->label('Agen Pengguna')
                            ->placeholder('-')
                            ->fontFamily(FontFamily::Mono)
                            ->size('xs')
                            ->columnSpanFull()
                            ->copyable(),
                    ]),
                Section::make('Detail Perubahan')
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'lg' => 2,
                    ])
                    ->schema([
                        KeyValueEntry::make('properties.before')
                            ->label('Sebelum')
                            ->keyLabel('Field')
                            ->valueLabel('Nilai Lama'),
                        KeyValueEntry::make('properties.after')
                            ->label('Sesudah')
                            ->keyLabel('Field')
                            ->valueLabel('Nilai Baru'),
                    ])
                    ->visible(fn(?ActivityLog $record): bool => filled($record?->properties['before'] ?? null) || filled($record?->properties['after'] ?? null)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return ActivityLogsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
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
            'index' => ListActivityLogs::route('/'),
        ];
    }
}
