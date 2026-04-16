<?php

namespace App\Filament\Pages;

use App\Filament\Actions\ValidateChecklistAction;
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
                ->columns(4)
                ->schema([
                    

                    TextInput::make('provinsi')
                        ->label('Provinsi')
                        ->default('Papua Barat')
                        ->disabled()
                        ->dehydrated(true),

                    TextInput::make('kabupaten')
                        ->label('Kabupaten / Kota')
                        ->default('Teluk Bintuni')
                        ->disabled()
                        ->dehydrated(true),

                    Select::make('kecamatan')
                        ->label('Kecamatan')
                        ->options(function () {
                            return WilayahKabBintuni::whereRaw("LENGTH(REPLACE(kode, '.', '')) = 6")
                                ->pluck('nama', 'nama');
                        })
                        ->live()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('desa', null))
                        ->searchable(),

                    Select::make('desa')
                        ->label('Desa / Kelurahan')
                        ->options(function (callable $get) {
                            $kecamatan = $get('kecamatan');
                            if (! $kecamatan) {
                                return [];
                            }
                            
                            $kecamatanModel = WilayahKabBintuni::where('nama', $kecamatan)
                                ->whereRaw("LENGTH(REPLACE(kode, '.', '')) = 6")
                                ->first();

                            if (!$kecamatanModel) {
                                return [];
                            }

                            return WilayahKabBintuni::where('kode', 'like', $kecamatanModel->kode . '.%')
                                ->whereRaw("LENGTH(REPLACE(kode, '.', '')) = 10")
                                ->pluck('nama', 'nama');
                        })
                        ->searchable(),
                    Textarea::make('alamat')
                        ->label('Alamat')
                        ->rows(2)
                        ->columnSpanFull(),

                ]),

            Section::make('Pendirian Sekolah')
                ->description('Informasi pendirian dan legalitas sekolah')
                ->columns(3)
                ->schema([
                    TextInput::make('tahun_berdiri')
                        ->label('Tahun Berdiri')
                        ->numeric()
                        ->minValue(1900)
                        ->maxValue((int) date('Y')),

                    TextInput::make('nomor_sk_pendirian')
                        ->label('Nomor SK Pendirian'),

                    DatePicker::make('tanggal_sk_pendirian')
                        ->label('Tanggal SK Pendirian')
                        ->native(false)
                        ->displayFormat('d/m/Y'),
                ]),

            Section::make('Tanah & Gedung')
                ->description('Informasi fisik bangunan dan lahan sekolah')
                ->columns(3)
                ->schema([
                    Select::make('status_tanah')
                        ->label('Status Tanah')
                        ->options([
                            'shm'    => 'Milik Sendiri (SHM)',
                            'hgb'    => 'Hak Guna Bangunan (HGB)',
                            'ulayat' => 'Tanah Ulayat',
                        ]),

                    TextInput::make('luas_tanah')
                        ->label('Luas Tanah')
                        ->numeric()
                        ->suffix('m²'),
                ]),

            Section::make('Penyelenggara / Yayasan')
                ->description('Informasi tentang yayasan atau penyelenggara sekolah')
                ->columns(3)
                ->schema([
                    TextInput::make('nama_yayasan')
                        ->label('Nama Penyelenggara / Yayasan'),
                    TextInput::make('nomor_sk_yayasan')
                        ->label('SK Pendirian Yayasan'),
                    Textarea::make('alamat_yayasan')
                        ->label('Alamat Penyelenggara / Yayasan')
                        ->rows(2)
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
