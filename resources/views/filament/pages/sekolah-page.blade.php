<x-filament-panels::page>
    <style>
        /* CSS handled in image-hover components */
    </style>

    {{-- Alpine.js: sync Media card height to match Identitas card height --}}
    <div
        x-data="{
            sync() {
                // The first .fi-sc-grid on this page is our top-level 3-column grid
                const grid = document.querySelector('.fi-page-content .fi-sc-grid');
                if (!grid) return;

                const cols = Array.from(grid.children);
                if (cols.length < 2) return;

                // Reset so measurement is natural
                cols.forEach(c => c.style.minHeight = '');

                // Find the tallest column (Identitas)
                const maxH = Math.max(...cols.map(c => c.offsetHeight));

                // Force all columns to that height
                cols.forEach(c => {
                    c.style.minHeight = maxH + 'px';
                    c.style.display   = 'flex';
                    c.style.flexDirection = 'column';
                });

                // Find the section inside the media column and stretch it
                const mediaCol = cols[0];
                if (mediaCol) {
                    const section = mediaCol.querySelector('section');
                    if (section) {
                        section.style.height = '100%';
                        section.style.display = 'flex';
                        section.style.flexDirection = 'column';
                    }
                    // Stretch the inner schema content
                    const content = mediaCol.querySelector('.fi-section-content-ctn');
                    if (content) {
                        content.style.flexGrow = '1';
                        content.style.display = 'flex';
                        content.style.flexDirection = 'column';
                    }
                    const sectionContent = mediaCol.querySelector('.fi-section-content');
                    if (sectionContent) {
                        sectionContent.style.flexGrow = '1';
                        sectionContent.style.display = 'flex';
                        sectionContent.style.flexDirection = 'column';
                        sectionContent.style.height = '100%';
                    }
                    // The inner grid holding the ihw-outer elements
                    const innerGrid = mediaCol.querySelector('.fi-sc-grid');
                    if (innerGrid) {
                        innerGrid.style.flexGrow = '1';
                        innerGrid.style.height = '100%';
                    }
                    
                    // Force the field wrappers inside the inner grid to fill height
                    if (innerGrid) {
                        Array.from(innerGrid.children).forEach(child => {
                            child.style.height = '100%';
                            child.style.display = 'flex';
                            child.style.flexDirection = 'column';
                            
                            // Make the inner view component wrapper fill the field wrapper
                            const viewWrapper = child.querySelector('.fi-fo-field-wrp-content');
                            if (viewWrapper) {
                                viewWrapper.style.flexGrow = '1';
                                viewWrapper.style.display = 'flex';
                                viewWrapper.style.flexDirection = 'column';
                                viewWrapper.style.height = '100%';
                            }
                        });
                    }
                }
            }
        }"
        x-init="
            $nextTick(() => sync());
            new ResizeObserver(() => sync()).observe(document.documentElement);
        "
        x-on:livewire-update.window="$nextTick(() => sync())"
        x-on:school-logo-updated.window="
            const url = $event.detail.logo_url;
            const avatars = document.querySelectorAll('.fi-topbar img.fi-avatar, .fi-user-menu img');
            avatars.forEach(img => img.src = url);
        "
    >
        {{ $this->content }}
    </div>
</x-filament-panels::page>
