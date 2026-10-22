<?php

namespace App\Support;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class RuntimeSettings
{
    public const APP_NAME_KEY = 'runtime.app_name';
    public const APP_URL_KEY = 'runtime.app_url';
    public const RECAPTCHA_ENABLED_KEY = 'runtime.recaptcha_enabled';
    public const RECAPTCHA_SITE_KEY = 'runtime.recaptcha_site_key';
    public const RECAPTCHA_SECRET_KEY = 'runtime.recaptcha_secret_key';
    public const SMTP_ENABLED_KEY = 'runtime.smtp_enabled';
    public const SMTP_SCHEME_KEY = 'runtime.smtp_scheme';
    public const SMTP_HOST_KEY = 'runtime.smtp_host';
    public const SMTP_PORT_KEY = 'runtime.smtp_port';
    public const SMTP_USERNAME_KEY = 'runtime.smtp_username';
    public const SMTP_PASSWORD_KEY = 'runtime.smtp_password';
    public const SMTP_FROM_ADDRESS_KEY = 'runtime.smtp_from_address';
    public const SMTP_FROM_NAME_KEY = 'runtime.smtp_from_name';

    private const ENCRYPTED_PREFIX = 'encrypted:';

    private static ?array $environmentDefaults = null;

    public static function apply(): void
    {
        self::$environmentDefaults ??= [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'recaptcha_enabled' => config('services.recaptcha.enabled', true),
            'recaptcha_site_key' => config('services.recaptcha.site_key'),
            'recaptcha_secret_key' => config('services.recaptcha.secret_key'),
            'mail_default' => config('mail.default'),
            'smtp_scheme' => config('mail.mailers.smtp.scheme')
                ?: ((int) config('mail.mailers.smtp.port') === 465 ? 'smtps' : 'smtp'),
            'smtp_encryption' => config('mail.mailers.smtp.encryption'),
            'smtp_host' => config('mail.mailers.smtp.host'),
            'smtp_port' => config('mail.mailers.smtp.port'),
            'smtp_username' => config('mail.mailers.smtp.username'),
            'smtp_password' => config('mail.mailers.smtp.password'),
            'smtp_from_address' => config('mail.from.address'),
            'smtp_from_name' => config('mail.from.name'),
        ];

        try {
            $settings = AppSetting::query()
                ->whereIn('key', self::keys())
                ->get()
                ->keyBy('key');
        } catch (Throwable) {
            return;
        }

        $appName = self::value($settings, self::APP_NAME_KEY, self::$environmentDefaults['app_name']);
        $appUrl = self::value($settings, self::APP_URL_KEY, self::$environmentDefaults['app_url']);
        $recaptchaSiteKey = self::value($settings, self::RECAPTCHA_SITE_KEY, self::$environmentDefaults['recaptcha_site_key']);
        $recaptchaEnabled = self::value($settings, self::RECAPTCHA_ENABLED_KEY, self::$environmentDefaults['recaptcha_enabled']);
        $smtpEnabled = (bool) self::value(
            $settings,
            self::SMTP_ENABLED_KEY,
            self::$environmentDefaults['mail_default'] === 'smtp'
        );

        $mailConfig = [
            'mail.default' => $smtpEnabled ? 'smtp' : self::$environmentDefaults['mail_default'],
            'mail.mailers.smtp.scheme' => $smtpEnabled
                ? self::value($settings, self::SMTP_SCHEME_KEY, self::$environmentDefaults['smtp_scheme'])
                : self::$environmentDefaults['smtp_scheme'],
            'mail.mailers.smtp.encryption' => $smtpEnabled
                ? (self::value($settings, self::SMTP_SCHEME_KEY, self::$environmentDefaults['smtp_scheme']) === 'smtps' ? 'ssl' : 'tls')
                : self::$environmentDefaults['smtp_encryption'],
            'mail.mailers.smtp.host' => $smtpEnabled
                ? self::value($settings, self::SMTP_HOST_KEY, self::$environmentDefaults['smtp_host'])
                : self::$environmentDefaults['smtp_host'],
            'mail.mailers.smtp.port' => $smtpEnabled
                ? self::value($settings, self::SMTP_PORT_KEY, self::$environmentDefaults['smtp_port'])
                : self::$environmentDefaults['smtp_port'],
            'mail.mailers.smtp.username' => $smtpEnabled
                ? self::value($settings, self::SMTP_USERNAME_KEY, self::$environmentDefaults['smtp_username'])
                : self::$environmentDefaults['smtp_username'],
            'mail.mailers.smtp.password' => $smtpEnabled
                ? self::secret($settings, self::SMTP_PASSWORD_KEY, self::$environmentDefaults['smtp_password'])
                : self::$environmentDefaults['smtp_password'],
            'mail.from.address' => $smtpEnabled
                ? self::value($settings, self::SMTP_FROM_ADDRESS_KEY, self::$environmentDefaults['smtp_from_address'])
                : self::$environmentDefaults['smtp_from_address'],
            'mail.from.name' => $smtpEnabled
                ? self::value($settings, self::SMTP_FROM_NAME_KEY, self::$environmentDefaults['smtp_from_name'])
                : self::$environmentDefaults['smtp_from_name'],
        ];

        config([
            'app.name' => $appName,
            'app.url' => $appUrl,
            'filesystems.disks.public.url' => rtrim((string) $appUrl, '/') . '/storage',
            'services.recaptcha.enabled' => (bool) $recaptchaEnabled,
            'services.recaptcha.site_key' => $recaptchaSiteKey,
            'services.recaptcha.secret_key' => self::secret(
                $settings,
                self::RECAPTCHA_SECRET_KEY,
                self::$environmentDefaults['recaptcha_secret_key']
            ),
            ...$mailConfig,
        ]);
    }

