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
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Image;
use Illuminate\Support\Facades\Storage;

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
            Grid::make(['default' => 1, 'md' => 3])
                ->schema([
                    Section::make('Foto & Logo Sekolah')
                        ->columnSpan(['default' => 1, 'md' => 1])
                        ->schema([
                            Grid::make(['default' => 1, 'lg' => 10])
                                ->schema([
                                    ...$this->getFotoSchemaComponents(),
                                    ...$this->getLogoSchemaComponents(),
                                ]),
                        ]),

                    Section::make('Identitas Sekolah')
                        ->columnSpan(['default' => 1, 'md' => 2])
                        ->schema($this->getIdentitasFormComponents())
                        ->columns(3),
                ]),

            Section::make('Alamat Sekolah')
                ->schema($this->getAlamatFormComponents())
                ->columns(4),

            Section::make('Data Pendukung')
                ->schema($this->getSupportingFormComponents())
                ->columns(3),
        ]);
    }

    protected function getIdentitasFormComponents(): array
    {
        return [
            TextInput::make('nama')
                ->label('Nama Sekolah')
                ->required()
                ->maxLength(255),

            TextInput::make('npsn')
                ->label('NPSN')
                ->maxLength(20),

            TextInput::make('nss')
                ->label('NSS')
                ->maxLength(20),

            TextInput::make('npwp')
                ->label('NPWP')
                ->maxLength(16),

            TextInput::make('email')
                ->label('Email Sekolah')
                ->email()
                ->maxLength(255),

            Select::make('jenjang')
                ->label('Jenjang')
                ->options([
                    'sd'  => 'SD',
                    'smp' => 'SMP',
                    'sma' => 'SMA',
                    'smk' => 'SMK',
                ]),

            Select::make('akreditasi')
                ->label('Akreditasi')
                ->options([
                    'A'     => 'A',
                    'B'     => 'B',
                    'C'     => 'C',
                    'Belum' => 'Belum Terakreditasi',
                ]),

            TextInput::make('tahun_berdiri')
                ->label('Tahun Berdiri')
                ->maxValue((int) date('Y')),

            TextInput::make('nomor_sk_pendirian')
                ->label('Nomor SK Pendirian'),

            DatePicker::make('tanggal_sk_pendirian')
                ->label('Tanggal SK Pendirian')
                ->native(false)
                ->displayFormat('d/m/Y'),
        ];
    }

    protected function getAlamatFormComponents(): array
    {
        return [
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
                ->columnSpan(2),
        ];
    }

    protected function getSupportingFormComponents(): array
    {
        return [
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

            TextInput::make('nama_yayasan')
                ->label('Nama Penyelenggara / Yayasan'),

            TextInput::make('nomor_sk_yayasan')
                ->label('SK Pendirian Yayasan'),

            Textarea::make('alamat_yayasan')
                ->label('Alamat Penyelenggara / Yayasan')
                ->rows(2)
                ->columnSpan(2),
        ];
    }

    protected function getFotoSchemaComponents(): array
    {
        $sekolah = $this->getSekolah();
        $foto = $sekolah?->foto;
        
        return [
            \Filament\Schemas\Components\View::make('filament.components.image-hover')
                ->viewData([
                    'field' => 'foto',
                    'livewire' => $this,
                ])
                ->columnSpan(['default' => 1, 'lg' => 7]),
        ];
    }

    protected function getLogoSchemaComponents(): array
    {
        $sekolah = $this->getSekolah();
        $logo = $sekolah?->logo;
        
        return [
            \Filament\Schemas\Components\View::make('filament.components.image-hover')
                ->viewData([
                    'field' => 'logo',
                    'livewire' => $this,
                    'width' => '50%',
                ])
                ->columnSpan(['default' => 1, 'lg' => 3]),
        ];
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
                ->label('Perbarui Data')
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
            ->title('Data sekolah berhasil diperbarui!')
            ->success()
            ->send();
    }

    public function viewFotoAction(): Action
    {
        return Action::make('viewFoto')
            ->label('Preview Foto')
            ->modalHeading('Foto Sekolah')
            ->modalWidth('4xl')
            ->form([
                \Filament\Forms\Components\ViewField::make('current_foto')
                    ->view('filament.components.image-current-preview')
                    ->viewData([
                        'url' => $this->getSekolah()?->foto 
                            ? Storage::disk('public')->url($this->getSekolah()->foto) 
                            : 'https://placehold.co/800x600?text=Foto+Sekolah',
                        'title' => 'Foto Sekolah'
                    ]),
                FileUpload::make('new_foto')
                    ->label('Pilih Foto Baru')
                    ->image()
                    ->imageEditor()
                    ->imageCropAspectRatio('4:3')
                    ->disk('public')
                    ->directory('sekolah-foto')
                    ->helperText('Klik area di atas untuk memilih gambar baru dari komputer Anda.')
            ])
            ->action(function (array $data) {
                if (!empty($data['new_foto'])) {
                    $sekolah = $this->getSekolah();
                    if ($sekolah) {
                        $sekolah->update(['foto' => $data['new_foto']]);
                        Notification::make()
                            ->title('Foto sekolah berhasil diperbarui!')
                            ->success()
                            ->send();
                    }
                }
            })
            ->modalSubmitActionLabel('Perbarui Foto')
            ->modalCancelActionLabel('Tutup');
    }

    public function viewLogoAction(): Action
    {
        return Action::make('viewLogo')
            ->label('Preview Logo')
            ->modalHeading('Logo Sekolah')
            ->modalWidth('2xl')
            ->form([
                \Filament\Forms\Components\ViewField::make('current_logo')
                    ->view('filament.components.image-current-preview')
                    ->viewData([
                        'url' => $this->getSekolah()?->logo 
                            ? Storage::disk('public')->url($this->getSekolah()->logo) 
                            : 'https://placehold.co/400x400?text=Logo+Sekolah',
                        'title' => 'Logo Sekolah'
                    ]),
                FileUpload::make('new_logo')
                    ->label('Pilih Logo Baru')
                    ->image()
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1')
                    ->disk('public')
                    ->directory('sekolah-logo')
                    ->helperText('Klik area di atas untuk memilih logo baru dari komputer Anda.')
            ])
            ->action(function (array $data) {
                if (!empty($data['new_logo'])) {
                    $sekolah = $this->getSekolah();
                    if ($sekolah) {
                        $sekolah->update(['logo' => $data['new_logo']]);
                        
                        $logoUrl = Storage::disk('public')->url($data['new_logo']);
                        $this->dispatch('school-logo-updated', logo_url: $logoUrl);
                        
                        Notification::make()
                            ->title('Logo sekolah berhasil diperbarui!')
                            ->success()
                            ->send();
                    }
                }
            })
            ->modalSubmitActionLabel('Perbarui Logo')
            ->modalCancelActionLabel('Tutup');
    }

    // Menghapus updateFotoAction dan updateLogoAction yang lama agar tidak membingungkan
}
