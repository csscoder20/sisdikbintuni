<div x-data="{ open: false }" x-init="$watch('open', value => { if (value) $nextTick(() => $refs.searchInput.focus()) })" @click.away="open = false" class="school-selector-container" style="display: flex; align-items: center; position: relative;">
    <!-- Trigger Button -->
    <div @click="open = !open" 
         style="cursor: pointer; display: flex; align-items: center; background-color: #f3f4f6; border-radius: 9999px; padding: 6px 14px; border: 1px solid #d1d5db; box-shadow: 0 1px 2px rgba(0,0,0,0.05); min-width: 220px; max-width: 300px; transition: all 0.2s;">
        
        <!-- Icon -->
        <div style="flex-shrink: 0; margin-right: 10px; color: #ea580c; display: flex; align-items: center;">
            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>

        <!-- Selected Label -->
        <span style="font-size: 0.875rem; line-height: 1.25rem; font-weight: 600; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex-grow: 1;">
            {{ $selectedSekolahNama }}
        </span>

        <!-- Chevron -->
        <div style="margin-left: 8px; flex-shrink: 0; display: flex; align-items: center; width: 12px; height: 12px;">
            <svg style="width: 12px; height: 12px; display: block; color: #6b7280;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="#6b7280">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </div>
    </div>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         style="display: none; position: absolute; top: 110%; left: 0; width: 320px; background-color: white; border: 1px solid #d1d5db; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); z-index: 50; padding: 8px;">
        
        <!-- Search Input -->
        <div style="margin-bottom: 8px; position: relative;">
            <input type="text" 
                   wire:model.live.debounce.300ms="search"
                   x-ref="searchInput"
                   placeholder="Cari sekolah..."
                   style="width: 100%; padding: 8px 12px; font-size: 0.875rem; border: 1px solid #e5e7eb; border-radius: 8px; outline: none; focus: border-orange-500;">
        </div>

        <!-- Results List -->
        <div style="max-height: 250px; overflow-y: auto; display: flex; flex-direction: column; gap: 2px;">
            <div wire:click="selectSekolah(null)" 
                 @click="open = false"
                 style="padding: 8px 12px; font-size: 0.875rem; cursor: pointer; border-radius: 6px; color: #6b7280; transition: background 0.2s;"
                 onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
                -- Kosongkan Pilihan --
            </div>

            @foreach($sekolahs as $sekolah)
                <div wire:click="selectSekolah({{ $sekolah->id }})" 
                     @click="open = false"
                     style="padding: 8px 12px; font-size: 0.875rem; cursor: pointer; border-radius: 6px; color: #111827; transition: all 0.2s; {{ $selectedSekolahId == $sekolah->id ? 'background-color: #ffedd5; color: #ea580c; font-weight: 600;' : '' }}"
                     onmouseover="if(this.style.backgroundColor != 'rgb(255, 237, 213)') this.style.backgroundColor='#f3f4f6'" 
                     onmouseout="if(this.style.backgroundColor != 'rgb(255, 237, 213)') this.style.backgroundColor='transparent'">
                    {{ $sekolah->nama }}
                </div>
            @endforeach

            @if($sekolahs->isEmpty())
                <div style="padding: 12px; font-size: 0.875rem; color: #9ca3af; text-align: center;">
                    Sekolah tidak ditemukan...
                </div>
            @endif
        </div>
    </div>
</div>