    public static function save(array $data): void
    {
        AppSetting::setValue(self::APP_NAME_KEY, trim($data['app_name']));
        AppSetting::setValue(self::APP_URL_KEY, rtrim(trim($data['app_url']), '/'));
        AppSetting::setValue(self::RECAPTCHA_ENABLED_KEY, (bool) ($data['captcha_enabled'] ?? false));
        AppSetting::setValue(self::RECAPTCHA_SITE_KEY, trim($data['recaptcha_site_key'] ?? ''));

        if (filled($data['recaptcha_secret_key'] ?? null)) {
            self::storeSecret(self::RECAPTCHA_SECRET_KEY, $data['recaptcha_secret_key']);
        }

        AppSetting::setValue(self::SMTP_ENABLED_KEY, (bool) ($data['smtp_enabled'] ?? false));
        AppSetting::setValue(self::SMTP_SCHEME_KEY, $data['smtp_scheme'] ?? 'smtp');
        AppSetting::setValue(self::SMTP_HOST_KEY, trim($data['smtp_host'] ?? ''));
        AppSetting::setValue(self::SMTP_PORT_KEY, (int) ($data['smtp_port'] ?? 587));
        AppSetting::setValue(self::SMTP_USERNAME_KEY, trim($data['smtp_username'] ?? ''));
        AppSetting::setValue(self::SMTP_FROM_ADDRESS_KEY, trim($data['smtp_from_address'] ?? ''));
        AppSetting::setValue(self::SMTP_FROM_NAME_KEY, trim($data['smtp_from_name'] ?? ''));

        if (filled($data['smtp_password'] ?? null)) {
            self::storeSecret(self::SMTP_PASSWORD_KEY, $data['smtp_password']);
        }

        self::apply();
    }

    public static function resetToEnvironment(): void
    {
        AppSetting::query()->whereIn('key', self::keys())->delete();

        foreach (self::keys() as $key) {
            Cache::forget("app-setting:{$key}");
        }

        self::apply();
    }

    public static function captchaEnabled(): bool
    {
        return (bool) config('services.recaptcha.enabled')
            && filled(config('services.recaptcha.site_key'))
            && filled(config('services.recaptcha.secret_key'));
    }

    public static function smtpEnabled(): bool
    {
        return config('mail.default') === 'smtp';
    }

    private static function keys(): array
    {
        return [
            self::APP_NAME_KEY,
            self::APP_URL_KEY,
            self::RECAPTCHA_ENABLED_KEY,
            self::RECAPTCHA_SITE_KEY,
            self::RECAPTCHA_SECRET_KEY,
            self::SMTP_ENABLED_KEY,
            self::SMTP_SCHEME_KEY,
            self::SMTP_HOST_KEY,
            self::SMTP_PORT_KEY,
            self::SMTP_USERNAME_KEY,
            self::SMTP_PASSWORD_KEY,
            self::SMTP_FROM_ADDRESS_KEY,
            self::SMTP_FROM_NAME_KEY,
        ];
    }

    private static function value($settings, string $key, mixed $default): mixed
    {
        if (! $settings->has($key)) {
            return $default;
        }

        return $settings->get($key)->value ?? $default;
    }

    private static function secret($settings, string $key, mixed $default): mixed
    {
        if (! $settings->has($key)) {
            return $default;
        }

        $stored = $settings->get($key)->value;

        if (! is_string($stored) || $stored === '') {
            return '';
        }

        if (! str_starts_with($stored, self::ENCRYPTED_PREFIX)) {
            return $stored;
        }

        try {
            return Crypt::decryptString(substr($stored, strlen(self::ENCRYPTED_PREFIX)));
        } catch (Throwable) {
            return '';
        }
    }

    private static function storeSecret(string $key, string $value): void
    {
        AppSetting::setValue(
            $key,
            self::ENCRYPTED_PREFIX . Crypt::encryptString(trim($value))
        );
    }
}
