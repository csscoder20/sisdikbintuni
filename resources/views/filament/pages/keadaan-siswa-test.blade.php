<x-filament-panels::page>
    <div class="bg-white p-6 rounded-lg shadow">
        <p>Test: Halaman berhasil dimuat</p>
        <p>Data siswa kelas rombel: <strong>{{ count($this->getTabelSiswaPerKelas()) }} records</strong></p>

        @if ($this->getTabelSiswaPerKelas()->count() > 0)
            <table class="w-full mt-4 border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">NO</th>
                        <th class="border px-4 py-2">KELAS</th>
                        <th class="border px-4 py-2">AWAL BULAN L</th>
                        <th class="border px-4 py-2">AWAL BULAN P</th>
                        <th class="border px-4 py-2">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->getTabelSiswaPerKelas() as $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2">{{ $item->nama_rombel }}</td>
                            <td class="border px-4 py-2">{{ $item->awal_bulan_l }}</td>
                            <td class="border px-4 py-2">{{ $item->awal_bulan_p }}</td>
                            <td class="border px-4 py-2"><strong>{{ $item->awal_bulan_jml }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-red-600">Tidak ada data</p>
        @endif
    </div>
</x-filament-panels::page>
