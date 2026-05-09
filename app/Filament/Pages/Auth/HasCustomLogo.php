<?php

namespace App\Filament\Pages\Auth;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

trait HasCustomLogo
{
    public function getLogo(): string | Htmlable | null
    {
        return new HtmlString(Blade::render('
            <div class="flex items-center justify-center gap-x-3">
                <img src="{{ asset(\'assets/logo/logo-bintuni.png\') }}" class="h-10 w-10">
                <div class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                    Sistem Pelaporan Bulanan <span class="text-primary-600">SMA/SMK</span>
                </div>
            </div>
        '));
    }
}
