<div class="ipw-container">
    <!-- Image Display Card -->
    <div class="ipw-card">
        <div class="ipw-gradient-overlay"></div>
        
        <img 
            src="{{ $url }}" 
            alt="{{ $title }}" 
            class="ipw-image"
        >
        
        <div class="ipw-card-footer">
            <p class="ipw-footer-text">
                {{ $title }} saat ini
            </p>
        </div>
    </div>
    
    <!-- Action Section -->
    <div class="ipw-actions">
        <button 
            type="button"
            x-on:click="$wire.mountAction('{{ $field === 'logo' ? 'updateLogo' : 'updateFoto' }}')"
            class="ipw-button"
        >
            <svg class="ipw-button-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15a2.25 2.25 0 0 0 2.25-2.25V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
            </svg>
            <span>Ganti / Unggah {{ $title }} Baru</span>
        </button>
        
        <p class="ipw-hint">
            Format: JPG, PNG atau WEBP (Maks. 2MB)
        </p>
    </div>
</div>

<style>
    .ipw-container {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .ipw-card {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        border: 4px solid white;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        background-color: #f9fafb;
    }

    .dark .ipw-card {
        border-color: #1f2937;
        background-color: #111827;
    }

    .ipw-gradient-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top right, rgba(var(--primary-500), 0.05), transparent);
        pointer-events: none;
    }

    .ipw-image {
        width: 100%;
        height: auto;
        max-height: 70vh;
        object-fit: contain;
        display: block;
        margin: 0 auto;
    }

    .ipw-card-footer {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 0.75rem;
        background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
    }

    .ipw-footer-text {
        color: white;
        font-size: 0.75rem;
        font-weight: 500;
        text-align: center;
        letter-spacing: 0.05em;
    }

    .ipw-actions {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 0.5rem;
    }

    .ipw-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 0.875rem 2rem;
        background-color: rgb(var(--primary-600));
        color: white;
        border-radius: 0.75rem;
        font-weight: 700;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 6px -1px rgba(var(--primary-600), 0.3);
    }

    .ipw-button:hover {
        background-color: rgb(var(--primary-500));
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(var(--primary-600), 0.4);
    }

    .ipw-button:active {
        transform: translateY(0);
    }

    .ipw-button-icon {
        width: 1.25rem;
        height: 1.25rem;
    }

    .ipw-hint {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .dark .ipw-hint {
        color: #9ca3af;
    }
</style>
