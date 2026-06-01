<div style="max-height: 250px; overflow-y: auto; display: flex; flex-direction: column; gap: 2px;">
    <div wire:click="selectSekolah(null)" @click="{{ $closeExpression }}"
        style="padding: 8px 12px; font-size: 0.875rem; cursor: pointer; border-radius: 6px; color: #6b7280; transition: background 0.2s;"
        onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
        -- Kosongkan Pilihan --
    </div>

    @foreach ($sekolahs as $sekolah)
        <div wire:click="selectSekolah({{ $sekolah->id }})" @click="{{ $closeExpression }}"
            style="padding: 8px 12px; font-size: 0.875rem; cursor: pointer; border-radius: 6px; color: #111827; transition: all 0.2s; {{ $selectedSekolahId == $sekolah->id ? 'background-color: #ffedd5; color: #9f9fa9; font-weight: 600;' : '' }}"
            onmouseover="if(this.style.backgroundColor != 'rgb(255, 237, 213)') this.style.backgroundColor='#f3f4f6'"
            onmouseout="if(this.style.backgroundColor != 'rgb(255, 237, 213)') this.style.backgroundColor='transparent'">
            {{ $sekolah->nama }}
        </div>
    @endforeach

    @if ($sekolahs->isEmpty())
        <div style="padding: 12px; font-size: 0.875rem; color: #9ca3af; text-align: center;">
            Sekolah tidak ditemukan...
        </div>
    @endif
</div>
