<?php

namespace App\Providers;

use App\Support\ValidationPeriod;
use Illuminate\Support\ServiceProvider;
use Filament\Actions\Action;
use Filament\Actions\Imports\ImportColumn;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Filament\Auth\Http\Responses\Contracts\LoginResponse::class,
            \App\Http\Responses\Filament\CustomLoginResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            \App\Listeners\LogAuthenticationActivity::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Logout::class,
            \App\Listeners\LogAuthenticationActivity::class
        );

        // Globally disable modal click-away using the unified Action class
        Action::configureUsing(fn (Action $action) => $action->closeModalByClickingAway(false));

        Action::configureUsing(function (Action $action): void {
            if (self::isReadOnlyAction($action)) {
                return;
            }

            $action
                ->hidden(fn (): bool => ValidationPeriod::isLockedForOperatorPanel());
        }, isImportant: true);

        // Add example macro to ImportColumn for templates
        ImportColumn::macro('example', function (string $value) {
            $this->examples = [$value];
            return $this;
        });

        ImportColumn::macro('getExamples', function () {
            return $this->examples ?? [];
        });
    }

    protected static function isReadOnlyAction(Action $action): bool
    {
        $name = strtolower((string) $action->getName());

        if ($name === '') {
            return false;
        }

        $allowedNames = [
            'view',
            'back',
            'cancel',
            'close',
            'periode',
            'downloadexample',
            'profile',
            'pengaturan-akun',
            'kunjungi-web',
            'logout',
        ];

        if (in_array($name, $allowedNames, true)) {
            return true;
        }

        foreach (['view', 'download', 'export', 'cetak'] as $prefix) {
            if (str_starts_with($name, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
