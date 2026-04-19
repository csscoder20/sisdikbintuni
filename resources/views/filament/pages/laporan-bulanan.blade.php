<x-filament-panels::page>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>
        label,
        button {
            font-size: .875rem;
        }

        #print-area { display: none; }

        @media print {
            body > *:not(#print-area) { display: none !important; }
            #print-area {
                display: block !important;
                font-family: Arial, sans-serif;
                font-size: 10px;
                color: #000;
                background: white;
            }
            #print-area table { width: 100%; border-collapse: collapse !important; margin-bottom: 10px; }
            #print-area th, #print-area td { border: 1px solid #333 !important; padding: 4px !important; text-align: left !important; font-size: 9px !important; line-height: normal !important; vertical-align: middle !important; }
            #print-area th.text-center, #print-area td.text-center { text-align: center !important; }
            #print-area th { background-color: #e8e8e8 !important; font-weight: bold !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            #print-area h2 { font-size: 13px; margin: 0 0 6px 0; }
            #print-area .print-section { border: 1px solid #ccc; margin-bottom: 20px; page-break-inside: avoid; }
            #print-area .print-section-title { background: #e8e8e8 !important; padding: 6px 10px; font-weight: bold; border-bottom: 1px solid #ccc; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            #print-area .print-section-body { padding: 10px; }
            @page { size: A4 landscape; margin: 12mm; }
        }

        .checklist-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1fr;
        }

        @media (min-width: 768px) and (max-width: 1279px) {
            .checklist-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1280px) {
            .checklist-grid {
                grid-template-columns: repeat(4, 1fr);
            }
            .card-siswa { grid-column: 1; grid-row: 1 / span 2; }
            .card-gtk { grid-column: 2; grid-row: 1 / span 2; }
            .card-sarpras { grid-column: 3; grid-row: 1; }
            .card-sebaran { grid-column: 4; grid-row: 1; }
            .card-kehadiran { grid-column: 3; grid-row: 2; }
            .card-kelulusan { grid-column: 4; grid-row: 2; }
        }

        /* Tab Styles */
        .tabs-header {
            display: flex;
            background-color: #f3f4f6;
            padding: 4px;
            border-radius: 8px;
            margin: 0 1.5rem 1rem 1.5rem;
            gap: 4px;
        }

        .tab-btn {
            flex: 1;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #4b5563;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .tab-btn.active {
            background-color: white;
            color: #1f2937;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Chart Styles */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1.5rem;
            padding: 0 1.5rem 1.5rem 1.5rem;
        }

        @media (min-width: 768px) {
            .charts-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .chart-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .chart-card h4 {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #374151;
            align-self: flex-start;
        }

        .chart-container {
            width: 100%;
            height: 300px;
            position: relative;
        }

    </style>
    <!-- Dashboard Header -->

    <!-- Dashboard Cards -->
    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 0rem;">
        <!-- GTK Card -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 0.5rem; border-left: 4px solid #3b82f6;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="background-color: #dbeafe; padding: 0.75rem; border-radius: 8px;">
                    <svg style="width: 24px; height: 24px; color: #1e40af;" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                    </svg>
                </div>
                <div>
                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Guru & Tenaga Kependidikan</p>
                    <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937; margin: 0;">
                        {{ \App\Models\Gtk::where('sekolah_id', auth()->user()->sekolah?->id)->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Siswa Card -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; border-left: 4px solid #10b981;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="background-color: #dcfce7; padding: 0.75rem; border-radius: 8px;">
                    <svg style="width: 24px; height: 24px; color: #047857;" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v2h8v-2zM2 8a2 2 0 11-4 0 2 2 0 014 0zM18 15v2h5v-2a4 4 0 00-5-3.87M9 11a6 6 0 0112 0v2H9v-2z" />
                    </svg>
                </div>
                <div>
                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Peserta Didik Aktif</p>
                    <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937; margin: 0;">
                        {{ \App\Models\Siswa::where('sekolah_id', auth()->user()->sekolah?->id)->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Rombel Card -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; border-left: 4px solid #a855f7;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="background-color: #e9d5ff; padding: 0.75rem; border-radius: 8px;">
                    <svg style="width: 24px; height: 24px; color: #7e22ce;" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                </div>
                <div>
                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Rombel</p>
                    <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937; margin: 0;">
                        {{ \App\Models\Rombel::where('sekolah_id', auth()->user()->sekolah?->id)->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Sarpras Card -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; border-left: 4px solid #f59e0b;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="background-color: #fed7aa; padding: 0.75rem; border-radius: 8px;">
                    <svg style="width: 24px; height: 24px; color: #b45309;" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 012-2h6a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V3z" />
                    </svg>
                </div>
                <div>
                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Gedung/Ruang</p>
                    <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937; margin: 0;">
                        {{ \App\Models\LaporanGedung::whereHas('laporan', function ($query) {
                            $query->where('sekolah_id', auth()->user()->sekolah?->id);
                        })->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div
        style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 0.5rem;">
        <h2 style="font-size: 1rem; font-weight: bold; margin-bottom: 1rem; color: #1f2937;">Progres Laporan:</h2>
        <div style="background-color: #e5e7eb; border-radius: 4px; height: 24px; overflow: hidden; position: relative;">
            <div id="progressBar"
                style="background-color: #f97316; height: 24px; border-radius: 4px; transition: width 0.3s ease; width: 0%; display: flex; align-items: center; justify-content: center;">
                <span id="progressText"
                    style="color: white; font-size: 0.75rem; font-weight: bold; margin: 0;">0%</span>
            </div>
        </div>
    </div>

    <!-- Checklist Form Section -->
    <form id="reportForm"
        style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 0.5rem;">
        <h2 style="font-size: 1rem; font-weight: bold; margin-bottom: 1.5rem; color: #1f2937;">Checklist Pembaruan Laporan Bulanan</h2>

        <div class="checklist-grid">
            @foreach ($this->groups as $groupLabel => $items)
                @php
                    $doneInGroup = collect($items)->filter(fn($key) => $this->checklistStatus[$key] ?? false)->count();
                    $totalInGroup = count($items);
                    $isGroupDone = $doneInGroup === $totalInGroup;

                    $cardClass = '';
                    if ($groupLabel === 'Keadaan Siswa') $cardClass = 'card-siswa';
                    elseif ($groupLabel === 'Keadaan GTK') $cardClass = 'card-gtk';
                    elseif ($groupLabel === 'Kondisi Gedung/Ruang') $cardClass = 'card-sarpras';
                    elseif ($groupLabel === 'Sebaran Jam Mengajar') $cardClass = 'card-sebaran';
                    elseif ($groupLabel === 'Kehadiran GTK') $cardClass = 'card-kehadiran';
                    elseif ($groupLabel === 'Kelulusan') $cardClass = 'card-kelulusan';
                @endphp
                <div class="{{ $cardClass }}" style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem;">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 2px solid {{ $isGroupDone ? '#10b981' : '#e5e7eb' }}; padding-bottom: 0.5rem;">
                        <h3 style="font-size: 0.9rem; font-weight: 700; color: #374151; margin: 0;">{{ $groupLabel }}</h3>
                        <span style="font-size: 0.75rem; font-weight: 600; color: {{ $isGroupDone ? '#059669' : '#6b7280' }};">
                            {{ $doneInGroup }}/{{ $totalInGroup }} Valid
                        </span>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @foreach ($items as $key)
                            @php
                                $label = $this->checklist[$key] ?? $key;
                                $isDone = $this->checklistStatus[$key] ?? false;
                            @endphp
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.25rem 0;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" id="checkbox-{{ $key }}" 
                                        {{ $isDone ? 'checked' : '' }}
                                        disabled
                                        value="{{ $key }}"
                                        class="report-checkbox"
                                        style="width: 16px; height: 16px; cursor: default; accent-color: #10b981;">
                                    <label for="checkbox-{{ $key }}"
                                        style="margin: 0; font-size: 0.8rem; color: {{ $isDone ? '#111827' : '#6b7280' }};">
                                        {{ $label }}
                                    </label>
                                </div>
                                @if($isDone)
                                    <svg style="width: 16px; height: 16px; color: #10b981;" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </form>

    <!-- Action Buttons -->
    <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
        <button type="button" id="previewBtn"
            style="flex: 1; min-width: 200px; background-color: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; border: none; font-weight: 500; cursor: pointer; transition: background-color 0.2s; font-size: 0.75rem;">
            PRATINJAU LAPORAN BULANAN
        </button>
        <button type="button" id="submitBtn"
            style="flex: 1; min-width: 200px; background-color: #10b981; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; border: none; font-weight: 500; cursor: pointer; transition: background-color 0.2s; font-size: 0.75rem;">
            KIRIMI LAPORAN BULANAN
        </button>
    </div>

    <!-- Modals for each checklist item -->
    @foreach ($this->checklist as $key => $label)
        @if ($this->checklistStatus[$key] ?? false)
            <div id="modal-{{ $key }}" role="dialog"
                style="display: none; position: fixed; inset: 0; z-index: 50; align-items: center; justify-content: center; background-color: rgba(0,0,0,0.5);">
                <div
                    style="background-color: white; border-radius: 8px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); width: 100%; max-width: 40rem; max-height: 90vh; overflow: hidden; margin: 0 1rem; display: flex; flex-direction: column;">
                    <!-- Modal Header -->
                    <div
                        style="border-bottom: 1px solid #e5e7eb; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; background-color: #f9fafb;">
                        <h2 style="font-size: 1.125rem; font-weight: bold; color: #1f2937; margin: 0;">
                            {{ $label }}</h2>
                        <button type="button" class="modal-close" data-key="{{ $key }}"
                            style="background: none; border: none; color: #9ca3af; cursor: pointer; padding: 0; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="report-modal-content-wrapper" style="flex: 1; overflow-y: auto; padding: 1.5rem;">
                        @php
                            $previewData = $this->getChecklistPreviewData($key);
                        @endphp

                        @if (empty($previewData))
                            <div style="text-align: center; padding: 2rem 0;">
                                <p style="color: #6b7280;">Tidak ada data untuk ditampilkan</p>
                            </div>
                        @else
                            @if ($key === 'identitas_sekolah')
                                <div style="overflow-x: auto; border: 1px solid #e5e7eb; border-radius: 8px;">
                                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem; text-align: left;">
                                        <tbody>
                                            @foreach ($previewData as $item)
                                                <tr style="border-bottom: 1px solid #e5e7eb; {{ $loop->even ? 'background-color: #f9fafb;' : 'background-color: white;' }}">
                                                    <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151; width: 30%; border-right: 1px solid #e5e7eb;">
                                                        {{ $item['label'] }}
                                                    </th>
                                                    <td style="padding: 0.75rem 1rem; color: #1f2937;">
                                                        {{ $item['value'] }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                @php
                                    $firstDetails = collect($previewData)->first()['details'] ?? [];
                                    $headers = is_array($firstDetails) ? array_keys($firstDetails) : ['Keterangan'];
                                @endphp
                                <div style="overflow-x: auto;">
                                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem; text-align: left;">
                                        <thead>
                                            <tr style="background-color: #f3f4f6; border-bottom: 2px solid #d1d5db;">
                                                <th style="padding: 4px; font-weight: 600; color: #374151; border-right: 1px solid #e5e7eb; border-left: 1px solid #e5e7eb; border-top: 1px solid #e5e7eb; width: 50px; text-align: center;">No.</th>
                                                <th style="padding: 4px; font-weight: 600; color: #374151; border-right: 1px solid #e5e7eb; border-top: 1px solid #e5e7eb;">
                                                    {{ $key === 'kondisi_sarpras' ? 'Nama Gedung/Ruang' : 'Nama Lengkap / Rincian' }}
                                                </th>
                                                @foreach ($headers as $header)
                                                    @php
                                                        $centeredHeaders = ['No.', 'Hari Kerja', 'Sakit', 'Alpa', 'Izin', 'Jam', 'Jumlah', 'Kondisi Baik', 'Rusak', 'Persentase Kelulusan', 'Kepemilikan'];
                                                        $isCentered = in_array($header, $centeredHeaders);
                                                    @endphp
                                                    <th style="padding: 4px; font-weight: 600; color: #374151; border-right: 1px solid #e5e7eb; border-top: 1px solid #e5e7eb; {{ $isCentered ? 'text-align: center;' : '' }}">{{ $header }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($previewData as $index => $item)
                                                <tr style="border-bottom: 1px solid #e5e7eb; {{ $loop->even ? 'background-color: #f9fafb;' : 'background-color: white;' }} hover:background-color: #f3f4f6;">
                                                    <td style="padding: 4px; color: #6b7280; text-align: center; border-right: 1px solid #e5e7eb; border-left: 1px solid #e5e7eb;">{{ $index + 1 }}</td>
                                                    <td style="padding: 4px; font-weight: 500; color: #1f2937; border-right: 1px solid #e5e7eb;">{{ $item['label'] }}</td>
                                                    @if (is_array($item['details']))
                                                        @foreach ($headers as $header)
                                                            @php
                                                                $centeredHeaders = ['No.', 'Hari Kerja', 'Sakit', 'Alpa', 'Izin', 'Jam', 'Jumlah', 'Kondisi Baik', 'Rusak', 'Persentase Kelulusan', 'Kepemilikan'];
                                                                $isCentered = in_array($header, $centeredHeaders);
                                                            @endphp
                                                            <td style="padding: 4px; color: #4b5563; border-right: 1px solid #e5e7eb; {{ $isCentered ? 'text-align: center;' : '' }}">{{ $item['details'][$header] ?? '-' }}</td>
                                                        @endforeach
                                                    @else
                                                        <td style="padding: 4px; color: #4b5563; border-right: 1px solid #e5e7eb;" colspan="{{ count($headers) }}">{{ $item['details'] }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        @endif
                    </div>

                    <!-- Modal Footer -->
                    <div style="border-top: 1px solid #e5e7eb; padding: 1.5rem; background-color: #f9fafb;">
                        <button type="button" class="modal-close" data-key="{{ $key }}"
                            style="width: 100%; background-color: #e5e7eb; color: #1f2937; padding: 0.75rem; border-radius: 6px; border: none; font-weight: 500; cursor: pointer; transition: background-color 0.2s;">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
 
    <!-- Selection Modal -->
    <div id="selection-modal" role="dialog"
        style="display: none; position: fixed; inset: 0; z-index: 60; align-items: center; justify-content: center; background-color: rgba(0,0,0,0.5);">
        <div style="background-color: white; border-radius: 8px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); width: 100%; max-width: 25rem; margin: 0 1rem;">
            <div style="border-bottom: 1px solid #e5e7eb; padding: 1.25rem; background-color: #f9fafb; border-radius: 8px 8px 0 0;">
                <h2 style="font-size: 1rem; font-weight: bold; color: #1f2937; margin: 0;">Pilih Data Laporan</h2>
            </div>
            <div style="padding: 1.5rem;">
                <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1rem;">Silakan pilih bagian laporan yang ingin Anda pratinjau:</p>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @php
                        $anyValid = collect($this->checklistStatus)->contains(true);
                        $checked = 'checked';
                    @endphp

                    @if ($anyValid)
                        <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 6px; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
                            <input type="radio" name="preview_group" value="all" {{ $checked }} style="width: 1.125rem; height: 1.125rem; accent-color: #3b82f6;">
                            <span style="font-size: 0.875rem; font-weight: 500; color: #1f2937;">Seluruh Data Laporan</span>
                        </label>
                        @php $checked = ''; @endphp
                    @endif

                    @foreach ($this->groups as $groupLabel => $items)
                        @php
                            $isGroupValid = collect($items)->every(fn($key) => $this->checklistStatus[$key] ?? false);
                        @endphp
                        @if ($isGroupValid)
                            <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 6px; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
                                <input type="radio" name="preview_group" value="{{ $groupLabel }}" {{ $checked }} style="width: 1.125rem; height: 1.125rem; accent-color: #3b82f6;">
                                <span style="font-size: 0.875rem; font-weight: 500; color: #1f2937;">{{ $groupLabel }}</span>
                            </label>
                            @php $checked = ''; @endphp
                        @endif
                    @endforeach
                </div>
            </div>
            <div style="border-top: 1px solid #e5e7eb; padding: 1.25rem; background-color: #f9fafb; display: flex; gap: 0.75rem; border-radius: 0 0 8px 8px;">
                <button type="button" id="confirmPreviewBtn" style="flex: 1; background-color: #3b82f6; color: white; padding: 0.625rem; border-radius: 6px; border: none; font-weight: 600; cursor: pointer;">Tampilkan</button>
                <button type="button" id="cancelSelectionBtn" style="flex: 1; background-color: #e5e7eb; color: #374151; padding: 0.625rem; border-radius: 6px; border: none; font-weight: 600; cursor: pointer;">Batal</button>
            </div>
        </div>
    </div>

    <div id="preview-modal" role="dialog"
        style="display:none; position:fixed; inset:0; z-index:60; align-items:center; justify-content:center; background-color: rgba(0,0,0,0.5);">

        <div
            style="background-color:white; border-radius:8px; width:100%; max-width:60rem; max-height:90vh; overflow:hidden; display:flex; flex-direction:column;">

            <!-- Header -->
            <div
                style="padding:1rem 1.5rem; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center;">
                <h3 style="font-weight:600; margin:0;">Pratinjau Laporan Bulanan</h3>
                <button type="button" id="preview-modal-close"
                    style="border:none;background:none;cursor:pointer;font-size:1.5rem; color:#9ca3af; line-height:1;">✕</button>
            </div>

            <!-- Tabs Header -->
            <div class="tabs-header " style="margin-top: 1rem;">
                <button class="tab-btn active" data-tab="tab-data">Data</button>
                <button class="tab-btn" data-tab="tab-grafik">Grafik</button>
            </div>

            <!-- Body -->
            <div id="tab-data" class="tab-content active" style="overflow:auto; flex: 1;">
                <div id="preview-modal-body" style="padding:0 1.5rem 1.5rem 1.5rem;"></div>
            </div>

            <div id="tab-grafik" class="tab-content" style="overflow:auto; flex: 1;">
                <div class="charts-grid">
                    <!-- Keadaan Siswa Section -->
                    <div class="chart-section" data-section="Keadaan Siswa" style="grid-column: 1 / -1; margin-top: 1rem;">
                        <h3 style="font-weight: bold; font-size: 1rem; color: #1f2937; margin-bottom: 1rem; border-left: 4px solid #f97316; padding-left: 0.5rem;">Keadaan Siswa</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                            <div class="chart-card"><h4>Siswa by Rombel</h4><div class="chart-container"><canvas id="chart-siswa-rombel"></canvas></div></div>
                            <div class="chart-card"><h4>Siswa by Umur</h4><div class="chart-container"><canvas id="chart-siswa-umur"></canvas></div></div>
                            <div class="chart-card"><h4>Siswa by Agama</h4><div class="chart-container"><canvas id="chart-siswa-agama"></canvas></div></div>
                            <div class="chart-card"><h4>Siswa by Daerah</h4><div class="chart-container"><canvas id="chart-siswa-daerah"></canvas></div></div>
                            <div class="chart-card"><h4>Siswa Disabilitas</h4><div class="chart-container"><canvas id="chart-siswa-disabilitas"></canvas></div></div>
                            <div class="chart-card"><h4>Siswa Beasiswa</h4><div class="chart-container"><canvas id="chart-siswa-beasiswa"></canvas></div></div>
                        </div>
                    </div>

                    <!-- Keadaan GTK Section -->
                    <div class="chart-section" data-section="Keadaan GTK" style="grid-column: 1 / -1; margin-top: 2rem;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                            <div class="chart-card"><h4>Pie GTK by Umur</h4><div class="chart-container"><canvas id="chart-gtk-umur-pie"></canvas></div></div>
                            <div class="chart-card"><h4>Pie GTK by Daerah Asal</h4><div class="chart-container"><canvas id="chart-gtk-daerah-asal"></canvas></div></div>
                            <div class="chart-card"><h4>GTK by Status Kepegawaian</h4><div class="chart-container"><canvas id="chart-gtk-status"></canvas></div></div>
                            <div class="chart-card"><h4>GTK by Umur</h4><div class="chart-container"><canvas id="chart-gtk-umur-bar"></canvas></div></div>
                            <div class="chart-card"><h4>GTK by Pendidikan</h4><div class="chart-container"><canvas id="chart-gtk-pendidikan"></canvas></div></div>
                        </div>
                    </div>

                    <!-- Kondisi Gedung/Ruang Section -->
                    <div class="chart-section" data-section="Kondisi Gedung/Ruang" style="grid-column: 1 / -1; margin-top: 2rem;">
                        <h3 style="font-weight: bold; font-size: 1rem; color: #1f2937; margin-bottom: 1rem; border-left: 4px solid #ef4444; padding-left: 0.5rem;">Kondisi Gedung/Ruang</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                            <div class="chart-card"><h4>Gedung Rusak dan Baik</h4><div class="chart-container"><canvas id="chart-sarpras-kondisi"></canvas></div></div>
                        </div>
                    </div>

                    <!-- Kehadiran GTK Section -->
                    <div class="chart-section" data-section="Kehadiran GTK" style="grid-column: 1 / -1; margin-top: 2rem;">
                        <h3 style="font-weight: bold; font-size: 1rem; color: #1f2937; margin-bottom: 1rem; border-left: 4px solid #ec4899; padding-left: 0.5rem;">Kehadiran GTK</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                            <div class="chart-card"><h4>Perbandingan Sakit, Izin, dan Alpa</h4><div class="chart-container"><canvas id="chart-kehadiran-rekap"></canvas></div></div>
                        </div>
                    </div>

                    <!-- Kelulusan Section -->
                    <div class="chart-section" data-section="Kelulusan" style="grid-column: 1 / -1; margin-top: 2rem; margin-bottom: 2rem;">
                        <h3 style="font-weight: bold; font-size: 1rem; color: #1f2937; margin-bottom: 1rem; border-left: 4px solid #8b5cf6; padding-left: 0.5rem;">Kelulusan</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                            <div class="chart-card"><h4>Persentase Kelulusan 5 Tahun Terakhir</h4><div class="chart-container"><canvas id="chart-kelulusan-tren"></canvas></div></div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Footer -->
            <div style="padding:1rem; border-top:1px solid #e5e7eb; display:flex; gap:1rem;">
                <button type="button" id="preview-modal-pdf"
                    style="flex:1; background:#ef4444; color:white; padding:0.75rem; border:none; border-radius:6px; font-weight:500; cursor:pointer; transition:background-color 0.2s;">
                    Cetak / Simpan PDF
                </button>
                <button type="button" id="preview-modal-close-2"
                    style="flex:1; background:#e5e7eb; padding:0.75rem; border:none; border-radius:6px; font-weight:500; cursor:pointer; transition:background-color 0.2s;">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    {{-- Area cetak tersembunyi, hanya tampil saat print --}}
    <div id="print-area"
        data-sekolah="{{ auth()->user()->sekolah?->nama ?? '-' }}"
        data-npsn="{{ auth()->user()->sekolah?->npsn ?? '-' }}"
        data-alamat="{{ auth()->user()->sekolah?->alamat ?? '-' }}"
        data-desa="{{ auth()->user()->sekolah?->desa ?? '-' }}"
        data-kecamatan="{{ auth()->user()->sekolah?->kecamatan ?? '-' }}"
        data-kabupaten="{{ auth()->user()->sekolah?->kabupaten ?? '-' }}"
        data-bulan="{{ \Carbon\Carbon::now()->translatedFormat('F') }}"
        data-tahun="{{ date('Y') }}"
        data-logo-left="{{ asset('assets/logo/logo-bintuni.png') }}"
        data-logo-right="{{ asset('assets/logo/tut-wuri-handayani.png') }}"
    ></div>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        const totalItems = {{ count($this->checklist) }};
        const checkboxes = document.querySelectorAll('.report-checkbox');

        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        const modalCloseButtons = document.querySelectorAll('.modal-close');
        const previewBtn = document.getElementById('previewBtn');
        const submitBtn = document.getElementById('submitBtn');

        // update progress
        function updateProgress() {
            const checkedCount = document.querySelectorAll('.report-checkbox:checked').length;
            const percentage = Math.round((checkedCount / totalItems) * 100);

            progressBar.style.width = percentage + '%';
            progressText.textContent = percentage + '%';
        }

        // Remove manual change listeners as they are disabled

        // close modal lama
        modalCloseButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const key = this.getAttribute('data-key');
                const modal = document.getElementById('modal-' + key);
                if (modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        });

        // close klik luar
        document.querySelectorAll('[role="dialog"]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                // Jangan tutup pemilihan data laporan jika klik luar
                if (e.target === this && this.id !== 'selection-modal') {
                    this.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        });

        // PREVIEW GABUNGAN
        const selectionModal = document.getElementById('selection-modal');
        const confirmPreviewBtn = document.getElementById('confirmPreviewBtn');
        const cancelSelectionBtn = document.getElementById('cancelSelectionBtn');

        const groupMapping = {
            @foreach ($this->groups as $groupLabel => $items)
                "{{ $groupLabel }}": {!! json_encode($items) !!},
            @endforeach
        };

        // Judul laporan per grup
        const groupTitleMap = {
            'all': 'LAPORAN BULANAN LENGKAP',
            'Keadaan Siswa': 'LAPORAN BULANAN - REKAP DATA SISWA',
            'Keadaan GTK': 'LAPORAN BULANAN - REKAP DATA GURU & TENAGA KEPENDIDIKAN',
            'Kondisi Gedung/Ruang': 'LAPORAN BULANAN - REKAP GEDUNG/RUANG',
            'Sebaran Jam Mengajar': 'LAPORAN BULANAN - SEBARAN JAM MENGAJAR',
            'Kehadiran GTK': 'LAPORAN BULANAN - REKAP KEHADIRAN GTK',
            'Kelulusan': 'LAPORAN BULANAN - DATA KELULUSAN',
            'Identitas Sekolah': 'LAPORAN BULANAN - IDENTITAS SEKOLAH',
        };

        // Simpan label grup yang dipilih agar bisa diakses oleh print listener
        let currentSelectedGroupLabel = 'LAPORAN BULANAN';

        previewBtn.addEventListener('click', function() {
            const hasRadioButtons = selectionModal.querySelectorAll('input[name="preview_group"]').length > 0;
            if (!hasRadioButtons) {
                alert('Silakan pastikan data telah valid (tercentang) sebelum pratinjau');
                return;
            }
            selectionModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });

        cancelSelectionBtn.addEventListener('click', function() {
            selectionModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        confirmPreviewBtn.addEventListener('click', function() {
            const selectedGroup = document.querySelector('input[name="preview_group"]:checked').value;
            const checkedItems = document.querySelectorAll('.report-checkbox:checked');

            if (checkedItems.length === 0) {
                alert('Silakan pastikan data telah valid (tercentang) sebelum pratinjau');
                selectionModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                return;
            }

            const previewBody = document.getElementById('preview-modal-body');
            previewBody.innerHTML = '';

            let itemsToPreview = Array.from(checkedItems);

            if (selectedGroup !== 'all') {
                const allowedKeys = groupMapping[selectedGroup] || [];
                itemsToPreview = itemsToPreview.filter(cb => allowedKeys.includes(cb.value));
            }

            if (itemsToPreview.length === 0) {
                alert('Tidak ada data yang valid untuk bagian ' + selectedGroup);
                return;
            }

            itemsToPreview.forEach(checkbox => {
                const key = checkbox.value;
                const modal = document.getElementById('modal-' + key);

                const sectionColors = {
                    identitas_sekolah: '#dbeafe',
                    kondisi_sarpras: '#fee2e2',
                    sebaran_jam: '#cffafe',
                    rekap_kehadiran: '#fce7f3',
                    kelulusan: '#ede9fe',
                };

                if (modal) {
                    const title = modal.querySelector('h2').innerHTML;
                    const contentElement = modal.querySelector('.report-modal-content-wrapper');
                    const content = contentElement ? contentElement.innerHTML : 'Data tidak dapat dimuat';

                    const bgColor = sectionColors[key] || '#f3f4f6';

                    previewBody.innerHTML += `
                        <div style="margin-bottom:1.5rem;">
                            <div style="background:${bgColor}; padding:0.5rem 0.75rem; font-weight:600; border: 1px solid #e5e7eb; border-bottom: none;">
                                ${title}
                            </div>
                            <div>
                                ${content}
                            </div>
                        </div>
                    `;
                }
            });

            selectionModal.style.display = 'none';

            // Simpan judul laporan berdasarkan grup yang dipilih
            currentSelectedGroupLabel = groupTitleMap[selectedGroup] || 'LAPORAN BULANAN';

            document.getElementById('preview-modal').style.display = 'flex';
        });

        // Cetak / Simpan PDF menggunakan print area native (no external library)
        document.getElementById('preview-modal-pdf').addEventListener('click', function() {
            const activeTab = document.querySelector('.tab-btn.active').getAttribute('data-tab');
            const printArea = document.getElementById('print-area');

            // Ambil data sekolah dari data attributes
            const namaSekolah = printArea.dataset.sekolah;
            const npsn = printArea.dataset.npsn;
            const alamat = printArea.dataset.alamat;
            const desa = printArea.dataset.desa;
            const kecamatan = printArea.dataset.kecamatan;
            const kabupaten = printArea.dataset.kabupaten;
            const bulan = printArea.dataset.bulan;
            const tahun = printArea.dataset.tahun;
            const logoLeft = printArea.dataset.logoLeft;
            const logoRight = printArea.dataset.logoRight;

            // Bangun header resmi dengan logo kiri dan kanan
            const header =
                '<div style="display:table; width:100%; border-bottom: 3px solid #000; padding-bottom: 12px; margin-bottom: 16px;">' +
                    '<div style="display:table-cell; width:80px; vertical-align:middle; text-align:left;">' +
                        '<img src="' + logoLeft + '" style="height:70px; width:auto; display:block;" />' +
                    '</div>' +
                    '<div style="display:table-cell; vertical-align:middle; text-align:center; padding: 0 10px;">' +
                        '<p style="font-size:11px; margin:0 0 1px 0; font-weight:normal;">PEMERINTAH KABUPATEN TELUK BINTUNI</p>' +
                        '<p style="font-size:11px; margin:0 0 1px 0; font-weight:normal;">DINAS PENDIDIKAN</p>' +
                        '<h1 style="font-size:17px; margin:0 0 1px 0; text-transform:uppercase; font-weight:bold;">' + namaSekolah + '</h1>' +
                        '<p style="font-size:9px; margin:0;">NPSN: ' + npsn + ' | ' + alamat + ', ' + desa + ', Kec. ' + kecamatan + ', ' + kabupaten + '</p>' +
                    '</div>' +
                    '<div style="display:table-cell; width:80px; vertical-align:middle; text-align:right;">' +
                        '<img src="' + logoRight + '" style="height:70px; width:auto; display:block; margin-left:auto;" />' +
                    '</div>' +
                '</div>' +
                '<div style="text-align:center; margin-bottom: 16px;">' +
                    '<h2 style="font-size:13px; font-weight:bold; text-transform:uppercase; margin:0 0 3px 0; text-decoration:underline;">' + currentSelectedGroupLabel + '</h2>' +
                    '<p style="font-size:10px; margin:0;">Bulan: <strong>' + bulan + ' ' + tahun + '</strong></p>' +
                '</div>';

            let printContent = '';

            if (activeTab === 'tab-data') {
                const element = document.getElementById('preview-modal-body');
                const sections = element.children;
                if (sections.length > 0) {
                    for (let i = 0; i < sections.length; i++) {
                        const section = sections[i];
                        const titleEl = section.children[0];
                        const contentEl = section.children[1];
                        if (titleEl && contentEl) {
                            printContent +=
                                '<div style="margin-bottom: 16px;">' +
                                    contentEl.innerHTML +
                                '</div>';
                        } else {
                            printContent += section.innerHTML;
                        }
                    }
                } else {
                    printContent = element.innerHTML;
                }
            } else {
                // EXPORT CHARTS
                const chartSections = document.querySelectorAll('.chart-section');
                chartSections.forEach(section => {
                    if (section.style.display !== 'none') {
                        const sectionTitle = section.querySelector('h3').innerText;
                        printContent += `<h3 style="font-size:12px; font-weight:bold; border-bottom:1px solid #ccc; padding-bottom:4px; margin-bottom:10px; margin-top:20px;">${sectionTitle}</h3>`;
                        
                        const canvases = section.querySelectorAll('canvas');
                        printContent += '<div style="display:flex; flex-wrap:wrap; gap:20px;">';
                        canvases.forEach(canvas => {
                            const chartCard = canvas.closest('.chart-card');
                            const chartTitle = chartCard.querySelector('h4').innerText;
                            const imageData = canvas.toDataURL('image/png');
                            
                            printContent += `
                                <div style="width: calc(50% - 10px); min-width:300px; margin-bottom:20px; page-break-inside:avoid;">
                                    <p style="font-size:10px; font-weight:bold; margin-bottom:5px;">${chartTitle}</p>
                                    <img src="${imageData}" style="width:100%; height:auto; border:1px solid #eee;" />
                                </div>
                            `;
                        });
                        printContent += '</div>';
                    }
                });
            }

            // Salin konten ke print area
            printArea.innerHTML = header + printContent;

            if (activeTab === 'tab-data') {
                // Strip inline padding/height dan terapkan rata tengah untuk kolom tertentu (hanya untuk tabel)
                printArea.querySelectorAll('td, th').forEach(function(cell) {
                    const isCentered = cell.style.textAlign === 'center';
                    cell.style.removeProperty('padding');
                    cell.style.removeProperty('padding-top');
                    cell.style.removeProperty('padding-bottom');
                    cell.style.removeProperty('padding-left');
                    cell.style.removeProperty('padding-right');
                    cell.style.removeProperty('height');
                    cell.style.removeProperty('min-height');
                    cell.style.removeProperty('line-height');

                    if (isCentered) {
                        cell.classList.add('text-center');
                    }
                });
            }

            // Pindahkan ke body root agar CSS @media print bekerja dengan benar
            const originalParent = printArea.parentNode;
            document.body.appendChild(printArea);

            // Tunggu semua gambar selesai loading sebelum print (fix logo tidak muncul)
            const images = printArea.querySelectorAll('img');
            const imagePromises = Array.from(images).map(function(img) {
                return new Promise(function(resolve) {
                    if (img.complete && img.naturalWidth > 0) {
                        resolve();
                    } else {
                        img.onload = resolve;
                        img.onerror = resolve; // tetap lanjut walau gambar gagal
                    }
                });
            });

            Promise.all(imagePromises).then(function() {
                // Simpan judul asli
                const originalTitle = document.title;
                
                // Set judul baru untuk nama file PDF
                document.title = currentSelectedGroupLabel + ' - ' + namaSekolah;

                window.print();

                // Kembalikan judul asli dan posisi setelah print
                setTimeout(function() {
                    document.title = originalTitle;
                    originalParent.appendChild(printArea);
                    printArea.innerHTML = '';
                }, 2000);
            });
        });

        // TABS AND CHARTS LOGIC
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        const chartData = @json($this->chartData);
        let chartInstances = {};

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');

                if (tabId === 'tab-grafik') {
                    renderCharts();
                }
            });
        });

        function renderCharts() {
            const selectedGroup = document.querySelector('input[name="preview_group"]:checked').value;
            const chartSections = document.querySelectorAll('.chart-section');

            chartSections.forEach(section => {
                const sectionName = section.getAttribute('data-section');
                if (selectedGroup === 'all' || selectedGroup === sectionName) {
                    section.style.display = 'block';
                    initSectionCharts(sectionName);
                } else {
                    section.style.display = 'none';
                }
            });
        }

        function initSectionCharts(sectionName) {
            const sectionData = chartData[sectionName];
            if (!sectionData) return;

            const colors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#3b82f6', '#ec4899', '#14b8a6', '#f97316'];

            if (sectionName === 'Keadaan Siswa') {
                createChart('chart-siswa-rombel', 'bar', sectionData.rombel, 'Siswa', colors[0]);
                createChart('chart-siswa-umur', 'line', sectionData.umur, 'Siswa', colors[1]);
                createChart('chart-siswa-agama', 'pie', sectionData.agama, 'Agama', colors);
                createChart('chart-siswa-daerah', 'pie', sectionData.daerah, 'Daerah', colors);
                createChart('chart-siswa-disabilitas', 'pie', sectionData.disabilitas, 'Disabilitas', colors);
                createChart('chart-siswa-beasiswa', 'bar', sectionData.beasiswa, 'Beasiswa', colors[2]);
            } else if (sectionName === 'Keadaan GTK') {
                createChart('chart-gtk-umur-pie', 'pie', sectionData.umur_pie, 'Umur', colors);
                createChart('chart-gtk-daerah-asal', 'pie', sectionData.daerah, 'Daerah', colors);
                createChart('chart-gtk-status', 'bar', sectionData.status, 'Status', colors[3]);
                createChart('chart-gtk-umur-bar', 'bar', sectionData.umur_bar, 'Umur', colors[4]);
                createChart('chart-gtk-pendidikan', 'bar', sectionData.pendidikan, 'Pendidikan', colors[5]);
            } else if (sectionName === 'Kondisi Gedung/Ruang') {
                createChart('chart-sarpras-kondisi', 'pie', sectionData.kondisi, 'Kondisi', [colors[1], colors[3]]);
            } else if (sectionName === 'Kehadiran GTK') {
                createChart('chart-kehadiran-rekap', 'pie', sectionData.rekap, 'Kehadiran', [colors[1], colors[2], colors[3]]);
            } else if (sectionName === 'Kelulusan') {
                createChart('chart-kelulusan-tren', 'line', sectionData.tren, 'Persentase (%)', colors[4]);
            }
        }

        function createChart(canvasId, type, data, label, color) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return;

            // Destroy existing chart if it exists
            if (chartInstances[canvasId]) {
                chartInstances[canvasId].destroy();
            }

            const labels = Object.keys(data);
            const values = Object.values(data);

            const isPie = type === 'pie' || type === 'doughnut';

            chartInstances[canvasId] = new Chart(ctx, {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: values,
                        backgroundColor: isPie ? color : (Array.isArray(color) ? color[0] : color),
                        borderColor: isPie ? '#fff' : (Array.isArray(color) ? color[0] : color),
                        borderWidth: 1,
                        tension: 0.3,
                        fill: type === 'line' ? false : true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: isPie,
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                font: { size: 10 }
                            }
                        }
                    },
                    scales: isPie ? {} : {
                        y: {
                            beginAtZero: true,
                            ticks: { font: { size: 10 } }
                        },
                        x: {
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        }

        // close preview modal
        document.getElementById('preview-modal-close').addEventListener('click', closePreview);
        document.getElementById('preview-modal-close-2').addEventListener('click', closePreview);

        function closePreview() {
            // Reset tabs
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            tabBtns[0].classList.add('active');
            tabContents[0].classList.add('active');

            // Destroy all charts
            Object.values(chartInstances).forEach(chart => chart.destroy());
            chartInstances = {};

            document.getElementById('preview-modal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }


        // submit
        submitBtn.addEventListener('click', function() {
            const checkedItems = document.querySelectorAll('.report-checkbox:checked');

            if (checkedItems.length !== totalItems) {
                alert('Semua item harus dicentang sebelum mengirim laporan');
                return;
            }

            alert('Laporan berhasil dikirim!');
        });

        updateProgress();

    });
</script>

</x-filament-panels::page>
