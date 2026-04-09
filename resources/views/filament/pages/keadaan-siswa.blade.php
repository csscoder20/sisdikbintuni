@push('styles')
<style>
/* ================================================================
   KEADAAN SISWA — Dark-mode aware styles via CSS custom properties.
   Filament v5 uses class="dark" on <html> for dark mode.
   ================================================================ */
:root {
    --ks-card-bg:        #ffffff;
    --ks-card-border:    #e5e7eb;
    --ks-card-shadow:    0 1px 4px rgba(0,0,0,.07);
    --ks-text:           #111827;
    --ks-text-muted:     #6b7280;
    --ks-th-bg:          #f3f4f6;
    --ks-th-text:        #374151;
    --ks-td-border:      #e5e7eb;
    --ks-row-hover:      #f9fafb;
    --ks-container-bg:   #f9fafb;
    --ks-select-bg:      #ffffff;
    --ks-select-border:  #d1d5db;
}

html.dark {
    --ks-card-bg:        #1e293b;
    --ks-card-border:    #334155;
    --ks-card-shadow:    0 1px 6px rgba(0,0,0,.3);
    --ks-text:           #f1f5f9;
    --ks-text-muted:     #94a3b8;
    --ks-th-bg:          #0f172a;
    --ks-th-text:        #e2e8f0;
    --ks-td-border:      #334155;
    --ks-row-hover:      #293548;
    --ks-container-bg:   #0f172a;
    --ks-select-bg:      #1e293b;
    --ks-select-border:  #475569;
}

/* ---- Card wrapper ---- */
.ks-card {
    background-color: var(--ks-card-bg);
    border: 1px solid var(--ks-card-border);
    border-radius: .75rem;
    box-shadow: var(--ks-card-shadow);
    overflow: hidden;
}

.ks-card-header {
    padding: .875rem 1.25rem;
    border-bottom: 1px solid var(--ks-card-border);
}

.ks-card-header h2 {
    font-size: 1rem;
    font-weight: 700;
    margin: 0;
}

/* ---- Scrollable table wrapper ---- */
.ks-table-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* ---- Table ---- */
.ks-table {
    width: 100%;
    font-size: .8125rem;
    border-collapse: collapse;
    color: var(--ks-text);
}

.ks-table th,
.ks-table td {
    border: 1px solid var(--ks-td-border);
    padding: .4rem .6rem;
    white-space: nowrap;
}

.ks-table thead th {
    background-color: var(--ks-th-bg);
    color: var(--ks-th-text);
    font-weight: 600;
    text-align: center;
}

.ks-table tbody td {
    color: var(--ks-text);
    background-color: var(--ks-card-bg);
}

.ks-table tbody tr:hover td {
    background-color: var(--ks-row-hover);
}

/* ---- Pagination container (bottom of each card) ---- */
.ks-pagination-container {
    padding: .75rem 1.25rem;
    border-top: 1px solid var(--ks-card-border);
    background-color: var(--ks-container-bg);
    position: relative;
    z-index: 10;
}

.ks-pagination-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: .5rem;
}

.ks-pagination-info {
    font-size: .8125rem;
    color: var(--ks-text-muted);
}

/* ---- Per-page selector ---- */
.ks-per-page {
    display: flex;
    align-items: center;
    gap: .4rem;
    font-size: .8125rem;
    color: var(--ks-text-muted);
}

.ks-per-page select {
    border: 1px solid var(--ks-select-border);
    border-radius: .375rem;
    padding: .2rem .4rem;
    font-size: .8125rem;
    background-color: var(--ks-select-bg);
    color: var(--ks-text);
    cursor: pointer;
    outline: none;
}

.ks-per-page select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59,130,246,.25);
}

/* ---- Pagination Buttons ---- */
.ks-page-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 10px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.8rem;
    cursor: pointer;
    background: #ffffff;
    color: #374151;
    transition: all 0.2s;
    text-decoration: none;
}
.ks-page-btn:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
    color: #1d4ed8;
}
.ks-page-btn-active {
    background: #3b82f6;
    color: #ffffff !important;
    border-color: #3b82f6;
    font-weight: 600;
    cursor: default;
}
.ks-page-btn-disabled {
    color: #9ca3af !important;
    cursor: not-allowed;
    background: #f9fafb;
    border-color: #d1d5db;
}
</style>
@endpush

