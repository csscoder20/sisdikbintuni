<?php

namespace App\Filament\Pages;

use App\Models\Sekolah;
use App\Models\WilayahKabBintuni;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form as SchemaForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class SekolahPage extends Page implements HasSchemas
{

    use InteractsWithSchemas;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $navigationLabel = 'Profil';
    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Profil';
    protected static ?string $pluralModelLabel = 'Profil';
    
    protected static string | \UnitEnum | null $navigationGroup = 'Data Sekolah';

    protected static ?string $slug = 'profil';

    protected static ?string $title = 'PROFIL SEKOLAH';

    protected string $view = 'filament.pages.sekolah-page';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    // Hanya tampil di panel operator (bukan panel dinas/admin)
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
    }

    public function mount(): void
    {
        $sekolah = $this->getSekolah();

        if ($sekolah) {
            $this->form->fill($sekolah->toArray());
        }
    }

    protected function getSekolah(): ?Sekolah
    {
        // Ambil dari tenancy (panel operator menggunakan ->tenant(Sekolah::class))
        $tenant = Filament::getTenant();
        if ($tenant instanceof Sekolah) {
            return $tenant;
        }

        // Fallback: ambil dari relasi user
        return auth()->user()?->sekolah;
    }

    public function getTitle(): string|Htmlable
    {
        $sekolah = $this->getSekolah();
        return $sekolah?->nama ?? 'Data Sekolah';
    }

    /**
     * Skema default: statePath untuk mengikat data ke property $data
     */
    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->operation('edit')
            ->statePath('data');
    }

    /**
     * Definisi field-field form sekolah
     */
    public function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Identitas Sekolah')
                ->description('Informasi dasar identitas sekolah')
                ->icon('heroicon-o-identification')
                ->columns(3)
                ->schema([
                    TextInput::make('nama')
                        ->label('Nama Sekolah')
                        ->required()
                        ->maxLength(255),
                    // ->columnSpanFull(),

                    TextInput::make('npsn')
                        ->label('NPSN')
                        ->maxLength(20),

                    TextInput::make('nss')
                        ->label('NSS')
                        ->maxLength(20),

                    TextInput::make('npwp')
                        ->label('NPWP')
                        ->maxLength(30),

                    TextInput::make('email')
                        ->label('Email Sekolah')
                        ->email()
                        ->maxLength(255),

                    Select::make('status_sekolah')
                        ->label('Status Sekolah')
                        ->options([
                            'Negeri' => 'Negeri',
                            'Swasta' => 'Swasta',
                        ]),

                    Select::make('akreditasi')
                        ->label('Akreditasi')
                        ->options([
                            'A'     => 'A',
                            'B'     => 'B',
                            'C'     => 'C',
                            'Belum' => 'Belum Terakreditasi',
                        ]),
                ]),

            Section::make('Alamat Sekolah')
                ->description('Lokasi dan alamat lengkap sekolah')
                ->icon('heroicon-o-map-pin')
                ->columns(3)
                ->schema([
                    Textarea::make('alamat')
                        ->label('Alamat')
                        ->rows(3)
                        ->columnSpanFull(),
                    TextInput::make('desa')
                        ->label('Desa / Kelurahan'),

                    TextInput::make('kecamatan')
                        ->label('Kecamatan'),

                    TextInput::make('kabupaten')
                        ->label('Kabupaten / Kota'),

                    TextInput::make('provinsi')
                        ->label('Provinsi'),
                ]),

            Section::make('Pendirian Sekolah')
                ->description('Informasi pendirian dan legalitas sekolah')
                ->icon('heroicon-o-document-text')
                ->columns(3)
                ->schema([
                    TextInput::make('tahun_berdiri')
                        ->label('Tahun Berdiri')
                        ->numeric()
                        ->minValue(1900)
                        ->maxValue((int) date('Y')),

                    TextInput::make('nomor_sk_pendirian')
                        ->label('Nomor SK Pendirian'),

                    DatePicker::make('tgl_sk_pendirian')
                        ->label('Tanggal SK Pendirian')
                        ->displayFormat('d/m/Y'),
                ]),

            Section::make('Tanah & Gedung')
                ->description('Informasi fisik bangunan dan lahan sekolah')
                ->icon('heroicon-o-home-modern')
                ->columns(3)
                ->schema([
                    Select::make('gedung_sekolah')
                        ->label('Gedung Sekolah')
                        ->options([
                            'Milik Sendiri' => 'Milik Sendiri',
                            'Sewa'          => 'Sewa',
                            'Pinjam Pakai'  => 'Pinjam Pakai',
                        ]),

                    Select::make('status_tanah')
                        ->label('Status Tanah')
                        ->options([
                            'Milik Sendiri'     => 'Milik Sendiri',
                            'Hak Guna Bangunan' => 'Hak Guna Bangunan',
                            'Sewa'              => 'Sewa',
                            'Pinjam Pakai'      => 'Pinjam Pakai',
                        ]),

                    TextInput::make('luas_tanah')
                        ->label('Luas Tanah')
                        ->numeric()
                        ->suffix('m²'),
                ]),

            Section::make('Penyelenggara / Yayasan')
                ->description('Informasi tentang yayasan atau penyelenggara sekolah')
                ->icon('heroicon-o-briefcase')
                ->columns(3)
                ->schema([
                    TextInput::make('nama_yayasan')
                        ->label('Nama Penyelenggara / Yayasan'),
                    TextInput::make('nomor_sk_yayasan')
                        ->label('SK Pendirian Yayasan'),
                    Textarea::make('alamat_yayasan')
                        ->label('Alamat Penyelenggara / Yayasan')
                        ->rows(3)
                        ->columnSpanFull(),

                ]),
        ]);
    }

    /**
     * Mengatur tata letak konten halaman: form + tombol simpan
     */
    public function content(Schema $schema): Schema
    {
        return $schema->components([
            SchemaForm::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make($this->getFormActions())
                        ->alignment($this->getFormActionsAlignment())
                        ->key('form-actions'),
                ]),
        ]);
    }

    /**
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $sekolah = $this->getSekolah();

        if (! $sekolah) {
            Notification::make()
                ->title('Data sekolah tidak ditemukan.')
                ->danger()
                ->send();
            return;
        }

        $data = $this->form->getState();

        // Pastikan user_id tidak tertimpa
        unset($data['user_id']);

        $sekolah->update($data);

        Notification::make()
            ->title('Data sekolah berhasil disimpan!')
            ->success()
            ->send();
    }
}
