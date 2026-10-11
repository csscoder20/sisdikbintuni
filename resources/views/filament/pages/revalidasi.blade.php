<x-filament-panels::page>
    <style>
        .revalidasi-page {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            font-size: .875rem;
            line-height: 1.25rem;
        }

        .revalidasi-page .revalidasi-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .revalidasi-page .revalidasi-sections {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .75rem 2rem;
        }

        .revalidasi-page .revalidasi-section-option {
            display: flex;
            align-items: center;
            gap: .65rem;
            min-height: 2.5rem;
            padding: .65rem .75rem;
            border: 1px solid rgb(226 232 240);
            border-radius: .5rem;
        }

        .revalidasi-page .revalidasi-section-option input {
            flex: 0 0 auto;
        }

        .revalidasi-page .revalidasi-section-option span {
            font-size: .875rem;
            line-height: 1.25rem;
        }

        @media (max-width: 768px) {

            .revalidasi-page .revalidasi-fields,
            .revalidasi-page .revalidasi-sections {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="revalidasi-page">
        <x-filament::section>
            <x-slot name="heading">Pilih Laporan</x-slot>
            <x-slot name="description">Menu ini membuka kembali flag validasi agar operator dapat memperbarui laporan
                periode terpilih.</x-slot>

            <div class="revalidasi-fields">
                {{ $this->form }}

            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Data yang Dibuka</x-slot>
            <div class="revalidasi-sections">
                @foreach ($this->sectionLabels() as $key => $label)
                    <label class="revalidasi-section-option">
                        <input type="checkbox" wire:model="sections" value="{{ $key }}"
                            {{ $this->isReadOnly() ? 'disabled' : '' }}>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </x-filament::section>

        @if ($laporan = $this->getSelectedLaporan())
            <x-filament::section>
                <x-slot name="heading">Status Laporan</x-slot>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Periode: <strong>{{ $laporan->bulan }}/{{ $laporan->tahun }}</strong>
                    · Status: <strong>{{ strtoupper($laporan->status ?? 'draft') }}</strong>
                    @if ($laporan->tanggal_submit)
                        · Divalidasi: {{ $laporan->tanggal_submit->format('d/m/Y H:i') }}
                    @endif
                </p>
            </x-filament::section>
        @endif

        <div class="flex justify-end">
            <x-filament::button wire:click="openValidation"
                wire:confirm="Validasi untuk bagian yang dipilih akan dibuka kembali. Lanjutkan?" color="warning"
                icon="heroicon-o-arrow-path" :disabled="$this->isReadOnly()">
                Hapus Validasi Terpilih
            </x-filament::button>
        </div>

        @if ($this->isReadOnly())
            <x-filament::section>
                <p class="text-sm text-gray-600 dark:text-gray-400">Role Pengawas hanya dapat melihat status laporan.
                    Perubahan validasi dilakukan oleh Admin Dinas atau Super Admin.</p>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
