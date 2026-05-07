@php
    $sekolah = isset($livewire) ? $livewire->getSekolah() : null;
    $imagePath = $sekolah ? $sekolah->$field : null;
    
    $url = $imagePath 
        ? Storage::disk('public')->url($imagePath) 
        : ($field === 'logo' 
            ? 'https://placehold.co/400x400?text=Logo+Sekolah' 
            : 'https://placehold.co/800x600?text=Foto+Sekolah');
            
    $label = $imagePath ? 'Lihat / Ganti<br>Gambar' : 'Upload / Unggah<br>Gambar';
    $wireAction = $field === 'logo' ? 'viewLogo' : 'viewFoto';
    $type = $field === 'logo' ? 'Logo Sekolah' : 'Foto Sekolah';
    $isLogo = $field === 'logo';
@endphp

<div class="ihw-outer {{ $isLogo ? 'ihw-logo-wrapper' : 'ihw-foto-wrapper' }}">
    <div class="ihw-inner"
         x-on:click="$wire.mountAction('{{ $wireAction }}')"
         wire:key="image-{{ $field }}-{{ $imagePath }}">
        <img src="{{ $url }}" alt="{{ $type }}" class="ihw-img">
        
        <div class="ihw-overlay">
            <div class="ihw-overlay-content">
                <div class="ihw-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>
                <span class="ihw-label">{!! $label !!}</span>
            </div>
        </div>
    </div>
</div>

<style>
    .ihw-outer {
        display: flex;
        flex-direction: column;
        min-height: 0;
        min-width: 0;
        overflow: hidden;
        height: 100%;
        width: 100%;
    }

    .ihw-foto-wrapper {
        flex-grow: 1;
        min-height: 250px;
    }

    .ihw-logo-wrapper {
        flex-grow: 1;
        justify-content: center;
        align-items: center;
        min-height: 200px;
    }

    .ihw-inner {
        position: relative;
        cursor: pointer;
        overflow: hidden;
        border-radius: 1.25rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        border: 6px solid white;
        background-color: #f8fafc;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .ihw-logo-wrapper .ihw-inner {
        width: 180px;
        height: 180px;
        margin: auto;
    }
    
    @media (min-width: 1024px) {
        .ihw-logo-wrapper .ihw-inner {
            width: 85%;
            height: auto;
            aspect-ratio: 1 / 1;
        }
    }

    .ihw-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .ihw-inner:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: rgba(var(--primary-500), 0.1);
    }

    .ihw-inner:hover .ihw-img {
        transform: scale(1.15);
    }

    .ihw-overlay {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.7) 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.4s ease;
        backdrop-filter: blur(4px);
    }

    .ihw-inner:hover .ihw-overlay {
        opacity: 1;
    }

    .ihw-overlay-content {
        transform: translateY(1.5rem);
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .ihw-inner:hover .ihw-overlay-content {
        transform: translateY(0);
    }

    .ihw-icon {
        background-color: rgba(255, 255, 255, 0.25);
        padding: 0.75rem;
        border-radius: 1rem;
        margin-bottom: 0.75rem;
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        color: white;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    .ihw-icon svg {
        width: 1.5rem;
        height: 1.5rem;
    }

    .ihw-label {
        color: white;
        font-weight: normal;
        font-size: 0.7rem;
        padding: 0.5rem 1rem;
        background-color: rgba(0, 0, 0, 0.3);
        border-radius: 0.75rem;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        text-align: center;
        line-height: 1.4;
    }
</style>