<div>
    <div style="padding:1.25rem;">

        <div class="ks-card-header" style="background:linear-gradient(135deg,#dbeafe33,#93c5fd11);">
            <h2 style="color:#1d4ed8;">📘 Jumlah Siswa Berdasarkan Kelas / Rombel</h2>
        </div>

        <div class="ks-table-wrapper">
            <table class="ks-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:3%;">NO</th>
                        <th rowspan="2" style="text-align:left;min-width:140px;">KELAS / ROMBEL</th>
                        <th colspan="3">AWAL BULAN</th>
                        <th colspan="3">MUTASI MASUK</th>
                        <th colspan="3">AKHIR BULAN</th>
                        <th colspan="3">PINDAH SEKOLAH</th>
                        <th colspan="3">MENGULANG</th>
                    </tr>
                    <tr>
                        <th>L</th><th>P</th><th>JML</th>
                        <th>L</th><th>P</th><th>JML</th>
                        <th>L</th><th>P</th><th>JML</th>
                        <th>L</th><th>P</th><th>JML</th>
                        <th>L</th><th>P</th><th>JML</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswaPerKelas as $item)
                        <tr wire:key="kelas-{{ $item['rombel_id'] ?? $loop->index }}">
                            <td style="text-align:center;">{{ $loop->iteration }}</td>
                            <td>{{ $item['nama_rombel'] ?? '-' }}</td>
                            <td style="text-align:center;">{{ $item['awal_bulan_l'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['awal_bulan_p'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['awal_bulan_jml'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['mutasi_l'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['mutasi_p'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['mutasi_jml'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['akhir_bulan_l'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['akhir_bulan_p'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['akhir_bulan_jml'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['pindah_sekolah_l'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['pindah_sekolah_p'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['pindah_sekolah_jml'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['mengulang_l'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['mengulang_p'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['mengulang_jml'] ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="17" style="text-align:center;padding:2rem;color:var(--ks-text-muted);">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="ks-pagination-container">
            <div class="ks-pagination-row">
                <div class="ks-per-page">
                    <span>Tampilkan</span>
                    <select wire:model.live="perPage">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span>data</span>
                </div>
                <div class="ks-pagination-info">
                    @if ($siswaPerKelas->total() > 0)
                        {{ $siswaPerKelas->firstItem() }}–{{ $siswaPerKelas->lastItem() }}
                        dari {{ $siswaPerKelas->total() }} data
                    @endif
                </div>
            </div>
            @if ($siswaPerKelas->hasPages())
                <div style="margin-top:.5rem;">
                    {{ $siswaPerKelas->onEachSide(1)->links('pagination::ks-pagination') }}
                </div>
            @endif
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- Tabel 2: Siswa Menurut Umur --}}
    {{-- ================================================================ --}}
    <div class="ks-card" style="margin-bottom:1.25rem;">
        <div class="ks-card-header" style="background:linear-gradient(135deg,#dcfce733,#86efac11);">
            <h2 style="color:#15803d;">📗 Jumlah Siswa Menurut Umur</h2>
        </div>

        <div class="ks-table-wrapper">
            <table class="ks-table">
                <thead>
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2" style="text-align:left;min-width:140px;">KELAS / ROMBEL</th>
                        @for ($age = 13; $age <= 23; $age++)
                            <th colspan="3">{{ $age }} Thn</th>
                        @endfor
                    </tr>
                    <tr>
                        @for ($age = 13; $age <= 23; $age++)
                            <th>L</th><th>P</th><th>JML</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswaPerUmur as $item)
                        <tr wire:key="umur-{{ $item['rombel_id'] ?? $loop->index }}">
                            <td style="text-align:center;">{{ $loop->iteration }}</td>
                            <td>{{ $item['nama_rombel'] ?? '-' }}</td>
                            @for ($age = 13; $age <= 23; $age++)
                                @php $px = 'umur_' . $age; @endphp
                                <td style="text-align:center;">{{ $item[$px . '_l'] ?? 0 }}</td>
                                <td style="text-align:center;">{{ $item[$px . '_p'] ?? 0 }}</td>
                                <td style="text-align:center;font-weight:600;">{{ $item[$px . '_jml'] ?? 0 }}</td>
                            @endfor
                        </tr>
                    @empty
                        <tr>
                            <td colspan="35" style="text-align:center;padding:2rem;color:var(--ks-text-muted);">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="ks-pagination-container">
            <div class="ks-pagination-row" style="justify-content:flex-end;">
                <div class="ks-pagination-info">
                    @if ($siswaPerUmur->total() > 0)
                        {{ $siswaPerUmur->firstItem() }}–{{ $siswaPerUmur->lastItem() }}
                        dari {{ $siswaPerUmur->total() }} data
                    @endif
                </div>
            </div>
            @if ($siswaPerUmur->hasPages())
                <div style="margin-top:.5rem;">
                    {{ $siswaPerUmur->onEachSide(1)->links('pagination::ks-pagination') }}
                </div>
            @endif
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- Tabel 3: Siswa Menurut Agama --}}
    {{-- ================================================================ --}}
    <div class="ks-card" style="margin-bottom:1.25rem;">
        <div class="ks-card-header" style="background:linear-gradient(135deg,#e9d5ff33,#d8b4fe11);">
            <h2 style="color:#7c3aed;">📙 Jumlah Siswa Menurut Agama</h2>
        </div>

        <div class="ks-table-wrapper">
            <table class="ks-table">
                <thead>
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2" style="text-align:left;min-width:140px;">KELAS / ROMBEL</th>
                        <th colspan="3">ISLAM</th>
                        <th colspan="3">KRISTEN PROT.</th>
                        <th colspan="3">KATOLIK</th>
                        <th colspan="3">HINDU</th>
                        <th colspan="3">BUDHA</th>
                        <th colspan="3">KONGHUCU</th>
                    </tr>
                    <tr>
                        @for ($i = 0; $i < 6; $i++)
                            <th>L</th><th>P</th><th>JML</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswaPerAgama as $item)
                        <tr wire:key="agama-{{ $item['rombel_id'] ?? $loop->index }}">
                            <td style="text-align:center;">{{ $loop->iteration }}</td>
                            <td>{{ $item['nama_rombel'] ?? '-' }}</td>
                            <td style="text-align:center;">{{ $item['islam_l'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['islam_p'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['islam_jml'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['kristen_protestan_l'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['kristen_protestan_p'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['kristen_protestan_jml'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['katolik_l'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['katolik_p'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['katolik_jml'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['hindu_l'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['hindu_p'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['hindu_jml'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['budha_l'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['budha_p'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['budha_jml'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['konghucu_l'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['konghucu_p'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['konghucu_jml'] ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="20" style="text-align:center;padding:2rem;color:var(--ks-text-muted);">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="ks-pagination-container">
            <div class="ks-pagination-row" style="justify-content:flex-end;">
                <div class="ks-pagination-info">
                    @if ($siswaPerAgama->total() > 0)
                        {{ $siswaPerAgama->firstItem() }}–{{ $siswaPerAgama->lastItem() }}
                        dari {{ $siswaPerAgama->total() }} data
                    @endif
                </div>
            </div>
            @if ($siswaPerAgama->hasPages())
                <div style="margin-top:.5rem;">
                    {{ $siswaPerAgama->onEachSide(1)->links('pagination::ks-pagination') }}
                </div>
            @endif
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- Tabel 4: Siswa Menurut Daerah Asal --}}
    {{-- ================================================================ --}}
    <div class="ks-card" style="margin-bottom:1.25rem;">
        <div class="ks-card-header" style="background:linear-gradient(135deg,#fed7aa33,#fdba7411);">
            <h2 style="color:#b45309;">📒 Jumlah Siswa Menurut Daerah Asal</h2>
        </div>

        <div class="ks-table-wrapper">
            <table class="ks-table">
                <thead>
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2" style="text-align:left;min-width:140px;">KELAS / ROMBEL</th>
                        <th colspan="3">PAPUA</th>
                        <th colspan="3">NON-PAPUA</th>
                    </tr>
                    <tr>
                        <th>L</th><th>P</th><th>JML</th>
                        <th>L</th><th>P</th><th>JML</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswaPDaerah as $item)
                        <tr wire:key="daerah-{{ $item['rombel_id'] ?? $loop->index }}">
                            <td style="text-align:center;">{{ $loop->iteration }}</td>
                            <td>{{ $item['nama_rombel'] ?? '-' }}</td>
                            <td style="text-align:center;">{{ $item['papua_l'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['papua_p'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['papua_jml'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['non_papua_l'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['non_papua_p'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['non_papua_jml'] ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:2rem;color:var(--ks-text-muted);">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="ks-pagination-container">
            <div class="ks-pagination-row" style="justify-content:flex-end;">
                <div class="ks-pagination-info">
                    @if ($siswaPDaerah->total() > 0)
                        {{ $siswaPDaerah->firstItem() }}–{{ $siswaPDaerah->lastItem() }}
                        dari {{ $siswaPDaerah->total() }} data
                    @endif
                </div>
            </div>
            @if ($siswaPDaerah->hasPages())
                <div style="margin-top:.5rem;">
                    {{ $siswaPDaerah->onEachSide(1)->links('pagination::ks-pagination') }}
                </div>
            @endif
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- Tabel 5: Siswa Disabilitas --}}
    {{-- ================================================================ --}}
    <div class="ks-card" style="margin-bottom:1.25rem;">
        <div class="ks-card-header" style="background:linear-gradient(135deg,#fecaca33,#fca5a511);">
            <h2 style="color:#b91c1c;">📕 Jumlah Siswa Disabilitas</h2>
        </div>

        <div class="ks-table-wrapper">
            <table class="ks-table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th style="text-align:left;min-width:180px;">JENIS DISABILITAS</th>
                        <th>LAKI-LAKI</th>
                        <th>PEREMPUAN</th>
                        <th>JUMLAH</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswaDisabilitas as $item)
                        <tr wire:key="disabilitas-{{ $loop->index }}">
                            <td style="text-align:center;">{{ $loop->iteration }}</td>
                            <td>{{ $item['jenis_disabilitas'] ?? '-' }}</td>
                            <td style="text-align:center;">{{ $item['laki_laki'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['perempuan'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['total'] ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:2rem;color:var(--ks-text-muted);">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="ks-pagination-container">
            <div class="ks-pagination-row" style="justify-content:flex-end;">
                <div class="ks-pagination-info">
                    @if ($siswaDisabilitas->total() > 0)
                        {{ $siswaDisabilitas->firstItem() }}–{{ $siswaDisabilitas->lastItem() }}
                        dari {{ $siswaDisabilitas->total() }} data
                    @endif
                </div>
            </div>
            @if ($siswaDisabilitas->hasPages())
                <div style="margin-top:.5rem;">
                    {{ $siswaDisabilitas->onEachSide(1)->links('pagination::ks-pagination') }}
                </div>
            @endif
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- Tabel 6: Siswa Penerima Beasiswa --}}
    {{-- ================================================================ --}}
    <div class="ks-card">
        <div class="ks-card-header" style="background:linear-gradient(135deg,#e0e7ff33,#c7d2fe11);">
            <h2 style="color:#4338ca;">📓 Jumlah Siswa Penerima Beasiswa</h2>
        </div>

        <div class="ks-table-wrapper">
            <table class="ks-table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th style="text-align:left;min-width:200px;">JENIS BEASISWA</th>
                        <th>LAKI-LAKI</th>
                        <th>PEREMPUAN</th>
                        <th>JUMLAH</th>
                        <th style="text-align:left;min-width:160px;">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswaBeasiswa as $item)
                        <tr wire:key="beasiswa-{{ $loop->index }}">
                            <td style="text-align:center;">{{ $loop->iteration }}</td>
                            <td>{{ $item['jenis_beasiswa'] ?? '-' }}</td>
                            <td style="text-align:center;">{{ $item['penerima_l'] ?? 0 }}</td>
                            <td style="text-align:center;">{{ $item['penerima_p'] ?? 0 }}</td>
                            <td style="text-align:center;font-weight:600;">{{ $item['penerima_jml'] ?? 0 }}</td>
                            <td>{{ $item['keterangan'] ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:2rem;color:var(--ks-text-muted);">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="ks-pagination-container">
            <div class="ks-pagination-row" style="justify-content:flex-end;">
                <div class="ks-pagination-info">
                    @if ($siswaBeasiswa->total() > 0)
                        {{ $siswaBeasiswa->firstItem() }}–{{ $siswaBeasiswa->lastItem() }}
                        dari {{ $siswaBeasiswa->total() }} data
                    @endif
                </div>
            </div>
            @if ($siswaBeasiswa->hasPages())
                <div style="margin-top:.5rem;">
                    {{ $siswaBeasiswa->onEachSide(1)->links('pagination::ks-pagination') }}
                </div>
            @endif
        </div>
    </div>

    </div>

</div>{{-- end padding wrapper --}}
</div>{{-- end single root div for livewire --}}
