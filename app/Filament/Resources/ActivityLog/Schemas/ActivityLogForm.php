<?php
// FILE: d:\laragon\www\sisdikbintuni\app\Filament\Resources\ActivityLog\Schemas\ActivityLogForm.php

namespace App\Filament\Resources\ActivityLog\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActivityLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('created_at')
                            ->label('Waktu'),
                        TextInput::make('user.name')
                            ->label('Pengguna'),
                        TextInput::make('event')
                            ->label('Aktivitas'),
                        TextInput::make('description')
                            ->label('Deskripsi'),
                        TextInput::make('subject_type')
                            ->label('Model')
                            ->placeholder('Tidak tersedia'),
                        TextInput::make('subject_id')
                            ->label('ID Subjek')
                            ->placeholder('Tidak tersedia'),
                    ]),
                Section::make('Metadata')
                    ->columns(2)
                    ->schema([
                        TextInput::make('ip_address')
                            ->label('Alamat IP'),
                        TextInput::make('user_agent')
                            ->label('Agen Pengguna')
                            ->columnSpanFull(),
                    ]),
                Section::make('Detail Perubahan')
                    ->columnSpanFull()
                    ->schema([
                        KeyValue::make('properties.before')
                            ->label('Sebelum')
                            ->visible(fn ($record) => !is_null($record) && !empty($record->properties['before'])),
                        KeyValue::make('properties.after')
                            ->label('Sesudah')
                            ->visible(fn ($record) => !is_null($record) && !empty($record->properties['after'])),
                    ])->columns(2)
                    ->visible(fn ($record) => !is_null($record) && !empty($record->properties)),
            ]);
    }
}
