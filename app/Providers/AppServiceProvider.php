<?php

namespace App\Providers;

use App\Support\ValidationPeriod;
use Illuminate\Support\ServiceProvider;
use Filament\Actions\Action;
use Filament\Actions\Imports\ImportColumn;
use Illuminate\Validation\Rules\Password;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Livewire\Component;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TrashedFilter;

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
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            if ($user->hasRole('pengawas')) {
                if (in_array($ability, ['create', 'update', 'delete', 'restore', 'forceDelete', 'replicate'])) {
                    return false;
                }
            }
        });

        \Illuminate\Support\Facades\Gate::guessPolicyNamesUsing(function ($modelClass) {
            $class = 'App\\Policies\\' . class_basename($modelClass) . 'Policy';
            if (class_exists($class)) {
                return $class;
            }
            return \App\Policies\GlobalPolicy::class;
        });

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

        TrashedFilter::configureUsing(function (TrashedFilter $filter): void {
            $filter->hidden(fn (): bool => filament()->getCurrentPanel()?->getId() !== 'dinas');
        }, isImportant: true);

        $hideNonReadOnlyActions = function ($action): void {
            if (self::isReadOnlyAction($action)) {
                return;
            }

            $action->hidden(function (?Component $livewire) {
                // Pengaduan/Tiket tidak terkunci oleh periode validasi
                // Operator selalu bisa membuat dan mengelola pengaduan
                if ($livewire instanceof \App\Filament\Resources\Pengaduans\Pages\ListPengaduans) {
                    return false;
                }

                if (auth()->check() && auth()->user()->hasRole('pengawas')) {
                    return true;
                }
                
                return ValidationPeriod::isLockedForOperatorPanel();
            });
        };

        Action::configureUsing($hideNonReadOnlyActions, isImportant: true);

        // Add example macro to ImportColumn for templates
        ImportColumn::macro('example', function (string $value) {
            $this->examples = [$value];
            return $this;
        });

        ImportColumn::macro('getExamples', function () {
            return $this->examples ?? [];
        });

        RestoreBulkAction::configureUsing(function (RestoreBulkAction $action): void {
            $action->hidden(function (Component $livewire) {
                if (filament()->getCurrentPanel()?->getId() !== 'dinas') {
                    return true;
                }
                if ($livewire instanceof HasTable) {
                    $trashedFilterState = $livewire->getTableFilterState('trashed') ?? $livewire->getTableFilterState(TrashedFilter::class) ?? [];
                    $val = $trashedFilterState['value'] ?? null;
                    return $val !== false; // Sembunyikan jika BUKAN filter "Hanya data yang dihapus"
                }
                return true;
            });
        }, isImportant: true);

        ForceDeleteBulkAction::configureUsing(function (ForceDeleteBulkAction $action): void {
            $action->hidden(function (Component $livewire) {
                if (filament()->getCurrentPanel()?->getId() !== 'dinas') {
                    return true;
                }
                if ($livewire instanceof HasTable) {
                    $trashedFilterState = $livewire->getTableFilterState('trashed') ?? $livewire->getTableFilterState(TrashedFilter::class) ?? [];
                    $val = $trashedFilterState['value'] ?? null;
                    return !(! $val && filled($val));
                }
                return false;
            });
        }, isImportant: true);

        RestoreAction::configureUsing(function (RestoreAction $action): void {
            $action->hidden(function (Component $livewire) {
                if (filament()->getCurrentPanel()?->getId() !== 'dinas') {
                    return true;
                }
                if ($livewire instanceof HasTable) {
                    $trashedFilterState = $livewire->getTableFilterState('trashed') ?? $livewire->getTableFilterState(TrashedFilter::class) ?? [];
                    $val = $trashedFilterState['value'] ?? null;
                    return $val !== false;
                }
                return false;
            });
        }, isImportant: true);

        ForceDeleteAction::configureUsing(function (ForceDeleteAction $action): void {
            $action->hidden(function (Component $livewire) {
                if (filament()->getCurrentPanel()?->getId() !== 'dinas') {
                    return true;
                }
                if ($livewire instanceof HasTable) {
                    $trashedFilterState = $livewire->getTableFilterState('trashed') ?? $livewire->getTableFilterState(TrashedFilter::class) ?? [];
                    $val = $trashedFilterState['value'] ?? null;
                    return $val !== false;
                }
                return false;
            });
        }, isImportant: true);

        DeleteBulkAction::configureUsing(function (DeleteBulkAction $action): void {
            $action->hidden(function (Component $livewire) {
                if ($livewire instanceof HasTable) {
                    $trashedFilterState = $livewire->getTableFilterState('trashed') ?? $livewire->getTableFilterState(TrashedFilter::class) ?? [];
                    $val = $trashedFilterState['value'] ?? null;
                    if ($val === 'false' || $val === false || $val === 0 || $val === '0') {
                        return true;
                    }
                    if (blank($val)) {
                        return false;
                    }
                    return false;
                }
                return false;
            });
        }, isImportant: true);

        DeleteAction::configureUsing(function (DeleteAction $action): void {
            $action->hidden(function (\Illuminate\Database\Eloquent\Model $record) {
                if (method_exists($record, 'trashed')) {
                    return $record->trashed(); // Sembunyikan tombol hapus biasa jika record sudah terhapus
                }
                return false;
            });
        }, isImportant: true);
    }

    protected static function isReadOnlyAction($action): bool
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
