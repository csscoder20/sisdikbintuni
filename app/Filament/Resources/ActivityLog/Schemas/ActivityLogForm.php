<?php
// FILE: d:\laragon\www\sisdikbintuni\app\Filament\Resources\ActivityLog\Schemas\ActivityLogForm.php

namespace App\Filament\Resources\ActivityLog\Schemas;

use Filament\Forms\Components\KeyValueField;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
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
                            ->label('User'),
                        TextInput::make('event')
                            ->label('Event'),
                        TextInput::make('description')
                            ->label('Deskripsi'),
                        TextInput::make('subject_type')
                            ->label('Model')
                            ->placeholder('N/A'),
                        TextInput::make('subject_id')
                            ->label('ID Subjek')
                            ->placeholder('N/A'),
                    ]),
                Section::make('Metadata')
                    ->columns(2)
                    ->schema([
                        TextInput::make('ip_address')
                            ->label('IP Address'),
                        TextInput::make('user_agent')
                            ->label('User Agent')
                            ->columnSpanFull(),
                    ]),
                Section::make('Detail Perubahan')
                    ->columnSpanFull()
                    ->schema([
                        KeyValueField::make('properties.before')
                            ->label('Sebelum')
                            ->visible(fn ($record) => !is_null($record) && !empty($record->properties['before'])),
                        KeyValueField::make('properties.after')
                            ->label('Sesudah')
                            ->visible(fn ($record) => !is_null($record) && !empty($record->properties['after'])),
                    ])->columns(2)
                    ->visible(fn ($record) => !is_null($record) && !empty($record->properties)),
            ]);
    }
}
