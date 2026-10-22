<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="flex flex-wrap justify-end gap-3" style="margin-top: 24px;">
            <x-filament::button type="button" color="gray" icon="heroicon-o-arrow-uturn-left"
                wire:click="resetToEnvironment"
                wire:confirm="Semua override akan dihapus dan aplikasi kembali menggunakan nilai dari .env. Lanjutkan?">
                Reset ke .env
            </x-filament::button>
            <x-filament::button type="submit" icon="heroicon-o-check">
                Simpan Pengaturan
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
