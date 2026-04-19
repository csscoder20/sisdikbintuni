<?php

namespace App\Filament\Resources\ActivityLog;

use App\Filament\Resources\ActivityLog\Pages\ListActivityLogs;
use App\Filament\Resources\ActivityLog\Schemas\ActivityLogForm;
use App\Filament\Resources\ActivityLog\Tables\ActivityLogsTable;
use App\Models\ActivityLog;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

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
        return auth()->check() && auth()->user()->isSuperAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return ActivityLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                Section::make('Ringkasan Data')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')->label('Waktu')->dateTime('d/m/Y H:i')->placeholder('-'),
                        TextEntry::make('user.name')->label('Pengguna')->placeholder('-'),
                        TextEntry::make('event')->label('Aktivitas')->placeholder('-'),
                        TextEntry::make('description')->label('Deskripsi')->placeholder('-'),
                        TextEntry::make('subject_type')->label('Model')->placeholder('-'),
                        TextEntry::make('subject_id')->label('ID Subjek')->placeholder('-'),
                        TextEntry::make('ip_address')->label('Alamat IP')->placeholder('-'),
                        TextEntry::make('user_agent')->label('Agen Pengguna')->placeholder('-')->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return ActivityLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
        ];
    }
}
