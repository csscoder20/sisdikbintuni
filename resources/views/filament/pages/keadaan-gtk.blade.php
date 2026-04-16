@php
    $tenantName = \Filament\Facades\Filament::getTenant()?->nama ?? 'Sekolah';
@endphp

@push('styles')
    <style>
        /* ================================================================
                                           KEADAAN GTK — High Precision Fixed Layout
                                           ================================================================ */
        :root {
            --ks-card-bg: #ffffff;
            --ks-card-border: #e5e7eb;
            --ks-card-shadow: 0 1px 4px rgba(0, 0, 0, .07);
            --ks-text: #111827;
            --ks-text-muted: #6b7280;
            --ks-th-bg: #f3f4f6;
            --ks-th-text: #374151;
            --ks-td-border: #e5e7eb;
            --ks-row-hover: #f9fafb;
            --ks-container-bg: #f9fafb;
            --ks-select-bg: #ffffff;
            --ks-select-border: #d1d5db;
        }

        html.dark {
            --ks-card-bg: #1e293b;
            --ks-card-border: #334155;
            --ks-card-shadow: 0 1px 6px rgba(0, 0, 0, .3);
            --ks-text: #f1f5f9;
            --ks-text-muted: #94a3b8;
            --ks-th-bg: #0f172a;
            --ks-th-text: #e2e8f0;
            --ks-td-border: #334155;
            --ks-row-hover: #293548;
            --ks-container-bg: #0f172a;
            --ks-select-bg: #1e293b;
            --ks-select-border: #475569;
        }

        .ks-card {
            background-color: var(--ks-card-bg);
            border: 1px solid var(--ks-card-border);
            border-radius: .75rem;
            box-shadow: var(--ks-card-shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .ks-card-header {
            padding: .5rem 1rem;
            border-bottom: 1px solid var(--ks-card-border);
        }

        .ks-card-header h2 {
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
        }

        .ks-table-wrapper {
            overflow-x: auto !important;
            display: block;
            width: 100%;
        }

        .keadaan-gtk-page .ks-table {
            table-layout: fixed !important;
            width: max-content !important;
            min-width: 100% !important;
            border-collapse: collapse !important;
            font-size: .75rem;
            color: var(--ks-text);
        }

        .keadaan-gtk-page .ks-table th,
        .keadaan-gtk-page .ks-table td {
            padding: 0.5rem 0.25rem !important;
            border: 1px solid var(--ks-td-border);
            white-space: nowrap !important;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
            vertical-align: middle !important;
        }

        /* colgroup classes */
        .ksc-no {
            width: 40px !important;
        }

        .ksc-nama {
            width: 250px !important;
        }

        .ksc-num {
            width: 50px !important;
        }

        .ksc-jml {
            width: 50px !important;
        }

        /* Alignment for Jenis GTK */
        .ks-w-nama {
            text-align: left !important;
            padding-left: 1rem !important;
            /* Give it some breathing room */
        }

        .keadaan-gtk-page .ks-table thead th {
            background-color: var(--ks-th-bg);
            color: var(--ks-th-text);
            font-weight: 600;
        }

        .keadaan-gtk-page .ks-table tbody tr:hover td {
            background-color: var(--ks-row-hover);
        }

        .ks-col-jml {
            background-color: rgba(59, 130, 246, 0.05) !important;
            color: #2563eb !important;
            font-weight: 700 !important;
        }

        html.dark .ks-col-jml {
            background-color: rgba(59, 130, 246, 0.15) !important;
            color: #60a5fa !important;
        }

        .ks-pagination-container {
            padding: .75rem 1.25rem;
            border-top: 1px solid var(--ks-card-border);
            background-color: var(--ks-container-bg);
        }

        .ks-pagination-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .ks-pagination-info {
            font-size: .8125rem;
            color: var(--ks-text-muted);
        }

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
        }

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
            background: #ffffff;
            color: #374151;
            text-decoration: none;
        }
    </style>
@endpush

