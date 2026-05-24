<?php

namespace App\Support;

use App\Models\AppSetting;
use Filament\Facades\Filament;

class ValidationPeriod
{
    public const SETTING_KEY = 'validation_period_active';

    public static function isActive(): bool
    {
        return (bool) AppSetting::getValue(self::SETTING_KEY, true);
    }

    public static function setActive(bool $active): void
    {
        AppSetting::setValue(self::SETTING_KEY, $active);
    }

    public static function isLockedForOperatorPanel(): bool
    {
        return in_array(Filament::getCurrentPanel()?->getId(), ['sma', 'smk'], true)
            && ! self::isActive();
    }

    public static function lockMessage(): string
    {
        return 'Periode validasi sedang ditutup oleh Admin Dinas.';
    }
}
