<?php

namespace App\Filament\Pages\Auth;

use App\Models\Sekolah;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Auth\Pages\Register as BaseRegister;

class CustomRegister extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                TextInput::make('nohp')
                    ->label('No. Handphone')
                    ->required()
                    ->tel()
                    ->maxLength(255),
                Select::make('sekolah_id')
                    ->label('Asal Sekolah')
                    ->options(Sekolah::query()->pluck('nama_sekolah', 'id'))
                    ->required()
                    ->searchable(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
