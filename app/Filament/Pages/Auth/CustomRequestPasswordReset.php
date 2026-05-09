<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset;

use Illuminate\Contracts\Support\Htmlable;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions;
use Filament\Support\Enums\Alignment;
use Filament\Schemas\Components\RenderHook;
use Filament\View\PanelsRenderHook;

class CustomRequestPasswordReset extends BaseRequestPasswordReset
{
    use HasCustomLogo;

    public function getSubheading(): string | Htmlable | null
    {
        return null;
    }

    public function getTitle(): string | Htmlable
    {
        return 'Lupa Kata Sandi';
    }


    public function getHeading(): string | Htmlable
    {
        return 'Atur Ulang Kata Sandi';
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                RenderHook::make(PanelsRenderHook::AUTH_PASSWORD_RESET_REQUEST_FORM_BEFORE),
                $this->getFormContentComponent(),
                RenderHook::make(PanelsRenderHook::AUTH_PASSWORD_RESET_REQUEST_FORM_AFTER),
                Actions::make([
                    $this->loginAction,
                ])->alignment(Alignment::Center),
            ]);
    }
}

