<?php

namespace App\Filament\Pages;

use App\Support\RuntimeSettings;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SystemSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Pengaturan';
    protected static ?string $title = 'Pengaturan Aplikasi';
    protected static ?int $navigationSort = 99;
    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected string $view = 'filament.pages.system-settings';

    public ?array $data = [];
    public bool $secretConfigured = false;
    public bool $smtpPasswordConfigured = false;

    public static function canAccess(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'dinas'
            && auth()->check()
            && auth()->user()->hasRole('super_admin');
    }

    public function mount(): void
    {
        RuntimeSettings::apply();
        $this->secretConfigured = filled(config('services.recaptcha.secret_key'));
        $this->smtpPasswordConfigured = filled(config('mail.mailers.smtp.password'));

        $this->form->fill([
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'captcha_enabled' => RuntimeSettings::captchaEnabled(),
            'recaptcha_site_key' => config('services.recaptcha.site_key'),
            'recaptcha_secret_key' => '',
            'smtp_enabled' => RuntimeSettings::smtpEnabled(),
            'smtp_scheme' => config('mail.mailers.smtp.scheme')
                ?: ((int) config('mail.mailers.smtp.port') === 465 ? 'smtps' : 'smtp'),
            'smtp_host' => config('mail.mailers.smtp.host'),
            'smtp_port' => config('mail.mailers.smtp.port'),
            'smtp_username' => config('mail.mailers.smtp.username'),
            'smtp_password' => '',
            'smtp_from_address' => config('mail.from.address'),
            'smtp_from_name' => config('mail.from.name'),
            'session_idle_timeout_enabled' => RuntimeSettings::sessionIdleTimeoutEnabled(),
            'session_idle_timeout_minutes' => RuntimeSettings::sessionIdleTimeoutMinutes(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Grid::make(['default' => 1, 'lg' => 2])
                    ->schema([
                        Section::make('Identitas Aplikasi')
                            ->description('Nilai ini menjadi override runtime. Kosongkan override melalui tombol reset untuk kembali memakai .env.')
                            ->schema([
                                Grid::make(['default' => 1, 'md' => 2])
                                    ->schema([
                                        TextInput::make('app_name')
                                            ->label('Nama aplikasi')
                                            ->required()
                                            ->maxLength(120),
                                        TextInput::make('app_url')
                                            ->label('URL aplikasi')
                                            ->url()
                                            ->required()
                                            ->maxLength(255)
                                            ->helperText('Gunakan URL utama aplikasi, termasuk https:// pada server produksi.'),
                                    ]),
                            ]),
                        Section::make('Google reCAPTCHA')
                            ->description('Kredensial reCAPTCHA digunakan pada form login dan pendaftaran.')
                            ->schema([
                                Toggle::make('captcha_enabled')
                                    ->label('Aktifkan reCAPTCHA')
                                    ->helperText('Jika dinonaktifkan, captcha tidak ditampilkan dan tidak divalidasi.'),
                                Grid::make(['default' => 1, 'md' => 2])
                                    ->schema([
                                        TextInput::make('recaptcha_site_key')
                                            ->label('Site key')
                                            ->maxLength(255),
                                        TextInput::make('recaptcha_secret_key')
                                            ->label('Secret key')
                                            ->password()
                                            ->revealable()
                                            ->autocomplete('new-password')
                                            ->maxLength(500)
                                            ->helperText($this->secretConfigured
                                                ? 'Secret sudah dikonfigurasi dan tidak ditampilkan. Isi hanya jika ingin menggantinya.'
                                                : 'Secret akan disimpan terenkripsi. Isi bila reCAPTCHA diaktifkan.'),
                                    ]),
                            ]),
                        Section::make('Masa Sesi')
                            ->description('Akhiri sesi setelah pengguna tidak aktif. Pengguna harus login kembali setelah timeout.')
                            ->schema([
                                Toggle::make('session_idle_timeout_enabled')
                                    ->label('Aktifkan auto-logout saat tidak aktif')
                                    ->helperText('Berlaku untuk sesi aplikasi dan panel administrasi.'),
                                TextInput::make('session_idle_timeout_minutes')
                                    ->label('Batas waktu tidak aktif (menit)')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->maxValue(1440)
                                    ->helperText('Default yang disarankan: 10 menit.'),
                            ]),
                        Section::make('SMTP')
                            ->columnSpanFull()
                            ->description('Saat diaktifkan, email aplikasi dikirim menggunakan konfigurasi SMTP ini.')
                            ->schema([
                                Toggle::make('smtp_enabled')
                                    ->label('Gunakan SMTP untuk email aplikasi')
                                    ->helperText('Jika dimatikan, aplikasi kembali memakai mailer dari .env.')
                                    ->columnSpanFull(),
                                Grid::make(['default' => 1, 'md' => 3, 'lg' => 4])
                                    ->schema([
                                        TextInput::make('smtp_host')
                                            ->label('Host SMTP')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('smtp_port')
                                            ->label('Port SMTP')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1)
                                            ->maxValue(65535),
                                        Select::make('smtp_scheme')
                                            ->label('Keamanan koneksi')
                                            ->options([
                                                'smtp' => 'STARTTLS (umumnya port 587)',
                                                'smtps' => 'SSL/TLS (umumnya port 465)',
                                            ])
                                            ->required(),
                                        TextInput::make('smtp_username')
                                            ->label('Username SMTP')
                                            ->maxLength(255),
                                        TextInput::make('smtp_password')
                                            ->label('Password SMTP')
                                            ->password()
                                            ->revealable()
                                            ->autocomplete('new-password')
                                            ->maxLength(500)
                                            ->helperText($this->smtpPasswordConfigured
                                                ? 'Password sudah dikonfigurasi dan tidak ditampilkan. Isi hanya jika ingin menggantinya.'
                                                : 'Password akan disimpan terenkripsi. Kosongkan bila server SMTP tidak memerlukan autentikasi.'),
                                        TextInput::make('smtp_from_address')
                                            ->label('Alamat pengirim')
                                            ->email()
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('smtp_from_name')
                                            ->label('Nama pengirim')
                                            ->required()
                                            ->maxLength(120),
                                        // ->columnSpanFull(),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (($data['captcha_enabled'] ?? false)
            && (blank($data['recaptcha_site_key'] ?? null)
                || (blank($data['recaptcha_secret_key'] ?? null) && blank(config('services.recaptcha.secret_key'))))
        ) {
            Notification::make()
                ->title('Kredensial reCAPTCHA belum lengkap')
                ->body('Isi site key dan secret key sebelum mengaktifkan reCAPTCHA.')
                ->danger()
                ->send();

            return;
        }

        RuntimeSettings::save($data);
        $this->secretConfigured = filled(config('services.recaptcha.secret_key'));
        $this->smtpPasswordConfigured = filled(config('mail.mailers.smtp.password'));
        $data['recaptcha_secret_key'] = '';
        $data['smtp_password'] = '';
        $data['smtp_enabled'] = RuntimeSettings::smtpEnabled();
        $this->form->fill($data);
        $this->dispatch(
            'idle-timeout-updated',
            enabled: RuntimeSettings::sessionIdleTimeoutEnabled(),
            minutes: RuntimeSettings::sessionIdleTimeoutMinutes(),
        );

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }

    public function resetToEnvironment(): void
    {
        RuntimeSettings::resetToEnvironment();
        $this->secretConfigured = filled(config('services.recaptcha.secret_key'));
        $this->smtpPasswordConfigured = filled(config('mail.mailers.smtp.password'));

        $this->form->fill([
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'captcha_enabled' => RuntimeSettings::captchaEnabled(),
            'recaptcha_site_key' => config('services.recaptcha.site_key'),
            'recaptcha_secret_key' => '',
            'smtp_enabled' => RuntimeSettings::smtpEnabled(),
            'smtp_scheme' => config('mail.mailers.smtp.scheme')
                ?: ((int) config('mail.mailers.smtp.port') === 465 ? 'smtps' : 'smtp'),
            'smtp_host' => config('mail.mailers.smtp.host'),
            'smtp_port' => config('mail.mailers.smtp.port'),
            'smtp_username' => config('mail.mailers.smtp.username'),
            'smtp_password' => '',
            'smtp_from_address' => config('mail.from.address'),
            'smtp_from_name' => config('mail.from.name'),
            'session_idle_timeout_enabled' => RuntimeSettings::sessionIdleTimeoutEnabled(),
            'session_idle_timeout_minutes' => RuntimeSettings::sessionIdleTimeoutMinutes(),
        ]);
        $this->dispatch(
            'idle-timeout-updated',
            enabled: RuntimeSettings::sessionIdleTimeoutEnabled(),
            minutes: RuntimeSettings::sessionIdleTimeoutMinutes(),
        );

        Notification::make()
            ->title('Pengaturan kembali menggunakan .env')
            ->success()
            ->send();
    }
}
