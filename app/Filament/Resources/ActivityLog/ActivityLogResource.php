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
use Filament\Schemas\Components\Group;
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
    protected static ?int $navigationSort = 3;

    protected static ?string $pluralModelLabel = 'Log Aktivitas';

    protected static string | \UnitEnum | null $navigationGroup = 'Sistem';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'description';

    public static function canViewAny(): bool
    {
        return auth()->check() &&
            (auth()->user()->isSuperAdmin() || auth()->user()->hasRole(['admin_dinas', 'pengawas'])) &&
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
                Group::make()
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('summary')
                            ->label('Ringkasan Aktivitas')
                            ->state(function (ActivityLog $record) {
                                $date = $record->created_at ? $record->created_at->format('d/m/Y H:i:s') : '-';
                                $user = $record->user ? $record->user->name : 'Sistem / Guest';

                                $event = match ($record->event) {
                                    'login' => 'berhasil masuk (login) ke dalam aplikasi',
                                    'logout' => 'telah keluar (logout) dari aplikasi',
                                    'created' => 'menambahkan data baru pada',
                                    'updated' => 'melakukan perubahan data pada',
                                    'deleted' => 'menghapus data pada',
                                    default => 'melakukan aktivitas \'' . $record->event . '\' pada',
                                };

                                $subject = $record->subject_type ? class_basename($record->subject_type) : '';
                                $subjectId = $record->subject_id ? " (ID: {$record->subject_id})" : '';

                                $message = "{$date}, {$user} {$event}";
                                if ($subject) {
                                    $message .= " {$subject}{$subjectId}";
                                }

                                return $message . '.';
                            }),
                    ]),
                Group::make()
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('ip_address')
                            ->label('Alamat IP/Lokasi')
                            ->state(function (ActivityLog $record) {
                                $ip = $record->ip_address;
                                if (!$ip) return '-';
                                if ($ip === '127.0.0.1' || $ip === '::1') {
                                    return $ip . ' (Lokal)';
                                }

                                $location = \Illuminate\Support\Facades\Cache::remember('ip_location_' . $ip, 86400, function () use ($ip) {
                                    try {
                                        $response = \Illuminate\Support\Facades\Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
                                        if ($response->successful() && $response->json('status') === 'success') {
                                            $data = $response->json();
                                            return $data['city'] . ', ' . $data['regionName'] . ', ' . $data['country'];
                                        }
                                    } catch (\Exception $e) {
                                        return 'Lokasi tidak diketahui';
                                    }
                                    return 'Lokasi tidak diketahui';
                                });

                                return $ip . ' - ' . $location;
                            })
                            ->placeholder('-')
                            ->copyable(),
                        TextEntry::make('user_agent')
                            ->label('Perangkat (User Agent)')
                            ->placeholder('-')
                            ->fontFamily(FontFamily::Mono)
                            ->size('xs')
                            ->copyable(),
                    ]),
                Group::make()
                    ->columnSpanFull()
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
