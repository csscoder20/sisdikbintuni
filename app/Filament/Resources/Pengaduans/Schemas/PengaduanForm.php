<?php

namespace App\Filament\Resources\Pengaduans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;
use Filament\Forms\Components\RichEditor;

class PengaduanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->label('Judul Pengaduan')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                RichEditor::make('deskripsi')
                    ->label('Deskripsi Pengaduan')
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'h2',
                        'h3',
                        'bulletList',
                        'orderedList',
                        'redo',
                        'undo',
                    ])
                    ->extraInputAttributes([
                        'style' => 'min-height: 200px; height: 100%;',
                    ])
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                    ])
                    ->default('pending')
                    ->visible(fn() => Filament::getCurrentPanel()?->getId() === 'dinas')
                    ->hiddenOn('create'),
                Textarea::make('jawaban')
                    ->label('Tanggapan dari Admin')
                    ->visible(fn() => Filament::getCurrentPanel()?->getId() === 'dinas')
                    ->hiddenOn('create')
                    ->columnSpanFull(),
            ]);
    }
}
