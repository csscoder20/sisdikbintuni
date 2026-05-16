<div class="icp-container">
    <div class="icp-card">
        <div class="icp-overlay"></div>
        <img src="{{ $url }}" alt="{{ $title }}" class="icp-image">
    </div>
    <div class="icp-label">
        {{ $title }} Saat Ini
    </div>
</div>

<style>
    .icp-container {
        margin-bottom: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .icp-card {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        border: 4px solid white;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        background-color: #f9fafb;
        max-width: 250px;
    }

    .dark .icp-card {
        border-color: #1f2937;
        background-color: #111827;
    }

    .icp-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top right, rgba(var(--primary-500), 0.05), transparent);
        pointer-events: none;
    }

    .icp-image {
        width: 100%;
        height: auto;
        max-height: 180px;
        object-fit: contain;
        display: block;
    }

    .icp-label {
        margin-top: 0.75rem;
        font-size: 0.7rem;
        font-weight: 800;
        color: #9ca3af;
        letter-spacing: 0.1em;
        text-align: center;
    }
</style>
