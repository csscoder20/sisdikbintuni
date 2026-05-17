<x-filament-panels::page>
    <div class="rl-container">
        
        <style>
            .rl-container {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
                width: 100%;
            }
            
            /* Card styling */
            .rl-card {
                background: #ffffff;
                border: 1px solid #f1f5f9;
                border-radius: 1rem;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
                padding: 1.5rem;
                transition: all 0.3s ease;
            }
            
            .dark .rl-card {
                background: #18181b;
                border-color: #27272a;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.2);
            }
            
            /* Header flex layout */
            .rl-header-flex {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: flex-start;
                gap: 1rem;
            }
            
            @media (min-width: 768px) {
                .rl-header-flex {
                    flex-direction: row;
                    align-items: center;
                }
            }
            
            .rl-title-group h2 {
                font-size: 1.125rem;
                font-weight: 700;
                color: #0f172a;
                margin: 0;
            }
            
            .dark .rl-title-group h2 {
                color: #f8fafc;
            }
            
            .rl-title-group p {
                font-size: 0.875rem;
                color: #64748b;
                margin: 0.25rem 0 0 0;
            }
            
            .dark .rl-title-group p {
                color: #a1a1aa;
            }
            
            /* Dropdown styling */
            .rl-select-wrapper {
                position: relative;
                width: 100%;
            }
            
            @media (min-width: 768px) {
                .rl-select-wrapper {
                    width: 18rem;
                }
            }
            
            .rl-select {
                width: 100%;
                background-color: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 0.75rem;
                padding: 0.625rem 2.5rem 0.625rem 1rem;
                font-size: 0.875rem;
                font-weight: 600;
                color: #334155;
                appearance: none;
                cursor: pointer;
                transition: all 0.2s ease;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }
            
            .dark .rl-select {
                background-color: #27272a;
                border-color: #3f3f46;
                color: #e4e4e7;
            }
            
            .rl-select:focus {
                outline: none;
                border-color: #ea580c;
                box-shadow: 0 0 0 2px rgba(234, 88, 12, 0.15);
            }
            
            .rl-select-wrapper::after {
                content: '';
                position: absolute;
                right: 1.125rem;
                top: 50%;
                transform: translateY(-50%);
                width: 0.45rem;
                height: 0.45rem;
                border-right: 2px solid #64748b;
                border-bottom: 2px solid #64748b;
                transform: translateY(-70%) rotate(45deg);
                pointer-events: none;
            }
            
            .dark .rl-select-wrapper::after {
                border-color: #a1a1aa;
            }
            
            /* Table Header layout */
            .rl-table-card {
                padding: 0;
                overflow: hidden;
            }
            
            .rl-table-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1.5rem;
                border-bottom: 1px solid #f1f5f9;
                background: linear-gradient(135deg, #f8fafc, #ffffff);
            }
            
            .dark .rl-table-header {
                border-color: #27272a;
                background: linear-gradient(135deg, #09090b, #18181b);
            }
            
            .rl-th-title-group {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }
            
            .rl-th-icon-box {
                padding: 0.5rem;
                background: #fff7ed;
                color: #ea580c;
                border-radius: 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .dark .rl-th-icon-box {
                background: rgba(234, 88, 12, 0.1);
                color: #f97316;
            }
            
            .rl-th-icon-box svg {
                width: 1.5rem;
                height: 1.5rem;
            }
            
            .rl-th-text h3 {
                font-size: 1rem;
                font-weight: 700;
                color: #0f172a;
                margin: 0;
            }
            
            .dark .rl-th-text h3 {
                color: #f8fafc;
            }
            
            .rl-th-text p {
                font-size: 0.75rem;
                color: #64748b;
                margin: 0.125rem 0 0 0;
            }
            
            .dark .rl-th-text p {
                color: #a1a1aa;
            }
            
            .rl-th-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.375rem;
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 600;
                background: #ffedd5;
                color: #9a3412;
            }
            
            .dark .rl-th-badge {
                background: rgba(234, 88, 12, 0.15);
                color: #fdba74;
            }
            
            .rl-th-pulse {
                width: 0.375rem;
                height: 0.375rem;
                border-radius: 50%;
                background: #ea580c;
                animation: rl-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
            
            @keyframes rl-pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: .4; }
            }
            
            /* Table design */
            .rl-table-responsive {
                overflow-x: auto;
                width: 100%;
            }
            
            .rl-table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
                font-size: 0.875rem;
            }
            
            .rl-table th {
                padding: 0.5rem 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
                color: #475569;
                background: #f8fafc;
                border-bottom: 1px solid #e2e8f0;
                border-right: 1px solid #f1f5f9;
            }
            
            .dark .rl-table th {
                color: #a1a1aa;
                background: #09090b;
                border-color: #27272a;
            }
            
            .rl-table th.rl-col-main-header {
                background: #fffaf5;
                color: #ea580c;
                border-bottom-color: #fed7aa;
                text-align: center;
                font-weight: 800;
            }
            
            .dark .rl-table th.rl-col-main-header {
                background: rgba(234, 88, 12, 0.03);
                color: #fdba74;
                border-bottom-color: #431407;
            }
            
            .rl-table td {
                padding: 0.4rem 0.75rem;
                border-bottom: 1px solid #f1f5f9;
                border-right: 1px solid #f1f5f9;
                vertical-align: middle;
                color: #334155;
            }
            
            .dark .rl-table td {
                border-color: #27272a;
                color: #e4e4e7;
            }
            
            .rl-table tr:hover {
                background-color: #fafafa;
            }
            
            .dark .rl-table tr:hover {
                background-color: #1f1f23;
            }
            
            /* School row styling */
            .rl-school-cell {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }
            
            .rl-school-name {
                color: #1e293b;
            }
            
            .dark .rl-school-name {
                color: #f1f5f9;
            }
            
            /* Button PDF Unduh */
            .rl-btn-pdf {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 2.25rem;
                height: 2.25rem;
                border-radius: 0.75rem;
                background: #fff1f2;
                border: 1px solid #ffe4e6;
                color: #e11d48;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }
            
            .dark .rl-btn-pdf {
                background: rgba(225, 29, 72, 0.1);
                border-color: rgba(225, 29, 72, 0.2);
                color: #fb7185;
            }
            
            .rl-btn-pdf:hover {
                background: #ffe4e6;
                color: #be123c;
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }
            
            .dark .rl-btn-pdf:hover {
                background: rgba(225, 29, 72, 0.25);
                color: #fda4af;
            }
            
            .rl-btn-pdf:active {
                transform: translateY(0);
                box-shadow: none;
            }
            
            .rl-btn-pdf svg {
                width: 1.25rem;
                height: 1.25rem;
            }
            
            .rl-empty-dash {
                color: #cbd5e1;
                font-weight: 400;
                font-size: 0.875rem;
            }
            
            .dark .rl-empty-dash {
                color: #4b5563;
            }
            
            .rl-empty-row-text {
                padding: 3rem 1.5rem;
                text-align: center;
                color: #64748b;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }
            
            .dark .rl-empty-row-text {
                color: #a1a1aa;
            }
            
            .rl-empty-row-text svg {
                width: 2rem;
                height: 2rem;
                color: #cbd5e1;
            }
            
            .dark .rl-empty-row-text svg {
                color: #4b5563;
            }
            
            /* Pagination Footer styling */
            .rl-pagination-footer {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
                padding: 1.25rem 1.5rem;
                border-top: 1px solid #f1f5f9;
                background-color: #ffffff;
            }
            
            .dark .rl-pagination-footer {
                border-color: #27272a;
                background-color: #18181b;
            }
            
            @media (min-width: 768px) {
                .rl-pagination-footer {
                    flex-direction: row;
                }
            }
            
            .rl-pagination-info {
                font-size: 0.8125rem;
                color: #64748b;
            }
            
            .dark .rl-pagination-info {
                color: #a1a1aa;
            }
            
            .rl-pagination-info span {
                font-weight: 700;
                color: #1e293b;
            }
            
            .dark .rl-pagination-info span {
                color: #f1f5f9;
            }
            
            .rl-pagination-buttons {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            
            .rl-pg-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.375rem;
                padding: 0.5rem 0.875rem;
                font-size: 0.8125rem;
                font-weight: 600;
                color: #334155;
                background-color: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 0.5rem;
                cursor: pointer;
                transition: all 0.2s ease;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }
            
            .dark .rl-pg-btn {
                color: #e4e4e7;
                background-color: #27272a;
                border-color: #3f3f46;
            }
            
            .rl-pg-btn:hover:not(.disabled) {
                background-color: #f8fafc;
                border-color: #cbd5e1;
                color: #0f172a;
            }
            
            .dark .rl-pg-btn:hover:not(.disabled) {
                background-color: #3f3f46;
                color: #ffffff;
            }
            
            .rl-pg-btn.disabled, .rl-pg-btn:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }
            
            .rl-pg-btn svg {
                width: 0.875rem;
                height: 0.875rem;
            }
            
            .rl-pagination-pages-list {
                display: none;
                align-items: center;
                gap: 0.25rem;
            }
            
            @media (min-width: 640px) {
                .rl-pagination-pages-list {
                    display: flex;
                }
            }
            
            .rl-pg-number {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 2.25rem;
                height: 2.25rem;
                border-radius: 0.5rem;
                font-size: 0.8125rem;
                font-weight: 600;
                color: #475569;
                background-color: #ffffff;
                border: 1px solid #e2e8f0;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            
            .dark .rl-pg-number {
                color: #a1a1aa;
                background-color: #27272a;
                border-color: #3f3f46;
            }
            
            .rl-pg-number:hover:not(.active) {
                background-color: #f8fafc;
                color: #0f172a;
                border-color: #cbd5e1;
            }
            
            .dark .rl-pg-number:hover:not(.active) {
                background-color: #3f3f46;
                color: #ffffff;
            }
            
            .rl-pg-number.active {
                background-color: #ea580c;
                color: #ffffff;
                border-color: #ea580c;
                cursor: default;
                box-shadow: 0 1px 3px 0 rgba(234, 88, 12, 0.3);
            }
            
            .dark .rl-pg-number.active {
                background-color: #ea580c;
                color: #ffffff;
                border-color: #ea580c;
                box-shadow: 0 1px 3px 0 rgba(234, 88, 12, 0.4);
            }
            
            /* Filters Group */
            .rl-filters-group {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
                width: 100%;
            }
            
            @media (min-width: 768px) {
                .rl-filters-group {
                    flex-direction: row;
                    align-items: center;
                    width: auto;
                }
            }
            
            /* Search box styling */
            .rl-search-wrapper {
                position: relative;
                width: 100%;
            }
            
            @media (min-width: 768px) {
                .rl-search-wrapper {
                    width: 18rem;
                }
            }
            
            .rl-search-input {
                width: 100%;
                background-color: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 0.75rem;
                padding: 0.625rem 1rem 0.625rem 2.5rem;
                font-size: 0.875rem;
                font-weight: 550;
                color: #334155;
                transition: all 0.2s ease;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }
            
            .dark .rl-search-input {
                background-color: #27272a;
                border-color: #3f3f46;
                color: #e4e4e7;
            }
            
            .rl-search-input::placeholder {
                color: #94a3b8;
            }
            
            .dark .rl-search-input::placeholder {
                color: #71717a;
            }
            
            .rl-search-input:focus {
                outline: none;
                border-color: #ea580c;
                box-shadow: 0 0 0 2px rgba(234, 88, 12, 0.15);
            }
            
            .rl-search-icon {
                position: absolute;
                left: 0.875rem;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                pointer-events: none;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .dark .rl-search-icon {
                color: #71717a;
            }
            
            .rl-search-icon svg {
                width: 1.125rem;
                height: 1.125rem;
            }
        </style>

        <!-- Header Card / Filter Panel -->
        <div class="rl-card">
            <div class="rl-header-flex">
                <div class="rl-title-group">
                    <h2>Filter & Pencarian</h2>
                    <p>Saring berdasarkan tahun ajaran dan cari nama sekolah atau NPSN.</p>
                </div>
                <div class="rl-filters-group">
                    <!-- Search Box -->
                    <div class="rl-search-wrapper">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search" 
                               placeholder="Cari sekolah atau NPSN..." 
                               class="rl-search-input">
                        <span class="rl-search-icon">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                    </div>

                    <!-- Year Selector -->
                    <div class="rl-select-wrapper">
                        <select wire:model.live="tahunAjaran" 
                                id="tahun_ajaran_select"
                                class="rl-select">
                            @foreach($this->getTahunAjaranOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="rl-card rl-table-card">
            <div class="rl-table-header">
                <div class="rl-th-title-group">
                    <div class="rl-th-icon-box">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path>
                        </svg>
                    </div>
                    <div class="rl-th-text">
                        <h3>Tabel Riwayat Laporan</h3>
                        <p>Menampilkan status & berkas PDF laporan bulanan.</p>
                    </div>
                </div>
                <span class="rl-th-badge">
                    <span class="rl-th-pulse"></span>
                    Tahun Ajaran {{ $tahunAjaran }}
                </span>
            </div>

            <!-- Custom Table -->
            <div class="rl-table-responsive">
                @php
                    $sekolahs = $this->getSekolahs();
                    $periods = $this->getPeriods();
                    $laporanData = $this->getLaporanData();
                @endphp
                <table class="rl-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="text-align: center; font-weight: normal; width: 60px;">NO</th>
                            <th rowspan="2" style="min-width: 360px;">NAMA SEKOLAH</th>
                            <th colspan="12" class="rl-col-main-header">
                                PERIODE LAPORAN BULANAN
                            </th>
                        </tr>
                        <tr>
                            @foreach($periods as $period)
                                <th style="text-align: center; min-width: 95px;">
                                    {{ $period['label'] }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sekolahs as $index => $sekolah)
                            <tr>
                                <td style="text-align: center; font-weight: 600; background: rgba(248, 250, 252, 0.5);">
                                    {{ ($sekolahs->currentPage() - 1) * $sekolahs->perPage() + $index + 1 }}
                                </td>
                                <td>
                                    <div class="rl-school-cell">
                                        <div>
                                            <span class="rl-school-name">
                                                {{ $sekolah->nama }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                @foreach($periods as $period)
                                    @php
                                        $key = "{$sekolah->id}_{$period['year']}_{$period['month']}";
                                        $laporan = $laporanData[$key] ?? null;
                                    @endphp
                                    <td style="text-align: center;">
                                        @if($laporan)
                                            <a href="{{ route('cetak-laporan.pdf', $sekolah) . '?laporan_id=' . $laporan->id }}" 
                                               target="_blank"
                                               class="rl-btn-pdf"
                                               title="Unduh Laporan {{ $period['label'] }}">
                                                <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        @else
                                            <span class="rl-empty-dash">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14">
                                    <div class="rl-empty-row-text">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span style="font-weight: 600; font-size: 0.875rem;">Tidak ada data sekolah ditemukan.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @if($sekolahs->hasPages())
                <div class="rl-pagination-footer">
                    <div class="rl-pagination-info">
                        Menampilkan <span>{{ $sekolahs->firstItem() }}</span> sampai <span>{{ $sekolahs->lastItem() }}</span> dari <span>{{ $sekolahs->total() }}</span> sekolah
                    </div>
                    <div class="rl-pagination-buttons">
                        {{-- Previous Page Button --}}
                        @if ($sekolahs->onFirstPage())
                            <button class="rl-pg-btn disabled" disabled>
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"></path>
                                </svg>
                                <span>Sebelumnya</span>
                            </button>
                        @else
                            <button wire:click="previousPage" wire:loading.attr="disabled" class="rl-pg-btn">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"></path>
                                </svg>
                                <span>Sebelumnya</span>
                            </button>
                        @endif

                        {{-- Pagination Pages --}}
                        <div class="rl-pagination-pages-list">
                            @foreach ($sekolahs->getUrlRange(max(1, $sekolahs->currentPage() - 2), min($sekolahs->lastPage(), $sekolahs->currentPage() + 2)) as $page => $url)
                                @if ($page == $sekolahs->currentPage())
                                    <span class="rl-pg-number active">{{ $page }}</span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})" class="rl-pg-number">{{ $page }}</button>
                                @endif
                            @endforeach
                        </div>

                        {{-- Next Page Button --}}
                        @if ($sekolahs->hasMorePages())
                            <button wire:click="nextPage" wire:loading.attr="disabled" class="rl-pg-btn">
                                <span>Selanjutnya</span>
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                                </svg>
                            </button>
                        @else
                            <button class="rl-pg-btn disabled" disabled>
                                <span>Selanjutnya</span>
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
