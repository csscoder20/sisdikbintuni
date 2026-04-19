<?php

namespace App\Filament\Resources\Gtks;

use App\Filament\Resources\Gtks\Pages\ListGtks;
use App\Filament\Resources\Gtks\Schemas\GtkForm;
use App\Filament\Resources\Gtks\Tables\GtksTable;
use App\Models\Gtk;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GtkResource extends Resource
{
    protected static ?string $slug = 'gtk';

    protected static ?string $model = Gtk::class;

    protected static ?string $modelLabel = 'Nominatif GTK';

    protected static ?string $navigationLabel = 'Nominatif GTK';

    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'Nominatif GTK';

    protected static string | \UnitEnum | null $navigationGroup = 'Data GTK';

    protected static bool $isScopedToTenant = true;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
    }



    public static function form(Schema $schema): Schema
    {
        return GtkForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                TextEntry::make('nama')->label('Nama Lengkap')->placeholder('-'),
                TextEntry::make('jenis_gtk')->label('Jenis GTK')->placeholder('-'),
                TextEntry::make('nik')->label('NIK')->placeholder('-'),
                TextEntry::make('nip')->label('NIP')->placeholder('-'),
                TextEntry::make('nuptk')->label('NUPTK')->placeholder('-'),
                TextEntry::make('nokarpeg')->label('Nomor Karpeg')->placeholder('-'),
                TextEntry::make('jenis_kelamin')->label('Jenis Kelamin')->placeholder('-'),
                TextEntry::make('agama')->label('Agama')->placeholder('-'),
                TextEntry::make('tempat_lahir')->label('Tempat Lahir')->placeholder('-'),
                TextEntry::make('tanggal_lahir')->label('Tanggal Lahir')->date('d/m/Y')->placeholder('-'),
                TextEntry::make('pendidikan_terakhir')->label('Pendidikan Terakhir')->placeholder('-'),
                TextEntry::make('daerah_asal')->label('Daerah Asal')->placeholder('-'),
                TextEntry::make('status_kepegawaian')->label('Status Kepegawaian')->placeholder('-'),
                TextEntry::make('pangkat_gol_terakhir')->label('Pangkat/Golongan Terakhir')->placeholder('-'),
                TextEntry::make('tmt_pns')->label('TMT PNS')->date('d/m/Y')->placeholder('-'),
                TextEntry::make('tmt_pangkat_gol_terakhir')->label('TMT Pangkat/Golongan')->date('d/m/Y')->placeholder('-'),
                TextEntry::make('alamat')->label('Alamat')->placeholder('-')->columnSpanFull(),
                TextEntry::make('desa')->label('Desa/Kelurahan')->placeholder('-'),
                TextEntry::make('kecamatan')->label('Kecamatan')->placeholder('-'),
                TextEntry::make('kabupaten')->label('Kabupaten')->placeholder('-'),
                TextEntry::make('provinsi')->label('Provinsi')->placeholder('-'),
            ])->columns(2);
    }



    public static function table(Table $table): Table
    {
        return GtksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGtks::route('/'),
        ];
    }
}
