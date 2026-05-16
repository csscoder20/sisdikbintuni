<?php

namespace App\Filament\Resources\Sekolahs;

use App\Filament\Resources\Sekolahs\Pages\ManageSekolahs;
use App\Models\Sekolah;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SekolahResource extends Resource
{
    protected static ?string $model = Sekolah::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $navigationLabel = 'Sekolah';
    protected static ?string $pluralModelLabel = 'Sekolah';
    protected static string | \UnitEnum | null $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = -1;

    public static function canViewAny(): bool
    {
        return auth()->check() &&
            (auth()->user()->hasRole(['super_admin', 'admin_dinas'])) &&
            filament()->getCurrentPanel()?->getId() === 'dinas';
    }




    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                TextInput::make('npsn')
                    ->required(),
                TextInput::make('jenjang'),
                TextInput::make('kecamatan'),
                TextInput::make('akreditasi'),
                TextInput::make('email')
                    ->email(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Sekolah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('npsn')
                    ->label('NPSN')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('jenjang')
                    ->label('Jenjang')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'sma' => 'info',
                        'smk' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => strtoupper($state))
                    ->searchable(),
                TextColumn::make('kecamatan')
                    ->label('Kecamatan')
                    ->searchable(),
                TextColumn::make('akreditasi')
                    ->label('Akreditasi')
                    ->badge()
                    ->searchable(),
                TextColumn::make('operator_count')
                    ->label('Operator')
                    ->counts('operator')
                    ->badge(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('jenjang')
                    ->options([
                        'sma' => 'SMA',
                        'smk' => 'SMK',
                    ]),
                TrashedFilter::make(),
            ])

            ->recordActions([
                ActionGroup::make([

                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->label('Aksi')
                    ->tooltip('Aksi'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => ManageSekolahs::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
