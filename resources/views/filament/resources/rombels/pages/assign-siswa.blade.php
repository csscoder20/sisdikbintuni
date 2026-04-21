<x-filament-panels::page>
    <div style="display: flex; flex-wrap: wrap; gap: 24px; align-items: flex-start;" x-data="{ dragging: false }">
        {{-- Panel 1: Enrolled (LEFT) --}}
        <div style="flex: 1; min-width: 350px; background: white; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; overflow: hidden;">
            {{-- Card Header --}}
            <div style="padding: 20px 24px; border-bottom: 1px solid #f3f4f6;">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px;">
                    <div>
                        <h3 style="font-weight: 800; font-size: 1.125rem; color: #10b981; margin: 0;">Siswa di {{ $record->nama }}</h3>
                        <p style="font-size: 0.75rem; color: #6b7280; font-weight: 500; margin-top: 2px;">Tahun Ajaran {{ $tahunAjaran }} • Aktif: {{ $assignedCount }}</p>
                    </div>
                </div>
                
                {{-- Integrated Search --}}
                <div style="position: relative;">
                    <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass">
                        <x-filament::input
                            type="text"
                            placeholder="Cari dalam rombel..."
                            wire:model.live.debounce.500ms="searchAssigned"
                        />
                    </x-filament::input.wrapper>
                </div>
            </div>

            {{-- Card Body --}}
            <div 
                style="min-height: 450px; max-height: 600px; overflow-y: auto; padding: 24px; border: 2px dashed transparent; transition: all 0.3s;"
                :style="dragging ? 'border-color: #10b981; background: rgba(16, 185, 129, 0.05);' : ''"
                @dragover.prevent
                @drop="
                    const id = $event.dataTransfer.getData('siswaId');
                    if (id) $wire.moveToRombel(id);
                    dragging = false;
                "
            >
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; padding: 10px 0;">
                    @forelse($assignedSiswa as $siswa)
                        <div 
                            draggable="true"
                            @dragstart="$event.dataTransfer.setData('siswaId', {{ $siswa->id }}); dragging = true"
                            @dragend="dragging = false"
                            class="group"
                            style="display: flex; align-items: center; gap: 12px; padding: 12px; margin: 0 10px; background: white; border: 1px solid #f3f4f6; border-radius: 12px; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); cursor: move; transition: all 0.2s;"
                            onmouseover="this.style.borderColor='#10b981'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';"
                            onmouseout="this.style.borderColor='#f3f4f6'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)';"
                        >
                            <div style="flex-shrink: 0; width: 36px; height: 36px; border-radius: 9999px; background: #f0fdfa; display: flex; align-items: center; justify-content: center; color: #10b981;">
                                @if($siswa->jenis_kelamin === 'Laki-laki')
                                    <x-heroicon-m-user style="width: 18px; height: 18px;" />
                                @else
                                    <x-heroicon-m-user-circle style="width: 18px; height: 18px;" />
                                @endif
                            </div>
                            
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-weight: 700; color: #1f2937; font-size: 0.8125rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $siswa->nama }}
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px; margin-top: 2px;">
                                    <span style="font-size: 0.6875rem; color: #6b7280; font-weight: 500;">{{ $siswa->nisn ?? '-' }}</span>
                                    <span style="font-size: 0.6875rem; font-weight: 700; color: {{ $siswa->jenis_kelamin === 'Laki-laki' ? '#0ea5e9' : '#ec4899' }};">
                                        {{ $siswa->jenis_kelamin === 'Laki-laki' ? 'L' : 'P' }}
                                    </span>
                                </div>
                            </div>

                            <button 
                                type="button"
                                wire:click="removeFromRombel({{ $siswa->id }})"
                                style="flex-shrink: 0; width: 28px; height: 28px; border-radius: 50%; border: none; background: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;"
                                onmouseover="this.style.background='#ef4444'; this.style.color='#fff';"
                                onmouseout="this.style.background='#fef2f2'; this.style.color='#ef4444';"
                            >
                                <x-heroicon-m-minus style="width: 16px; height: 16px;" />
                            </button>
                        </div>
                    @empty
                        <div style="grid-column: 1 / -1; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 250px; color: #d1d5db;">
                            <x-heroicon-o-user-plus style="width: 56px; height: 56px; opacity: 0.2; margin-bottom: 12px;" />
                            <p style="font-size: 0.875rem; font-weight: 500;">Belum ada siswa terpilih</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Custom Resilient Pagination --}}
            <div style="padding: 16px; border-top: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; background: #fafafa;">
                <span style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">Hal. {{ $assignedSiswa->currentPage() }} / {{ $assignedSiswa->lastPage() }}</span>
                <div style="display: flex; gap: 8px;">
                    <button type="button" wire:click="previousPage('assignedPage')" @disabled($assignedSiswa->onFirstPage())
                        style="padding: 6px 12px; border-radius: 8px; border: 1px solid #e5e7eb; background: white; font-size: 0.75rem; font-weight: 700; cursor: {{ $assignedSiswa->onFirstPage() ? 'not-allowed' : 'pointer' }}; opacity: {{ $assignedSiswa->onFirstPage() ? '0.5' : '1' }};">Prev</button>
                    <button type="button" wire:click="nextPage('assignedPage')" @disabled(!$assignedSiswa->hasMorePages())
                        style="padding: 6px 12px; border-radius: 8px; border: 1px solid #e5e7eb; background: white; font-size: 0.75rem; font-weight: 700; cursor: {{ !$assignedSiswa->hasMorePages() ? 'not-allowed' : 'pointer' }}; opacity: {{ !$assignedSiswa->hasMorePages() ? '0.5' : '1' }};">Next</button>
                </div>
            </div>
        </div>

        {{-- Panel 2: Available (RIGHT) --}}
        <div style="flex: 1; min-width: 350px; background: white; border: 1px solid #e5e7eb; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; overflow: hidden;">
            {{-- Card Header --}}
            <div style="padding: 20px 24px; border-bottom: 1px solid #f3f4f6;">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px;">
                    <div>
                        <h3 style="font-weight: 800; font-size: 1.125rem; color: #111827; margin: 0;">Siswa Belum Memiliki Rombel</h3>
                        <p style="font-size: 0.75rem; color: #6b7280; font-weight: 500; margin-top: 2px;">Tahun Ajaran {{ $tahunAjaran }} • Total: {{ $unassignedCount }}</p>
                    </div>
                </div>
                
                {{-- Integrated Search --}}
                <div style="position: relative;">
                    <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass">
                        <x-filament::input
                            type="text"
                            placeholder="Cari nama atau NISN..."
                            wire:model.live.debounce.500ms="searchUnassigned"
                        />
                    </x-filament::input.wrapper>
                </div>
            </div>

            {{-- Card Body --}}
            <div 
                style="min-height: 450px; max-height: 600px; overflow-y: auto; padding: 24px; border: 2px dashed transparent; transition: all 0.3s;"
                :style="dragging ? 'border-color: #f97316; background: rgba(249, 115, 22, 0.05);' : ''"
                @dragover.prevent
                @drop="
                    const id = $event.dataTransfer.getData('siswaId');
                    if (id) $wire.removeFromRombel(id);
                    dragging = false;
                "
            >
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; padding: 10px 0;">
                    @forelse($unassignedSiswa as $siswa)
                        <div 
                            draggable="true"
                            @dragstart="$event.dataTransfer.setData('siswaId', {{ $siswa->id }}); dragging = true"
                            @dragend="dragging = false"
                            class="group"
                            style="display: flex; align-items: center; gap: 12px; padding: 12px; margin: 0 10px; background: white; border: 1px solid #f3f4f6; border-radius: 12px; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); cursor: move; transition: all 0.2s;"
                            onmouseover="this.style.borderColor='#f97316'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';"
                            onmouseout="this.style.borderColor='#f3f4f6'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)';"
                        >
                            <div style="flex-shrink: 0; width: 36px; height: 36px; border-radius: 9999px; background: #f9fafb; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                                @if($siswa->jenis_kelamin === 'Laki-laki')
                                    <x-heroicon-m-user style="width: 18px; height: 18px;" />
                                @else
                                    <x-heroicon-m-user-circle style="width: 18px; height: 18px;" />
                                @endif
                            </div>
                            
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-weight: 700; color: #1f2937; font-size: 0.8125rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $siswa->nama }}
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px; margin-top: 2px;">
                                    <span style="font-size: 0.6875rem; color: #6b7280; font-weight: 500;">{{ $siswa->nisn ?? '-' }}</span>
                                    <span style="font-size: 0.6875rem; font-weight: 700; color: {{ $siswa->jenis_kelamin === 'Laki-laki' ? '#0ea5e9' : '#ec4899' }};">
                                        {{ $siswa->jenis_kelamin === 'Laki-laki' ? 'L' : 'P' }}
                                    </span>
                                </div>
                            </div>

                            <button 
                                type="button"
                                wire:click="moveToRombel({{ $siswa->id }})"
                                style="flex-shrink: 0; width: 28px; height: 28px; border-radius: 50%; border: none; background: #f0fdf4; color: #22c55e; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;"
                                onmouseover="this.style.background='#22c55e'; this.style.color='#fff';"
                                onmouseout="this.style.background='#f0fdf4'; this.style.color='#22c55e';"
                            >
                                <x-heroicon-m-plus style="width: 16px; height: 16px;" />
                            </button>
                        </div>
                    @empty
                        <div style="grid-column: 1 / -1; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 250px; color: #d1d5db;">
                            <x-heroicon-o-face-smile style="width: 56px; height: 56px; opacity: 0.2; margin-bottom: 12px;" />
                            <p style="font-size: 0.875rem; font-weight: 500;">Tidak ada data yang cocok</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Custom Resilient Pagination --}}
            <div style="padding: 16px; border-top: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; background: #fafafa;">
                <span style="font-size: 0.75rem; color: #6b7280; font-weight: 600;">Hal. {{ $unassignedSiswa->currentPage() }} / {{ $unassignedSiswa->lastPage() }}</span>
                <div style="display: flex; gap: 8px;">
                    <button type="button" wire:click="previousPage('unassignedPage')" @disabled($unassignedSiswa->onFirstPage())
                        style="padding: 6px 12px; border-radius: 8px; border: 1px solid #e5e7eb; background: white; font-size: 0.75rem; font-weight: 700; cursor: {{ $unassignedSiswa->onFirstPage() ? 'not-allowed' : 'pointer' }}; opacity: {{ $unassignedSiswa->onFirstPage() ? '0.5' : '1' }};">Prev</button>
                    <button type="button" wire:click="nextPage('unassignedPage')" @disabled(!$unassignedSiswa->hasMorePages())
                        style="padding: 6px 12px; border-radius: 8px; border: 1px solid #e5e7eb; background: white; font-size: 0.75rem; font-weight: 700; cursor: {{ !$unassignedSiswa->hasMorePages() ? 'not-allowed' : 'pointer' }}; opacity: {{ !$unassignedSiswa->hasMorePages() ? '0.5' : '1' }};">Next</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [draggable="true"] {
            user-select: none;
            -webkit-user-drag: element;
        }
    </style>
</x-filament-panels::page>
