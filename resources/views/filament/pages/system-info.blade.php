<x-filament-panels::page>
    <style>
        .system-info-page {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.5rem;
        }

        .system-info-page>* {
            min-width: 0;
        }

        .system-info-page .system-info-list {
            display: grid;
            grid-template-columns: 1fr;
        }

        .system-info-page .system-info-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: .85rem 0;
            border-bottom: 1px solid rgb(229 231 235);
        }

        .system-info-page .system-info-label {
            color: rgb(75 85 99);
            font-size: .875rem;
        }

        .system-info-page .system-info-value {
            max-width: 65%;
            overflow-wrap: anywhere;
            text-align: right;
        }

        .dark .system-info-page .system-info-item {
            border-color: rgb(255 255 255 / .1);
        }

        .dark .system-info-page .system-info-label {
            color: rgb(209 213 219);
        }

        @media (max-width: 1024px) {
            .system-info-page {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .system-info-page {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="system-info-page">
        <x-filament::section>
            <x-slot name="heading">Aplikasi</x-slot>
            <x-slot name="description">Identitas dan konfigurasi runtime aplikasi saat ini.</x-slot>
            <div class="system-info-list">
                @foreach ($this->getApplicationInfo() as $item)
                    <div class="system-info-item">
                        <span class="system-info-label">{{ $item['label'] }}</span>
                        <span class="system-info-value"><x-filament::badge
                                :color="$item['status'] ?? 'gray'">{{ $item['value'] }}</x-filament::badge></span>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">PHP & Server</x-slot>
            <x-slot name="description">Detail proses PHP yang saat ini melayani panel administrasi.</x-slot>
            <div class="system-info-list">
                @foreach ($this->getRuntimeInfo() as $item)
                    <div class="system-info-item">
                        <span class="system-info-label">{{ $item['label'] }}</span>
                        <span class="system-info-value"><x-filament::badge
                                color="gray">{{ $item['value'] }}</x-filament::badge></span>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Filament</x-slot>
            <x-slot name="description">Versi Filament dan panel administrasi yang sedang aktif.</x-slot>
            <div class="system-info-list">
                @foreach ($this->getFilamentInfo() as $item)
                    <div class="system-info-item">
                        <span class="system-info-label">{{ $item['label'] }}</span>
                        <span class="system-info-value"><x-filament::badge
                                color="gray">{{ $item['value'] }}</x-filament::badge></span>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Database</x-slot>
            <x-slot name="description">Status dan target koneksi database aplikasi.</x-slot>
            <div class="system-info-list">
                @foreach ($this->getDatabaseInfo() as $item)
                    <div class="system-info-item">
                        <span class="system-info-label">{{ $item['label'] }}</span>
                        <span class="system-info-value"><x-filament::badge
                                :color="$item['status'] ?? 'gray'">{{ $item['value'] }}</x-filament::badge></span>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Ekstensi PHP</x-slot>
            <x-slot name="description">Ekstensi penting untuk koneksi database dan fitur aplikasi.</x-slot>
            <div class="system-info-list">
                @foreach ($this->getExtensions() as $extension => $active)
                    <div class="system-info-item">
                        <span class="system-info-label">{{ $extension }}</span>
                        <span class="system-info-value">
                            <x-filament::badge
                                :color="$active ? 'success' : 'danger'">{{ $active ? 'Aktif' : 'Tidak aktif' }}</x-filament::badge>
                        </span>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