<x-filament-panels::page>
    <div class="keadaan-gtk-page">



        <div style="display: grid; gap: .2rem;">
            {{-- Tabel 1: Agama --}}
            <div class="ks-card" wire:key="card-agama">
                <div class="ks-card-header" style="background:linear-gradient(135deg,#e9d5ff33,#d8b4fe11); display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="color:#7c3aed;">Jumlah GTK Berdasarkan Agama</h2>
                    <div wire:key="wrapper-validateGtkAgama">
                        {{ $this->validateGtkAgamaAction }}
                    </div>
                </div>
                <div class="ks-table-wrapper">
                    <table class="ks-table">
                        <colgroup>
                            <col class="ksc-no">
                            <col class="ksc-nama">
                            @for ($i = 0; $i < 18; $i++)
                                <col class="ksc-num">
                            @endfor
                        </colgroup>
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 30px;">NO</th>
                                <th rowspan="2" class="ks-w-nama">JENIS GTK</th>
                                <th colspan="3">ISLAM</th>
                                <th colspan="3">PROTESTAN</th>
                                <th colspan="3">KATOLIK</th>
                                <th colspan="3">HINDU</th>
                                <th colspan="3">BUDHA</th>
                                <th colspan="3">KONGHUCU</th>
                            </tr>
                            <tr>
                                <th>L</th>
                                <th>P</th>
                                <th class="ks-col-jml">JML</th>
                                <th>L</th>
                                <th>P</th>
                                <th class="ks-col-jml">JML</th>
                                <th>L</th>
                                <th>P</th>
                                <th class="ks-col-jml">JML</th>
                                <th>L</th>
                                <th>P</th>
                                <th class="ks-col-jml">JML</th>
                                <th>L</th>
                                <th>P</th>
                                <th class="ks-col-jml">JML</th>
                                <th>L</th>
                                <th>P</th>
                                <th class="ks-col-jml">JML</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gtkAgama as $item)
                                <tr wire:key="row-agama-{{ $loop->index }}">
                                    <td class="ks-w-no">
                                        {{ ($gtkAgama->currentPage() - 1) * $gtkAgama->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="ks-w-nama">{{ $item->jenis_gtk }}</td>
                                    <td>{{ $item->islam_l }}</td>
                                    <td>{{ $item->islam_p }}</td>
                                    <td class="ks-col-jml">{{ $item->islam_jml }}</td>
                                    <td>{{ $item->kristen_protestan_l }}</td>
                                    <td>{{ $item->kristen_protestan_p }}</td>
                                    <td class="ks-col-jml">{{ $item->kristen_protestan_jml }}</td>
                                    <td>{{ $item->katolik_l }}</td>
                                    <td>{{ $item->katolik_p }}</td>
                                    <td class="ks-col-jml">{{ $item->katolik_jml }}</td>
                                    <td>{{ $item->hindu_l }}</td>
                                    <td>{{ $item->hindu_p }}</td>
                                    <td class="ks-col-jml">{{ $item->hindu_jml }}</td>
                                    <td>{{ $item->budha_l }}</td>
                                    <td>{{ $item->budha_p }}</td>
                                    <td class="ks-col-jml">{{ $item->budha_jml }}</td>
                                    <td>{{ $item->konghucu_l }}</td>
                                    <td>{{ $item->konghucu_p }}</td>
                                    <td class="ks-col-jml">{{ $item->konghucu_jml }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="20" style="text-align:center;padding:2rem;">Data tidak tersedia</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr style="background-color: var(--ks-th-bg); font-weight: 800;">
                                <td colspan="2" style="text-align: center">TOTAL KESELURUHAN</td>
                                <td>{{ $totalGtkAgama['islam_l'] ?? 0 }}</td>
                                <td>{{ $totalGtkAgama['islam_p'] ?? 0 }}</td>
                                <td class="ks-col-jml">{{ $totalGtkAgama['islam_jml'] ?? 0 }}</td>

                                <td>{{ $totalGtkAgama['kristen_protestan_l'] ?? 0 }}</td>
                                <td>{{ $totalGtkAgama['kristen_protestan_p'] ?? 0 }}</td>
                                <td class="ks-col-jml">{{ $totalGtkAgama['kristen_protestan_jml'] ?? 0 }}</td>

                                <td>{{ $totalGtkAgama['katolik_l'] ?? 0 }}</td>
                                <td>{{ $totalGtkAgama['katolik_p'] ?? 0 }}</td>
                                <td class="ks-col-jml">{{ $totalGtkAgama['katolik_jml'] ?? 0 }}</td>

                                <td>{{ $totalGtkAgama['hindu_l'] ?? 0 }}</td>
                                <td>{{ $totalGtkAgama['hindu_p'] ?? 0 }}</td>
                                <td class="ks-col-jml">{{ $totalGtkAgama['hindu_jml'] ?? 0 }}</td>

                                <td>{{ $totalGtkAgama['budha_l'] ?? 0 }}</td>
                                <td>{{ $totalGtkAgama['budha_p'] ?? 0 }}</td>
                                <td class="ks-col-jml">{{ $totalGtkAgama['budha_jml'] ?? 0 }}</td>

                                <td>{{ $totalGtkAgama['konghucu_l'] ?? 0 }}</td>
                                <td>{{ $totalGtkAgama['konghucu_p'] ?? 0 }}</td>
                                <td class="ks-col-jml">{{ $totalGtkAgama['konghucu_jml'] ?? 0 }}</td>
                            </tr>
                        </tfoot>
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

                        @if ($gtkAgama->hasPages())
                            <div class="ks-pagination-links">
                                {{ $gtkAgama->onEachSide(1)->links('pagination::ks-pagination') }}
                            </div>
                        @endif

                        <div class="ks-pagination-info">
                            @if ($gtkAgama->total() > 0)
                                {{ $gtkAgama->firstItem() }}–{{ $gtkAgama->lastItem() }}
                                dari {{ $gtkAgama->total() }} data
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel 2: Daerah Asal --}}
            <div class="ks-card" wire:key="card-daerah">
                <div class="ks-card-header" style="background:linear-gradient(135deg,#dcfce733,#86efac11); display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="color:#15803d;">Jumlah GTK Berdasarkan Daerah Asal</h2>
                    <div wire:key="wrapper-validateGtkDaerah">
                        {{ $this->validateGtkDaerahAction }}
                    </div>
                </div>
                <div class="ks-table-wrapper">
                    <table class="ks-table">
                        <colgroup>
                            <col class="ksc-no">
                            <col class="ksc-nama">
                            @for ($i = 0; $i < 6; $i++)
                                <col class="ksc-num">
                            @endfor
                        </colgroup>
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 30px;">NO</th>
                                <th rowspan="2" class="ks-w-nama">JENIS GTK</th>
                                <th colspan="3">PAPUA</th>
                                <th colspan="3">NON PAPUA</th>
                            </tr>
                            <tr>
                                <th class="ks-w-lp">L</th>
                                <th class="ks-w-lp">P</th>
                                <th class="ks-w-jml ks-col-jml">JML</th>
                                <th class="ks-w-lp">L</th>
                                <th class="ks-w-lp">P</th>
                                <th class="ks-w-jml ks-col-jml">JML</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gtkDaerah as $item)
                                <tr wire:key="row-daerah-{{ $loop->index }}">
                                    <td class="ks-w-no">
                                        {{ ($gtkDaerah->currentPage() - 1) * $gtkDaerah->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="ks-w-nama">{{ $item->jenis_gtk }}</td>
                                    <td>{{ $item->papua_l }}</td>
                                    <td>{{ $item->papua_p }}</td>
                                    <td class="ks-col-jml">{{ $item->papua_jml }}</td>
                                    <td>{{ $item->non_papua_l }}</td>
                                    <td>{{ $item->non_papua_p }}</td>
                                    <td class="ks-col-jml">{{ $item->non_papua_jml }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align:center;padding:2rem;">Data tidak tersedia</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr style="background-color: var(--ks-th-bg); font-weight: 800;">
                                <td colspan="2" style="text-align: center">TOTAL KESELURUHAN</td>
                                <td>{{ $totalGtkDaerah['papua_l'] ?? 0 }}</td>
                                <td>{{ $totalGtkDaerah['papua_p'] ?? 0 }}</td>
                                <td class="ks-col-jml">{{ $totalGtkDaerah['papua_jml'] ?? 0 }}</td>

                                <td>{{ $totalGtkDaerah['non_papua_l'] ?? 0 }}</td>
                                <td>{{ $totalGtkDaerah['non_papua_p'] ?? 0 }}</td>
                                <td class="ks-col-jml">{{ $totalGtkDaerah['non_papua_jml'] ?? 0 }}</td>
                            </tr>
                        </tfoot>
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

                        @if ($gtkDaerah->hasPages())
                            <div class="ks-pagination-links">
                                {{ $gtkDaerah->onEachSide(1)->links('pagination::ks-pagination') }}
                            </div>
                        @endif

                        <div class="ks-pagination-info">
                            @if ($gtkDaerah->total() > 0)
                                {{ $gtkDaerah->firstItem() }}–{{ $gtkDaerah->lastItem() }}
                                dari {{ $gtkDaerah->total() }} data
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel 3: Status --}}
            <div class="ks-card" wire:key="card-status">
                <div class="ks-card-header" style="background:linear-gradient(135deg,#faf5ff33,#f3e8ff11); display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="color:#7e22ce;">Status Kepegawaian</h2>
                    <div wire:key="wrapper-validateGtkStatus">
                        {{ $this->validateGtkStatusAction }}
                    </div>
                </div>
                <div class="ks-table-wrapper" style="overflow-x: auto;">
                    <table class="ks-table" style="min-width: 1200px; border-collapse: collapse;">
                        <thead>
                            <!-- Baris 1: Header utama -->
                            <tr style="text-align: center;">
                                <th rowspan="2" style="vertical-align: middle; width: 50px;">NO</th>
                                <th rowspan="2" style="vertical-align: middle;">JENIS GTK</th>
                                <th colspan="17" style="text-align: center;">PNS</th>
                                <th rowspan="2" style="vertical-align: middle;">PPPK</th>
                                <th rowspan="2" style="vertical-align: middle;">HONORER</th>
                            </tr>
                            <!-- Baris 2: Sub golongan -->
                            <tr style="text-align: center;">
                                <!-- Golongan I -->
                                <th>Gol I/a</th>
                                <th>Gol I/b</th>
                                <th>Gol I/c</th>
                                <th>Gol I/d</th>
                                <!-- Golongan II -->
                                <th>Gol II/a</th>
                                <th>Gol II/b</th>
                                <th>Gol II/c</th>
                                <th>Gol II/d</th>
                                <!-- Golongan III -->
                                <th>Gol III/a</th>
                                <th>Gol III/b</th>
                                <th>Gol III/c</th>
                                <th>Gol III/d</th>
                                <!-- Golongan IV -->
                                <th>Gol IV/a</th>
                                <th>Gol IV/b</th>
                                <th>Gol IV/c</th>
                                <th>Gol IV/d</th>
                                <th>Gol IV/e</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gtkStatusKepegawaian as $item)
                                <tr>
                                    <td style="text-align: center;">
                                        {{ ($gtkStatusKepegawaian->currentPage() - 1) * $gtkStatusKepegawaian->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $item->jenis_gtk }}</td>
                                    <!-- Golongan I -->
                                    <td style="text-align: center;">{{ $item->gol_i_a ?? 0 }}</td>
                                    <td style="text-align: center;">{{ $item->gol_i_b ?? 0 }}</td>
                                    <td style="text-align: center;">{{ $item->gol_i_c ?? 0 }}</td>
                                    <td style="text-align: center;">{{ $item->gol_i_d ?? 0 }}</td>
                                    <!-- Golongan II -->
                                    <td style="text-align: center;">{{ $item->gol_ii_a ?? 0 }}</td>
                                    <td style="text-align: center;">{{ $item->gol_ii_b ?? 0 }}</td>
                                    <td style="text-align: center;">{{ $item->gol_ii_c ?? 0 }}</td>
                                    <td style="text-align: center;">{{ $item->gol_ii_d ?? 0 }}</td>
                                    <!-- Golongan III -->
                                    <td style="text-align: center;">{{ $item->gol_iii_a ?? 0 }}</td>
                                    <td style="text-align: center;">{{ $item->gol_iii_b ?? 0 }}</td>
                                    <td style="text-align: center;">{{ $item->gol_iii_c ?? 0 }}</td>
                                    <td style="text-align: center;">{{ $item->gol_iii_d ?? 0 }}</td>
                                    <!-- Golongan IV -->
                                    <td style="text-align: center;">{{ $item->gol_iv_a ?? 0 }}</td>
                                    <td style="text-align: center;">{{ $item->gol_iv_b ?? 0 }}</td>
                                    <td style="text-align: center;">{{ $item->gol_iv_c ?? 0 }}</td>
                                    <td style="text-align: center;">{{ $item->gol_iv_d ?? 0 }}</td>
                                    <td style="text-align: center;">{{ $item->gol_iv_e ?? 0 }}</td>
                                    <!-- PPPK & Honorer -->
                                    <td style="text-align: center;">{{ $item->pppk }}</td>
                                    <td style="text-align: center;">{{ $item->honorer_sekolah }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="20" style="text-align: center; padding: 2rem;">
                                        Data tidak tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #f3e8ff; font-weight: bold;">
                                <td colspan="2" style="text-align: center;">TOTAL KESELURUHAN</td>
                                <!-- Golongan I -->
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_i_a'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_i_b'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_i_c'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_i_d'] ?? 0 }}</td>
                                <!-- Golongan II -->
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_ii_a'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_ii_b'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_ii_c'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_ii_d'] ?? 0 }}</td>
                                <!-- Golongan III -->
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_iii_a'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_iii_b'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_iii_c'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_iii_d'] ?? 0 }}</td>
                                <!-- Golongan IV -->
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_iv_a'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_iv_b'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_iv_c'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_iv_d'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $totalGtkStatus['gol_iv_e'] ?? 0 }}</td>
                                <!-- PPPK & Honorer -->
                                <td style="text-align: center;">{{ $totalGtkStatus['pppk'] }}</td>
                                <td style="text-align: center;">{{ $totalGtkStatus['honorer'] }}</td>
                            </tr>
                        </tfoot>
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

                        @if ($gtkStatusKepegawaian->hasPages())
                            <div class="ks-pagination-links">
                                {{ $gtkStatusKepegawaian->onEachSide(1)->links('pagination::ks-pagination') }}
                            </div>
                        @endif

                        <div class="ks-pagination-info">
                            @if ($gtkStatusKepegawaian->total() > 0)
                                {{ $gtkStatusKepegawaian->firstItem() }}–{{ $gtkStatusKepegawaian->lastItem() }}
                                dari {{ $gtkStatusKepegawaian->total() }} data
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel 4: Umur --}}
            <div class="ks-card" wire:key="card-umur">
                <div class="ks-card-header" style="background:linear-gradient(135deg,#fff7ed33,#ffedd511); display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="color:#c2410c;">Jumlah GTK Berdasarkan Umur</h2>
                    <div wire:key="wrapper-validateGtkUmur">
                        {{ $this->validateGtkUmurAction }}
                    </div>
                </div>
                <div class="ks-table-wrapper">
                    <table class="ks-table">
                        <colgroup>
                            <col class="ksc-no">
                            <col class="ksc-nama">
                            @for ($i = 0; $i < 33; $i++)
                                <col class="ksc-num">
                            @endfor
                        </colgroup>
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 30px;">NO</th>
                                <th rowspan="2" class="ks-w-nama">JENIS GTK</th>
                                @for ($age = 13; $age <= 23; $age++)
                                    <th colspan="3">{{ $age }} Thn</th>
                                @endfor
                            </tr>
                            <tr>
                                @for ($age = 13; $age <= 23; $age++)
                                    <th class="ks-w-lp" style="font-size: 0.65rem;">L</th>
                                    <th class="ks-w-lp" style="font-size: 0.65rem;">P</th>
                                    <th class="ks-w-jml ks-col-jml" style="font-size: 0.65rem;">JML</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gtkUmur as $item)
                                <tr wire:key="row-umur-{{ $loop->index }}">
                                    <td class="ks-w-no">
                                        {{ ($gtkUmur->currentPage() - 1) * $gtkUmur->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="ks-w-nama">{{ $item->jenis_gtk }}</td>
                                    @for ($age = 13; $age <= 23; $age++)
                                        @php $prefix = 'umur_' . $age; @endphp
                                        <td>{{ $item->{$prefix . '_l'} }}</td>
                                        <td>{{ $item->{$prefix . '_p'} }}</td>
                                        <td class="ks-col-jml">{{ $item->{$prefix . '_jml'} }}</td>
                                    @endfor
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="35" style="text-align:center;padding:2rem;">Data tidak tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr style="background-color: var(--ks-th-bg); font-weight: 800;">
                                <td colspan="2" style="text-align: center">TOTAL KESELURUHAN</td>
                                @for ($age = 13; $age <= 23; $age++)
                                    @php $prefix = 'umur_' . $age; @endphp
                                    <td>{{ $totalGtkUmur[$prefix . '_l'] ?? 0 }}</td>
                                    <td>{{ $totalGtkUmur[$prefix . '_p'] ?? 0 }}</td>
                                    <td class="ks-col-jml">{{ $totalGtkUmur[$prefix . '_jml'] ?? 0 }}</td>
                                @endfor
                            </tr>
                        </tfoot>
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

                        @if ($gtkUmur->hasPages())
                            <div class="ks-pagination-links">
                                {{ $gtkUmur->onEachSide(1)->links('pagination::ks-pagination') }}
                            </div>
                        @endif

                        <div class="ks-pagination-info">
                            @if ($gtkUmur->total() > 0)
                                {{ $gtkUmur->firstItem() }}–{{ $gtkUmur->lastItem() }}
                                dari {{ $gtkUmur->total() }} data
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel 5: Pendidikan --}}
            <div class="ks-card" wire:key="card-pendidikan">
                <div class="ks-card-header" style="background:linear-gradient(135deg,#fff1f233,#ffe4e611); display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="color:#be123c;">Jumlah GTK Berdasarkan Pendidikan Terakhir</h2>
                    <div wire:key="wrapper-validateGtkPendidikan">
                        {{ $this->validateGtkPendidikanAction }}
                    </div>
                </div>
                <div class="ks-table-wrapper">
                    <table class="ks-table">
                        <colgroup>
                            <col class="ksc-no">
                            <col class="ksc-nama">
                            @for ($i = 0; $i < 7; $i++)
                                <col class="ksc-num">
                            @endfor
                        </colgroup>
                        <thead>
                            <tr>
                                <th style="width:30px;">NO</th>
                                <th class="ks-w-nama">JENIS GTK</th>
                                <th class="ks-w-jml">SLTA</th>
                                <th class="ks-w-jml">DI</th>
                                <th class="ks-w-jml">DII</th>
                                <th class="ks-w-jml">DIII</th>
                                <th class="ks-w-jml">S1</th>
                                <th class="ks-w-jml">S2</th>
                                <th class="ks-w-jml">S3</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gtkPendidikan as $item)
                                <tr wire:key="row-pend-{{ $loop->index }}">
                                    <td class="ks-w-no">
                                        {{ ($gtkPendidikan->currentPage() - 1) * $gtkPendidikan->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="ks-w-nama">{{ $item->jenis_gtk }}</td>
                                    <td>{{ $item->slta }}</td>
                                    <td>{{ $item->di }}</td>
                                    <td>{{ $item->dii }}</td>
                                    <td>{{ $item->diii }}</td>
                                    <td>{{ $item->s1 }}</td>
                                    <td>{{ $item->s2 }}</td>
                                    <td>{{ $item->s3 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" style="text-align:center;padding:2rem;">Data tidak tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr style="background-color: var(--ks-th-bg); font-weight: 800;">
                                <td colspan="2" style="text-align: center">TOTAL KESELURUHAN</td>
                                <td>{{ $totalGtkPendidikan['slta'] }}</td>
                                <td>{{ $totalGtkPendidikan['di'] }}</td>
                                <td>{{ $totalGtkPendidikan['dii'] }}</td>
                                <td>{{ $totalGtkPendidikan['diii'] }}</td>
                                <td>{{ $totalGtkPendidikan['s1'] }}</td>
                                <td>{{ $totalGtkPendidikan['s2'] }}</td>
                                <td>{{ $totalGtkPendidikan['s3'] }}</td>
                            </tr>
                        </tfoot>
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

                        @if ($gtkPendidikan->hasPages())
                            <div class="ks-pagination-links">
                                {{ $gtkPendidikan->onEachSide(1)->links('pagination::ks-pagination') }}
                            </div>
                        @endif

                        <div class="ks-pagination-info">
                            @if ($gtkPendidikan->total() > 0)
                                {{ $gtkPendidikan->firstItem() }}–{{ $gtkPendidikan->lastItem() }}
                                dari {{ $gtkPendidikan->total() }} data
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
