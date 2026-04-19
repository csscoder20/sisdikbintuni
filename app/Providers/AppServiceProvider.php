<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Actions\Action;
use Filament\Actions\Imports\ImportColumn;

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

        // Add example macro to ImportColumn for templates
        ImportColumn::macro('example', function (string $value) {
            $this->examples = [$value];
            return $this;
        });

        ImportColumn::macro('getExamples', function () {
            return $this->examples ?? [];
        });
    }
}
