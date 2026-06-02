<div x-data="{ open: false, modalOpen: false }" x-init="$watch('open', value => { if (value) $nextTick(() => $refs.searchInput?.focus()) });
$watch('modalOpen', value => { if (value) $nextTick(() => $refs.modalSearchInput?.focus()) });" @click.away="open = false" class="school-selector-container">
    <style>
        .school-selector-container {
            display: flex;
            align-items: center;
            position: relative;
            width: 100%;
        }

        .school-selector-collapsed-trigger {
            display: none;
        }

        .button.school-selector-collapsed-trigger {
            color: #9f9fa9 !important;
        }

        @media (min-width: 1024px) {
            .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .sidebar-school-selector-hook {
                display: flex;
                justify-content: center;
                padding: 0.75rem 0 !important;
            }

            .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .school-selector-expanded-trigger,
            .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .school-selector-dropdown {
                display: none !important;
            }

            .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .school-selector-container {
                justify-content: center;
                width: auto;
            }

            .fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .school-selector-collapsed-trigger {
                display: inline-flex;
            }

        }

        button.school-selector-collapsed-trigger {
            color: #9f9fa9 !important;
        }
    </style>

    <button type="button" @click="open = !open" class="school-selector-expanded-trigger"
        style="cursor: pointer; display: flex; align-items: center; background-color: #f3f4f6; border-radius: 0.5rem; padding: 8px 12px; border: 1px solid #d1d5db; box-shadow: 0 1px 2px rgba(0,0,0,0.05); width: 100%; transition: all 0.2s; text-align: left;"
        onmouseover="this.style.backgroundColor='#e5e7eb'" onmouseout="this.style.backgroundColor='#f3f4f6'"
        aria-label="Pilih sekolah">
        <span style="flex-shrink: 0; margin-right: 10px; color: #9f9fa9; display: flex; align-items: center;">
            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
            </svg>
        </span>

        <span
            style="font-size: 0.875rem; line-height: 1.25rem; font-weight: 600; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex-grow: 1; text-transform: uppercase;">
            {{ $selectedSekolahNama }}
        </span>

        <span style="margin-left: 8px; flex-shrink: 0; display: flex; align-items: center; width: 12px; height: 12px;">
            <svg style="width: 12px; height: 12px; display: block; color: #6b7280;" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="#6b7280">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </span>
    </button>

    <button type="button" @click="modalOpen = true" class="school-selector-collapsed-trigger" title="Pilih sekolah"
        aria-label="Pilih sekolah"
        style="align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; color: #ea580c; background: #f9fafb; transition: background 0.15s ease;"
        onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#f9fafb'">
        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m21 21-4.35-4.35m1.6-5.4a7 7 0 11-14 0 7 7 0 0114 0z">
            </path>
        </svg>
    </button>

    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100"
        class="school-selector-dropdown"
        style="display: none; position: absolute; top: calc(100% + 4px); left: 0; width: 100%; background-color: white; border: 1px solid #d1d5db; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); z-index: 50; padding: 8px;">
        <div style="margin-bottom: 8px; position: relative;">
            <input type="text" wire:model.live.debounce.300ms="search" x-ref="searchInput"
                placeholder="Cari sekolah..."
                style="width: 100%; padding: 8px 12px; font-size: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.375rem; outline: none;">
        </div>

        @include('livewire.partials.school-selector-options', ['closeExpression' => 'open = false'])
    </div>

    <template x-teleport="body">
        <div x-show="modalOpen" x-cloak style="position: fixed; inset: 0; z-index: 9999;"
            x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click="modalOpen = false"
                style="position: absolute; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(2px);">
            </div>

            <div
                style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; padding: 1rem; pointer-events: none;">
                <div @click.stop
                    style="position: relative; width: 100%; max-width: 26rem; border-radius: 0.75rem; background: #ffffff; box-shadow: 0 20px 25px -5px rgba(15,23,42,0.18), 0 8px 10px -6px rgba(15,23,42,0.12); padding: 1rem; pointer-events: auto;"
                    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">
                    <div
                        style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.625rem; min-width: 0;">
                            <span
                                style="display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; border-radius: 0.5rem; background: #ffedd5; color: #9f9fa9; flex: none;">
                                <svg style="width: 1.1rem; height: 1.1rem;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </span>
                            <h3
                                style="font-size: 0.95rem; font-weight: 700; color: #111827; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                Pilih Sekolah
                            </h3>
                        </div>

                        <button type="button" @click="modalOpen = false" aria-label="Tutup"
                            style="display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; border-radius: 9999px; color: #6b7280; background: transparent;"
                            onmouseover="this.style.backgroundColor='#f3f4f6'"
                            onmouseout="this.style.backgroundColor='transparent'">
                            <svg style="width: 1.15rem; height: 1.15rem;" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <input type="text" wire:model.live.debounce.300ms="search" x-ref="modalSearchInput"
                        placeholder="Cari sekolah..."
                        style="width: 100%; padding: 0.65rem 0.75rem; font-size: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; outline: none; margin-bottom: 0.75rem;">

                    @include('livewire.partials.school-selector-options', [
                        'closeExpression' => 'modalOpen = false',
                    ])
                </div>
            </div>
        </div>
    </template>
</div>
