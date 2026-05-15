<?php

namespace App\Filament\Resources\ActivityLog\Tables;

use App\Models\ActivityLog;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Enums\Size;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->striped()
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable(),
                TextColumn::make('event')
                    ->label('Aktivitas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'login' => 'success',
                        'logout' => 'warning',
                        'created' => 'info',
                        'updated' => 'primary',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('event')
                    ->label('Aktivitas')
                    ->options([
                        'login' => 'Login',
                        'logout' => 'Logout',
                        'created' => 'Ditambahkan',
                        'updated' => 'Diperbarui',
                        'deleted' => 'Dihapus',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                    ViewAction::make()
                        ->modalHeading('Detail Log Aktivitas')
                        ->modalWidth(\Filament\Support\Enums\Width::ExtraLarge)
                        ->mutateRecordDataUsing(fn(array $data, ActivityLog $record): array => [
                            ...$data,
                            'user_name' => $record->user?->name,
                            'event_label' => match ($record->event) {
                                'login' => 'Login',
                                'logout' => 'Logout',
                                'created' => 'Ditambahkan',
                                'updated' => 'Diperbarui',
                                'deleted' => 'Dihapus',
                                default => ucfirst((string) $record->event),
                            },
                            'subject_model' => $record->subject_type ? class_basename($record->subject_type) : null,
                            'before_changes' => $record->properties['before'] ?? [],
                            'after_changes' => $record->properties['after'] ?? [],
                        ])
                        ->schema([
                            Section::make('Ringkasan')
                                ->columns(2)
                                ->schema([
                                    DateTimePicker::make('created_at')
                                        ->label('Waktu'),
                                    TextInput::make('user_name')
                                        ->label('Pengguna'),
                                    TextInput::make('event_label')
                                        ->label('Aktivitas'),
                                    TextInput::make('description')
                                        ->label('Deskripsi'),
                                ]),
                            Section::make('Objek & Perangkat')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('subject_model')
                                        ->label('Model'),
                                    TextInput::make('subject_id')
                                        ->label('ID Subjek'),
                                    TextInput::make('ip_address')
                                        ->label('Alamat IP'),
                                    Textarea::make('user_agent')
                                        ->label('Agen Pengguna')
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ]),
                            Section::make('Detail Perubahan')
                                ->columns(2)
                                ->schema([
                                    KeyValue::make('before_changes')
                                        ->label('Sebelum')
                                        ->keyLabel('Field')
                                        ->valueLabel('Nilai Lama'),
                                    KeyValue::make('after_changes')
                                        ->label('Sesudah')
                                        ->keyLabel('Field')
                                        ->valueLabel('Nilai Baru'),
                                ])
                                ->visible(fn(?ActivityLog $record): bool => filled($record?->properties['before'] ?? null) || filled($record?->properties['after'] ?? null)),
                        ])
                        ->icon(Heroicon::OutlinedEye),
                    DeleteAction::make()
                        ->icon(Heroicon::OutlinedTrash),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('primary')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
