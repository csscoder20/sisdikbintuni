<div>
    <style>
        .validation-period-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.65rem;
            width: 100%;
            margin: 0;
            padding: 0.5rem 0.75rem;
            /* border: 1px solid #e5e7eb; */
            /* border-radius: 0.5rem; */
            /* background: #ffffff; */
            /* box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05); */
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

        @media (min-width: 1024px) {
            .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .sidebar-validation-period-hook {
                display: flex;
                justify-content: center;
                padding: 0.75rem 0 !important;
            }

            .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .validation-period-toggle {
                justify-content: center;
                width: auto;
                padding: 0;
            }

            .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .validation-period-toggle-label {
                display: none;
            }
        }
    </style>

    <div class="validation-period-toggle" title="{{ $active ? 'Periode validasi aktif' : 'Periode validasi ditutup' }}">
        <span class="validation-period-toggle-label">
            @if ($active)
                <x-filament::icon icon="heroicon-o-shield-check" />
            @else
                <x-filament::icon icon="heroicon-o-no-symbol" />
            @endif
            Periode Validasi
        </span>

        <button type="button" class="validation-period-toggle-switch" wire:click="requestToggle" role="switch"
            aria-checked="{{ $active ? 'true' : 'false' }}" aria-label="Toggle periode validasi">
            <span class="validation-period-toggle-slider"></span>
        </button>
    </div>

    <div x-data="{
        open: false,
        title: '',
        message: '',
        confirmText: '',
        confirmColor: 'primary',
        targetActive: false,
        init() {
            window.addEventListener('validation-period-toggle-confirm', (event) => {
                const detail = Array.isArray(event.detail) ? event.detail[0] : event.detail;
                this.title = detail.title;
                this.message = detail.message;
                this.confirmText = detail.confirmText;
                this.confirmColor = detail.confirmColor;
                this.targetActive = detail.targetActive;
                this.open = true;
            });
        },
        confirm() {
            this.open = false;
            @this.confirmToggle();
        },
        cancel() {
            this.open = false;
            @this.cancelToggle();
        }
    }">
        <template x-teleport="body">
            <div x-show="open" x-cloak style="position: fixed; inset: 0; z-index: 9999;"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                {{-- Backdrop --}}
                <div
                    style="position: absolute; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(2px);">
                </div>

                {{-- Centering wrapper --}}
                <div
                    style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; padding: 1rem; pointer-events: none;">
                    {{-- Dialog Panel --}}
                    <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1); padding: 1.5rem; max-width: 28rem; width: 100%; pointer-events: auto;"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                        {{-- Close Button (X) --}}
                        <button type="button" x-on:click="cancel()"
                            style="position: absolute; top: 0.75rem; right: 0.75rem; display: flex; align-items: center; justify-content: center; width: 1.75rem; height: 1.75rem; border-radius: 9999px; border: none; background: transparent; color: #9ca3af; cursor: pointer; transition: all 0.15s ease-in-out;"
                            onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#4b5563';"
                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#9ca3af';">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" style="width: 1.15rem; height: 1.15rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div
                            style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 1.5rem;">
                            <div style="width: 3.5rem; height: 3.5rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;"
                                :style="targetActive ? 'background:#fef9c3;' : 'background:#fee2e2;'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" style="width: 1.75rem; height: 1.75rem;"
                                    :style="targetActive ? 'color:#ca8a04;' : 'color:#dc2626;'">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <h3 style="font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0 0 0.5rem; text-align: center;"
                                x-text="title"></h3>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0; line-height: 1.5; text-align: center;"
                                x-html="message"></p>
                        </div>
                        <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem; width: 100%;">
                            <button type="button" x-on:click="cancel()"
                                style="flex: 1; display: inline-flex; align-items: center; justify-content: center; height: 2.75rem; padding: 0 1.25rem; font-size: 0.875rem; font-weight: 700; border-radius: 0.5rem; border: 1px solid #e5e7eb; background: white; color: #1f2937; cursor: pointer; transition: all 0.15s ease-in-out; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);"
                                onmouseover="this.style.backgroundColor='#f9fafb'; this.style.transform='scale(1.02)';"
                                onmouseout="this.style.backgroundColor='#ffffff'; this.style.transform='none';">
                                Batal
                            </button>
                            <button type="button" x-on:click="confirm()"
                                style="flex: 1; display: inline-flex; align-items: center; justify-content: center; height: 2.75rem; padding: 0 1.25rem; font-size: 0.875rem; font-weight: 700; border-radius: 0.5rem; border: none; cursor: pointer; color: white !important; transition: all 0.15s ease-in-out; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);"
                                :style="{ backgroundColor: targetActive ? '#16a34a' : '#dc2626', color: 'white' }"
                                onmouseover="this.style.filter='brightness(92%)'; this.style.transform='scale(1.02)';"
                                onmouseout="this.style.filter='none'; this.style.transform='none';"
                                x-text="confirmText"></button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
