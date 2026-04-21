<?php

namespace App\Filament\Resources\Notifikasis\Schemas;

use App\Models\Sekolah;
use App\Models\User;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NotifikasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Pemberitahuan')
                ->schema([
                    TextInput::make('subject')
                        ->label('Subject / Judul')
                        ->required()
                        ->placeholder('Contoh: Pemutakhiran Data Siswa')
                        ->columnSpanFull(),
                    RichEditor::make('content')
                        ->label('Isi Pesan')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Penerima')
                ->schema([
                    Select::make('recipient_type')
                        ->label('Dikirim Ke')
                        ->options([
                            'all' => 'Semua Operator',
                            'schools' => 'Sekolah Tertentu',
                            'users' => 'Pengguna Tertentu',
                        ])
                        ->required()
                        ->live(),
                    
                    Select::make('target_ids')
                        ->label('Pilih Sekolah')
                        ->multiple()
                        ->options(Sekolah::pluck('nama', 'id'))
                        ->required()
                        ->visible(fn ($get) => $get('recipient_type') === 'schools')
                        ->searchable()
                        ->preload(),

                    Select::make('target_ids')
                        ->label('Pilih Pengguna')
                        ->multiple()
                        ->options(User::whereHas('roles', fn($q) => $q->where('name', 'operator'))->pluck('name', 'id'))
                        ->required()
                        ->visible(fn ($get) => $get('recipient_type') === 'users')
                        ->searchable()
                        ->preload(),
                ]),
        ]);
    }
}
