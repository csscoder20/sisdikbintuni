@php
    $sekolah = isset($livewire) ? $livewire->getSekolah() : null;
    $imagePath = $sekolah ? $sekolah->$field : null;
    
    $url = $imagePath 
        ? Storage::disk('public')->url($imagePath) 
        : ($field === 'logo' 
            ? 'https://placehold.co/400x400?text=Logo+Sekolah' 
            : 'https://placehold.co/800x600?text=Foto+Sekolah');
            
    $label = $imagePath ? 'Upload atau<br>Ganti Gambar' : 'Upload atau<br>Unggah Gambar';
    $wireAction = $field === 'logo' ? 'updateLogo' : 'updateFoto';
    $type = $field === 'logo' ? 'Logo Sekolah' : 'Foto Sekolah';
    $isLogo = $field === 'logo';
@endphp

<div class="ihw-outer {{ $isLogo ? 'ihw-logo' : 'ihw-foto' }}">
    <div class="ihw-inner"
         x-on:click="$wire.mountAction('{{ $wireAction }}')"
         wire:key="image-{{ $field }}-{{ $imagePath }}">
        <img src="{{ $url }}" alt="{{ $type }}" class="ihw-img">
        
        <div class="ihw-overlay">
            <div class="ihw-overlay-content">
                <div class="ihw-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15a2.25 2.25 0 0 0 2.25-2.25V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                    </svg>
                </div>
                <span class="ihw-label">{!! $label !!}</span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Outer wrapper — must have min-height:0 and min-width:0 so it can shrink */
    .ihw-outer {
        display: flex;
        flex-direction: column;
        min-height: 0;
        min-width: 0;
        overflow: hidden;
        height: 100%;
        width: 100%;
    }

    .ihw-foto {
        flex-grow: 1;
        min-height: 0;
        min-width: 0;
        overflow: hidden;
    }

    .ihw-logo {
        flex-grow: 1;
        min-height: 0;
        min-width: 0;
        justify-content: center;
        align-items: center;
    }

    .ihw-inner {
        position: relative;
        cursor: pointer;
        overflow: hidden;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        border: 4px solid white;
        background-color: #f3f4f6;
        width: 100%;
        height: 100%;
        max-height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ihw-logo .ihw-inner {
        width: 80%;
        max-width: 100%;
        max-height: 100%;
        aspect-ratio: 1 / 1;
        height: auto;
    }

    .ihw-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .ihw-inner:hover .ihw-img {
        transform: scale(1.1);
    }

    .ihw-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0.4), transparent);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
        backdrop-filter: blur(2px);
    }

    .ihw-inner:hover .ihw-overlay {
        opacity: 1;
    }

    .ihw-overlay-content {
        transform: translateY(1rem);
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .ihw-inner:hover .ihw-overlay-content {
        transform: translateY(0);
    }

    .ihw-icon {
        background-color: rgba(255, 255, 255, 0.2);
        padding: 0.6rem;
        border-radius: 9999px;
        margin-bottom: 0.5rem;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ihw-icon svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .ihw-label {
        color: white;
        font-weight: 700;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        padding: 0.35rem 0.75rem;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 0.5rem;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        text-align: center;
        line-height: 1.3;
    }
</style>
