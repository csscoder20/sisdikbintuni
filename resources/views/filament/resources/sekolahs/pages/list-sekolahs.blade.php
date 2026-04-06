<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
        <!-- Sidebar dengan daftar sekolah -->
        @if (auth()->user()->role !== 'operator')
            <div class="md:col-span-1">
                <div class="rounded-lg border border-gray-300 bg-white p-4">
                    <h3 class="mb-4 font-semibold text-gray-900">Daftar Sekolah</h3>
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @forelse($sekolahs as $sekolah)
                            <button type="button" wire:click="selectSekolah({{ $sekolah->id }})"
                                @class([
                                    'w-full truncate rounded px-3 py-2 text-left text-sm transition',
                                    'bg-blue-100 text-blue-900' => $selectedSekolah?->id === $sekolah->id,
                                    'hover:bg-gray-100' => $selectedSekolah?->id !== $sekolah->id,
                                ]) title="{{ $sekolah->nama_sekolah }}">
                                {{ $sekolah->nama_sekolah }}
                            </button>
                        @empty
                            <p class="text-sm text-gray-500">Tidak ada sekolah</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif

        <!-- Form sekolah -->
        <div @class([
            'md:col-span-3' => auth()->user()->role !== 'operator',
            'md:col-span-4' => auth()->user()->role === 'operator',
        ])>
            @if ($selectedSekolah)
                <div class="rounded-lg border border-gray-300 bg-white p-6">
                    <h2 class="mb-6 text-2xl font-bold text-gray-900">
                        {{ $selectedSekolah->nama_sekolah ?? 'Form Sekolah' }}
                    </h2>

                    <form wire:submit="save" class="space-y-6">
                        <!-- Grid untuk form fields -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <!-- Nama Sekolah -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sekolah <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="selectedSekolah.nama_sekolah"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none"
                                    required />
                            </div>

                            <!-- NPSN -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NPSN</label>
                                <input type="text" wire:model.live="selectedSekolah.npsn"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>

                            <!-- NSS -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NSS</label>
                                <input type="text" wire:model.live="selectedSekolah.nss"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>

                            <!-- NPWP -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NPWP</label>
                                <input type="text" wire:model.live="selectedSekolah.npwp"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>

                            <!-- Email Sekolah -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Sekolah</label>
                                <input type="email" wire:model.live="selectedSekolah.email_sekolah"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>

                            <!-- Tahun Berdiri -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Berdiri</label>
                                <input type="number" wire:model.live="selectedSekolah.tahun_berdiri"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <textarea wire:model.live="selectedSekolah.alamat" rows="3"
                                class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none"></textarea>
                        </div>

                        <!-- Lokasi -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Desa</label>
                                <input type="text" wire:model.live="selectedSekolah.desa"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                                <input type="text" wire:model.live="selectedSekolah.kecamatan"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten</label>
                                <input type="text" wire:model.live="selectedSekolah.kabupaten"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                <input type="text" wire:model.live="selectedSekolah.provinsi"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>
                        </div>

                        <!-- SK Pendirian -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor SK Pendirian</label>
                                <input type="text" wire:model.live="selectedSekolah.nomor_sk_pendirian"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal SK Pendirian</label>
                                <input type="date" wire:model.live="selectedSekolah.tgl_sk_pendirian"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Sekolah</label>
                                <input type="text" wire:model.live="selectedSekolah.status_sekolah"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Akreditasi Sekolah</label>
                                <input type="text" wire:model.live="selectedSekolah.akreditasi_sekolah"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gedung Sekolah</label>
                                <input type="text" wire:model.live="selectedSekolah.gedung_sekolah"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>
                        </div>

                        <!-- Tanah Sekolah -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Tanah Sekolah</label>
                                <input type="text" wire:model.live="selectedSekolah.status_tanah_sekolah"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Luas Tanah Sekolah
                                    (m²)</label>
                                <input type="number" wire:model.live="selectedSekolah.luas_tanah_sekolah"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>
                        </div>

                        <!-- Informasi Yayasan -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penyelenggara
                                    Yayasan</label>
                                <input type="text" wire:model.live="selectedSekolah.nama_penyelenggara_yayasan"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">SK Pendirian Yayasan</label>
                                <input type="text" wire:model.live="selectedSekolah.sk_pendirian_yayasan"
                                    class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none" />
                            </div>
                        </div>

                        <!-- Alamat Yayasan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Penyelenggara
                                Yayasan</label>
                            <textarea wire:model.live="selectedSekolah.alamat_penyelenggara_yayasan" rows="3"
                                class="w-full rounded border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none"></textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3 justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('filament.admin.resources.sekolahs.index') }}"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="rounded-lg border border-gray-300 bg-white p-6 text-center">
                    <p class="text-gray-500">Pilih atau buat sekolah untuk menampilkan form</p>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
