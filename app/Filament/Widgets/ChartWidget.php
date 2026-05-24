<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget as FilamentChartWidget;

/**
 * Base ChartWidget dengan Built-in Loading Indicator
 * 
 * Semua chart widget extend dari class ini akan otomatis menampilkan
 * loading spinner saat chart data sedang diproses/dirender.
 */
abstract class ChartWidget extends FilamentChartWidget
{
    protected string $view = 'filament.widgets.chart-widget';
}
