<?php

namespace App\Filament\Resources\Siswas;

use App\Filament\Resources\Siswas\Pages\ListSiswas;
use App\Filament\Resources\Siswas\Schemas\SiswaForm;
use App\Filament\Resources\Siswas\Tables\SiswasTable;
use App\Models\Siswa;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum; // Add this import

class SiswaResource extends Resource
{
    protected static ?string $slug = 'siswa';

    protected static ?string $model = Siswa::class;

    protected static ?string $navigationLabel = 'Nominatif Siswa';

    protected static ?int $navigationSort = 1;

    protected static string | \UnitEnum | null $navigationGroup = 'Data Siswa';

    protected static ?string $modelLabel = 'Nominatif Siswa';

    protected static ?string $pluralModelLabel = 'Nominatif Siswa';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $recordTitleAttribute = 'nama';

    protected static bool $isScopedToTenant = true;

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
    }



    public static function form(Schema $schema): Schema
    {
        return SiswaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                TextEntry::make('nama')->label('Nama Lengkap')->prefix(': ')->placeholder('-'),
                TextEntry::make('rombel_ringkas')
                    ->label('Rombel/Kelas')
                    ->state(fn(Siswa $record): ?string => $record->rombel->pluck('nama')->implode(', ') ?: null)
                    ->prefix(': ')
                    ->placeholder('-'),
                TextEntry::make('nisn')->label('NISN')->prefix(': ')->placeholder('-'),
                TextEntry::make('nik')->label('NIK')->prefix(': ')->placeholder('-'),
                TextEntry::make('jenis_kelamin')->label('Jenis Kelamin')->prefix(': ')->placeholder('-'),
                TextEntry::make('status')
                    ->label('Status Siswa')
                    ->formatStateUsing(fn(?string $state): string => $state ? Str::headline($state) : '-')
                    ->prefix(': '),
                TextEntry::make('tempat_lahir')->label('Tempat Lahir')->prefix(': ')->placeholder('-'),
                TextEntry::make('tanggal_lahir')->label('Tanggal Lahir')->date('d/m/Y')->prefix(': ')->placeholder('-'),
                TextEntry::make('agama')->label('Agama')->prefix(': ')->placeholder('-'),
                TextEntry::make('daerah_asal')->label('Daerah Asal')->prefix(': ')->placeholder('-'),
                TextEntry::make('alamat')
                    ->label('Alamat Domisili')
                    ->placeholder('-')
                    ->prefix(': ')
                    ->inlineLabel(),
                TextEntry::make('desa')->label('Desa/Kelurahan')->prefix(': ')->placeholder('-'),
                TextEntry::make('kecamatan')->label('Kecamatan')->prefix(': ')->placeholder('-'),
                TextEntry::make('kabupaten')->label('Kabupaten')->prefix(': ')->placeholder('-'),
                TextEntry::make('provinsi')->label('Provinsi')->prefix(': ')->placeholder('-'),
                TextEntry::make('nama_ayah')->label('Nama Ayah')->prefix(': ')->placeholder('-'),
                TextEntry::make('nama_ibu')->label('Nama Ibu')->prefix(': ')->placeholder('-'),
                TextEntry::make('nama_wali')->label('Nama Wali')->prefix(': ')->placeholder('-'),
                TextEntry::make('nokk')->label('Nomor KK')->prefix(': ')->placeholder('-'),
                TextEntry::make('nobpjs')->label('Nomor BPJS')->prefix(': ')->placeholder('-'),
                TextEntry::make('disabilitas')
                    ->label('Jenis Disabilitas')
                    ->formatStateUsing(fn(?string $state): string => $state ? Str::headline($state) : '-')
                    ->prefix(': '),
                TextEntry::make('beasiswa')
                    ->label('Status Beasiswa')
                    ->formatStateUsing(fn(?string $state): string => $state ? Str::headline($state) : '-')
                    ->prefix(': '),
            ])->columns(2);
    }



    public static function table(Table $table): Table
    {
        return SiswasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
            'index' => ListSiswas::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
