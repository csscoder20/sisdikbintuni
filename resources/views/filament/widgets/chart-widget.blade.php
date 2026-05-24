@php
    use Filament\Widgets\View\Components\ChartWidgetComponent;
    use Illuminate\View\ComponentAttributeBag;
    use Filament\Support\Enums\Color;

    $color = $this->getColor();
    $heading = $this->getHeading();
    $description = $this->getDescription();
    $filters = $this->getFilters();
    $isCollapsible = $this->isCollapsible();
    $type = $this->getType();
    $maxHeight = $this->getMaxHeight();
    $hasMaxHeight = filled($maxHeight) && $maxHeight !== '100%';

    // Build the attributes for the chart container
    $chartAttributes = \Filament\Support\prepare_inherited_attributes(new ComponentAttributeBag())
        ->color(ChartWidgetComponent::class, $color)
        ->class(['fi-wi-chart-canvas-ctn', 'fi-wi-chart-canvas-ctn-no-aspect-ratio' => $hasMaxHeight]);
@endphp

<x-filament-widgets::widget class="fi-wi-chart">
    <x-filament::section :description="$description" :heading="$heading" :collapsible="$isCollapsible">
        @if ($filters || method_exists($this, 'getFiltersSchema'))
            <x-slot name="afterHeader">
                @if ($filters)
                    <x-filament::input.wrapper inline-prefix wire:target="filter" class="fi-wi-chart-filter">
                        <x-filament::input.select inline-prefix wire:model.live="filter">
                            @foreach ($filters as $value => $label)
                                <option value="{{ $value }}">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                @endif

                @if (method_exists($this, 'getFiltersSchema'))
                    <x-filament::dropdown placement="bottom-end" shift width="xs" class="fi-wi-chart-filter">
                        <x-slot name="trigger">
                            {{ $this->getFiltersTriggerAction() }}
                        </x-slot>

                        <div class="fi-wi-chart-filter-content">
                            {{ $this->getFiltersSchema() }}

                            @if (method_exists($this, 'hasDeferredFilters') && $this->hasDeferredFilters())
                                <div class="fi-wi-chart-filter-content-actions-ctn">
                                    {{ $this->getFiltersApplyAction() }}

                                    {{ $this->getFiltersResetAction() }}
                                </div>
                            @endif
                        </div>
                    </x-filament::dropdown>
                @endif
            </x-slot>
        @endif

        <div @if ($pollingInterval = $this->getPollingInterval()) wire:poll.{{ $pollingInterval }}="updateChartData" @endif
            class="relative" data-chart-widget="{{ $this->getId() }}" style="position: relative; min-height: 200px;">
            {{-- Loading Overlay --}}
            <div class="chart-loading-overlay"
                style="
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(255, 255, 255, 0.8);
                    backdrop-filter: blur(2px);
                    border-radius: inherit;
                    display: none;
                    align-items: center;
                    justify-content: center;
                    z-index: 50;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    pointer-events: none;
                ">
                <div style="text-align: center;">
                    <div style="margin-bottom: 12px;">
                        <svg style="
                            width: 32px;
                            height: 32px;
                            color: #3b82f6;
                            margin: 0 auto;
                            animation: spin-loader 1s linear infinite;
                        "
                            fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path style="opacity: 0.75;" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    <p
                        style="
                            font-size: 14px;
                            font-weight: 500;
                            color: #4b5563;
                            margin: 0;
                        ">
                        Memuat grafik...
                    </p>
                </div>
            </div>

            <div x-load
                x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('chart', 'filament/widgets') }}"
                wire:ignore data-chart-type="{{ $type }}" x-data="chart({
                    cachedData: @js($this->getCachedData()),
                    options: @js($this->getOptions()),
                    type: @js($type),
                })" {!! $chartAttributes !!}>
                <canvas x-ref="canvas" @style(['width: 100%', 'height: 100%; max-height: 100%' => !$hasMaxHeight, "max-height: {$maxHeight}" => $hasMaxHeight])></canvas>

                <span x-ref="backgroundColorElement" class="fi-wi-chart-bg-color"></span>

                <span x-ref="borderColorElement" class="fi-wi-chart-border-color"></span>

                <span x-ref="gridColorElement" class="fi-wi-chart-grid-color"></span>

                <span x-ref="textColorElement" class="fi-wi-chart-text-color"></span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

<style>
    @keyframes spin-loader {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .dark .chart-loading-overlay {
        background: rgba(31, 41, 55, 0.85) !important;
    }

    .dark .chart-loading-overlay p {
        color: #d1d5db !important;
    }
</style>

<script>
    (function() {
        const chartWidgetId = @js($this->getId());

        const init = () => {
            const chartContainer = document.querySelector('[data-chart-widget="' + chartWidgetId + '"]');
            if (!chartContainer) return;

            const overlay = chartContainer.querySelector('.chart-loading-overlay');
            if (!overlay) return;

            // Overlay dimulai dengan display: none (tidak terlihat)
            // Ini adalah solusi: default sudah hidden

            // Jika diperlukan untuk Livewire updates, tampilkan dulu
            document.addEventListener('livewire:updating', () => {
                overlay.style.display = 'flex';
                overlay.style.opacity = '1';
                overlay.style.pointerEvents = 'auto';
            });

            // Setelah update selesai, hide kembali
            document.addEventListener('livewire:updated', () => {
                overlay.style.display = 'none';
                overlay.style.opacity = '0';
                overlay.style.pointerEvents = 'none';
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
</script>
