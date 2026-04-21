<x-filament-panels::page>
    <style>
        .fi-page-content .fi-sc-grid {
            align-items: stretch !important;
        }
        .fi-page-content .fi-sc-grid > div {
            display: flex !important;
            flex-direction: column !important;
        }
        .fi-page-content .fi-sc-grid > div > .fi-sc-section {
            flex-grow: 1 !important;
            height: 100% !important;
        }
        .fi-page-content .fi-sc-grid > div > .fi-sc-section > section {
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
        }
        .fi-page-content .fi-sc-grid > div > .fi-sc-section > section > .fi-section-content-ctn {
            flex-grow: 1 !important;
            display: flex !important;
            flex-direction: column !important;
        }
    </style>
    {{ $this->content }}
</x-filament-panels::page>
