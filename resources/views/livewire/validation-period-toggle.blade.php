<div>
    <style>
        .validation-period-toggle {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            margin-left: 0.5rem;
            padding: 0.35rem 0.65rem;
            border: 1px solid #e5e7eb;
            border-radius: 999px;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
        }

        .validation-period-toggle-label {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #334155;
            font-size: 0.78rem;
            font-weight: 700;
            line-height: 1rem;
            white-space: nowrap;
        }

        .validation-period-toggle-label svg {
            width: 1rem;
            height: 1rem;
            color: {{ $active ? '#16a34a' : '#dc2626' }};
        }

        .validation-period-toggle-switch {
            position: relative;
            width: 2.55rem;
            height: 1.45rem;
            flex: none;
            cursor: pointer;
            border: 0;
            background: transparent;
            padding: 0;
        }

        .validation-period-toggle-slider {
            position: absolute;
            inset: 0;
            border-radius: 999px;
            background: {{ $active ? '#22c55e' : '#cbd5e1' }};
            transition: background 160ms ease;
        }

        .validation-period-toggle-slider::after {
            content: '';
            position: absolute;
            top: 0.2rem;
            left: {{ $active ? '1.3rem' : '0.2rem' }};
            width: 1.05rem;
            height: 1.05rem;
            border-radius: 999px;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.25);
            transition: left 160ms ease;
        }
    </style>

    <div class="validation-period-toggle" title="{{ $active ? 'Periode validasi aktif' : 'Periode validasi ditutup' }}">
        <span class="validation-period-toggle-label">
            @if($active)
                <x-filament::icon icon="heroicon-o-shield-check" />
            @else
                <x-filament::icon icon="heroicon-o-no-symbol" />
            @endif
            Periode Validasi
        </span>

        <button
            type="button"
            class="validation-period-toggle-switch"
            wire:click="requestToggle"
            role="switch"
            aria-checked="{{ $active ? 'true' : 'false' }}"
            aria-label="Toggle periode validasi"
        >
            <span class="validation-period-toggle-slider"></span>
        </button>
    </div>

    <script>
        (() => {
            const ensureSweetAlert = () => new Promise((resolve) => {
                if (window.Swal) {
                    resolve();
                    return;
                }

                if (window.__sisdikSweetAlertLoading) {
                    const wait = window.setInterval(() => {
                        if (window.Swal) {
                            window.clearInterval(wait);
                            resolve();
                        }
                    }, 50);
                    return;
                }

                window.__sisdikSweetAlertLoading = true;
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                script.onload = () => resolve();
                document.head.appendChild(script);
            });

            if (window.__validationPeriodToggleSwalInitialized) {
                return;
            }

            window.__validationPeriodToggleSwalInitialized = true;

            window.addEventListener('validation-period-toggle-confirm', async (event) => {
                const detail = Array.isArray(event.detail) ? event.detail[0] : event.detail;

                await ensureSweetAlert();

                const result = await Swal.fire({
                    title: detail.title,
                    html: detail.message,
                    icon: detail.icon,
                    showCancelButton: true,
                    confirmButtonText: detail.confirmText,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: detail.confirmColor,
                    cancelButtonColor: '#6b7280',
                    reverseButtons: true,
                    focusCancel: ! detail.targetActive,
                });

                if (result.isConfirmed) {
                    @this.confirmToggle();
                } else {
                    @this.cancelToggle();
                }
            });
        })();
    </script>
</div>
