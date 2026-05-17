<x-filament-panels::page>
    <div class="space-y-6" x-data x-init="
        let activeTab = '';
        $nextTick(() => {
            let activeEl = $el.querySelector('button[aria-selected=true]');
            if (activeEl) {
                activeTab = activeEl.innerText.trim();
            }
        });
        
        document.addEventListener('click', (e) => {
            let button = e.target.closest('button[role=tab]');
            if (button) {
                let tabName = button.innerText.trim();
                if (tabName && tabName !== activeTab) {
                    activeTab = tabName;
                    $wire.resetOtherTabFilters(tabName);
                }
            }
        });
    ">
        {{ $this->form }}
    </div>
</x-filament-panels::page>
