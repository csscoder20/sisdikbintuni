<div class="relative" data-chart-widget="{{ $widget->getId() }}" style="position: relative;">
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
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
        opacity: 1;
        transition: opacity 0.3s ease;
        pointer-events: auto;
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
                Memuat grafik...</p>
        </div>
    </div>

    {{-- Chart Content --}}
    <div style="position: relative;">
        {!! $chartHtml !!}
    </div>
</div>

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
        // Wait for DOM to be ready
        const initLoadingOverlay = () => {
            const chartContainer = document.querySelector('[data-chart-widget="{{ $widget->getId() }}"]');
            if (!chartContainer) {
                setTimeout(initLoadingOverlay, 100);
                return;
            }

            const loadingOverlay = chartContainer.querySelector('.chart-loading-overlay');
            if (!loadingOverlay) return;

            // Hide loading setelah 1.5 detik
            const hideLoadingTimeout = setTimeout(() => {
                loadingOverlay.style.opacity = '0';
                loadingOverlay.style.pointerEvents = 'none';
            }, 1500);

            // Handle Livewire updates
            const handleLivewireUpdating = () => {
                clearTimeout(hideLoadingTimeout);
                loadingOverlay.style.opacity = '1';
                loadingOverlay.style.pointerEvents = 'auto';
            };

            const handleLivewireUpdated = () => {
                setTimeout(() => {
                    loadingOverlay.style.opacity = '0';
                    loadingOverlay.style.pointerEvents = 'none';
                }, 300);
            };

            document.addEventListener('livewire:updating', handleLivewireUpdating);
            document.addEventListener('livewire:updated', handleLivewireUpdated);
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initLoadingOverlay);
        } else {
            initLoadingOverlay();
        }
    })();
</script>
