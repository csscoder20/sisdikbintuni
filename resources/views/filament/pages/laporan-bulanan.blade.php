<x-filament-panels::page>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
                color: #000 !important;
                background: white;
            }
            /* Force all text to black in print area */
            #print-area *:not(canvas) {
                color: #000 !important;
            }
            #print-area table { width: 100%; border-collapse: collapse !important; margin-bottom: 10px; border: 1px solid #000 !important; }
            #print-area th, #print-area td { border: 1px solid #000 !important; padding: 4px !important; text-align: left !important; font-size: 9px !important; line-height: normal !important; vertical-align: middle !important; color: #000 !important; }
            #print-area th.text-center, #print-area td.text-center { text-align: center !important; }
            #print-area th { background-color: #e8e8e8 !important; font-weight: bold !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            #print-area h2 { font-size: 13px; margin: 0 0 6px 0; color: #000 !important; }
            #print-area .print-section { border: 1px solid #000; margin-bottom: 20px; page-break-inside: avoid; }
            #print-area .print-section-title { background: #e8e8e8 !important; padding: 6px 10px; font-weight: bold; border-bottom: 1px solid #000; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
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

        .chart-container {
            display: none;
        }


        @media print {
            @page {
                margin: 5mm;
            }
            @page portrait {
                size: portrait;
            }
            @page landscape {
                size: landscape;
            }
            .portrait {
                page: portrait;
            }
            .landscape {
                page: landscape;
                zoom: 0.85; /* Shrink to fit wider tables */
            }

            body {
                background: white !important;
            }

            .print-page {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Zoom for landscape tables to ensure they fit */
            .landscape table {
                font-size: 7pt !important;
            }
            
            /* Fix table layout for print */
            table {
                width: 100% !important;
                table-layout: auto !important;
                border-collapse: collapse !important;
                border: 1px solid #000 !important;
            }
            
            th, td {
                word-wrap: break-word !important;
                padding: 2px !important; /* Tighten padding */
                border: 1px solid #000 !important;
            }
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
                        {{ \App\Models\Gtk::where('sekolah_id', $this->getSchoolId())->count() }}
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
                        {{ \App\Models\Siswa::where('sekolah_id', $this->getSchoolId())->count() }}
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
                        {{ \App\Models\Rombel::where('sekolah_id', $this->getSchoolId())->count() }}
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
                            $query->where('sekolah_id', $this->getSchoolId());
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
        @if (!$this->isPreview)
            <button type="button" id="submitBtn"
                style="flex: 1; min-width: 200px; background-color: #10b981; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; border: none; font-weight: 500; cursor: pointer; transition: background-color 0.2s; font-size: 0.75rem;">
                KIRIMI LAPORAN BULANAN
            </button>
        @endif
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
                            @if (isset($previewData['type']) && $previewData['type'] === 'rekap_siswa_matrix')
                                @php
                                    $data = $previewData['data'];
                                    $perKelas = $data['perKelas'];
                                    $perUmur = $data['perUmur'];
                                    $perAgama = $data['perAgama'];
                                    $perDaerah = $data['perDaerah'];
                                    $disabilitas = $data['disabilitas'];
                                    $beasiswa = $data['beasiswa'];
                                @endphp

                                {{-- 1. Siswa per Rombel --}}
                                <div class="matrix-table-wrapper" data-title="JUMLAH SISWA BERDASARKAN KELAS / ROMBEL" style="margin-bottom: 3rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                                    <div style="overflow-x: auto; position: relative;">
                                        <table style="width: 100%; border-collapse: collapse; font-size: 0.75rem; text-align: center; border: 1px solid #cbd5e1; table-layout: fixed; min-width: 1000px;">
                                            <thead>
                                                <tr style="background-color: #f8fafc; white-space: nowrap;">
                                                    <th rowspan="2" style="border: 1px solid #cbd5e1; padding: 10px 4px; width: 40px; background: #f8fafc; position: sticky; left: 0; z-index: 20; white-space: nowrap;">NO</th>
                                                    <th rowspan="2" style="border: 1px solid #cbd5e1; padding: 10px 8px; text-align: left; width: 160px; background: #f8fafc; position: sticky; left: 40px; z-index: 20; border-right: 2px solid #cbd5e1; white-space: nowrap;">KELAS / ROMBEL</th>
                                                    <th colspan="3" style="border: 1px solid #cbd5e1; padding: 8px 4px; white-space: nowrap;">AWAL BULAN</th>
                                                    <th colspan="3" style="border: 1px solid #cbd5e1; padding: 8px 4px; white-space: nowrap;">MUTASI MASUK</th>
                                                    <th colspan="3" style="border: 1px solid #cbd5e1; padding: 8px 4px; white-space: nowrap;">MUTASI KELUAR</th>
                                                    <th colspan="3" style="border: 1px solid #cbd5e1; padding: 8px 4px; white-space: nowrap;">PUTUS SEKOLAH</th>
                                                    <th colspan="3" style="border: 1px solid #cbd5e1; padding: 8px 4px; white-space: nowrap;">MENGULANG</th>
                                                    <th colspan="3" style="border: 1px solid #cbd5e1; padding: 8px 4px; white-space: nowrap;">AKHIR BULAN</th>
                                                </tr>
                                                <tr style="background-color: #f8fafc; white-space: nowrap;">
                                                    @for ($i = 0; $i < 6; $i++)
                                                        <th style="border: 1px solid #cbd5e1; padding: 4px; width: 35px; white-space: nowrap;">L</th>
                                                        <th style="border: 1px solid #cbd5e1; padding: 4px; width: 35px; white-space: nowrap;">P</th>
                                                        <th style="border: 1px solid #cbd5e1; padding: 4px; width: 45px; background-color: #f1f5f9; font-weight: 800; white-space: nowrap;">JML</th>
                                                    @endfor
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($perKelas as $index => $row)
                                                    <tr style="background: white;">
                                                        <td style="border: 1px solid #cbd5e1; padding: 10px 4px; background: #fff; position: sticky; left: 0; z-index: 10;">{{ $index + 1 }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 10px 8px; text-align: left; background: #fff; position: sticky; left: 40px; z-index: 10; font-weight: 600; border-right: 2px solid #cbd5e1;">{{ $row['nama_rombel'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['awal_bulan_l'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['awal_bulan_p'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px; font-weight: 700;">{{ $row['awal_bulan_jml'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['mutasi_l'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['mutasi_p'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px; font-weight: 700;">{{ $row['mutasi_jml'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['mutasi_keluar_l'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['mutasi_keluar_p'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px; font-weight: 700;">{{ $row['mutasi_keluar_jml'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['putus_sekolah_l'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['putus_sekolah_p'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px; font-weight: 700;">{{ $row['putus_sekolah_jml'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['mengulang_l'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['mengulang_p'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px; font-weight: 700;">{{ $row['mengulang_jml'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['akhir_bulan_l'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['akhir_bulan_p'] }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 8px 4px; font-weight: 800; background-color: #f8fafc;">{{ $row['akhir_bulan_jml'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr style="background-color: #f1f5f9; font-weight: 800;">
                                                    <td colspan="2" style="border: 1px solid #cbd5e1; padding: 12px 8px; text-align: right; background: #f1f5f9; position: sticky; left: 0; z-index: 15; border-right: 2px solid #cbd5e1;">TOTAL KESELURUHAN</td>
                                                    @php
                                                        $sums = ['awal_bulan_l', 'awal_bulan_p', 'awal_bulan_jml', 'mutasi_l', 'mutasi_p', 'mutasi_jml', 'mutasi_keluar_l', 'mutasi_keluar_p', 'mutasi_keluar_jml', 'putus_sekolah_l', 'putus_sekolah_p', 'putus_sekolah_jml', 'mengulang_l', 'mengulang_p', 'mengulang_jml', 'akhir_bulan_l', 'akhir_bulan_p', 'akhir_bulan_jml'];
                                                    @endphp
                                                    @foreach ($sums as $col)
                                                        <td style="border: 1px solid #cbd5e1; padding: 12px 4px;">{{ collect($perKelas)->sum($col) }}</td>
                                                    @endforeach
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="matrix-table-wrapper" data-title="JUMLAH SISWA MENURUT UMUR" style="margin-bottom: 3rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                                    <div style="overflow-x: auto; position: relative;">
                                        <table style="width: 100%; border-collapse: collapse; font-size: 0.7rem; text-align: center; border: 1px solid #cbd5e1; table-layout: fixed; min-width: 1400px;">
                                            <thead>
                                                <tr style="background-color: #f8fafc; white-space: nowrap;">
                                                    <th rowspan="2" style="border: 1px solid #cbd5e1; padding: 10px 4px; width: 40px; background: #f8fafc; position: sticky; left: 0; z-index: 20; white-space: nowrap;">NO</th>
                                                    <th rowspan="2" style="border: 1px solid #cbd5e1; padding: 10px 8px; text-align: left; width: 140px; background: #f8fafc; position: sticky; left: 40px; z-index: 20; border-right: 2px solid #cbd5e1; white-space: nowrap;">KELAS / ROMBEL</th>
                                                    @for ($age = 13; $age <= 23; $age++)
                                                        <th colspan="3" style="border: 1px solid #cbd5e1; padding: 8px 2px; white-space: nowrap;">{{ $age }} Thn</th>
                                                    @endfor
                                                </tr>
                                                <tr style="background-color: #f8fafc; white-space: nowrap;">
                                                    @for ($age = 13; $age <= 23; $age++)
                                                        <th style="border: 1px solid #cbd5e1; padding: 4px; width: 30px; white-space: nowrap;">L</th>
                                                        <th style="border: 1px solid #cbd5e1; padding: 4px; width: 30px; white-space: nowrap;">P</th>
                                                        <th style="border: 1px solid #cbd5e1; padding: 4px; width: 40px; background-color: #f1f5f9; font-weight: 800; white-space: nowrap;">J</th>
                                                    @endfor
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($perUmur as $index => $row)
                                                    <tr style="background: white;">
                                                        <td style="border: 1px solid #cbd5e1; padding: 10px 4px; background: #fff; position: sticky; left: 0; z-index: 10;">{{ $index + 1 }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 10px 8px; text-align: left; background: #fff; position: sticky; left: 40px; z-index: 10; font-weight: 600; border-right: 2px solid #cbd5e1;">{{ $row['nama_rombel'] }}</td>
                                                        @for ($age = 13; $age <= 23; $age++)
                                                            @php $px = 'umur_' . $age; @endphp
                                                            <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row[$px . '_l'] }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row[$px . '_p'] }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 8px 4px; font-weight: 700; background: #f8fafc;">{{ $row[$px . '_jml'] }}</td>
                                                        @endfor
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="matrix-table-wrapper" data-title="JUMLAH SISWA MENURUT AGAMA" style="margin-bottom: 3rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                                    <div style="overflow-x: auto; position: relative;">
                                        <table style="width: 100%; border-collapse: collapse; font-size: 0.75rem; text-align: center; border: 1px solid #cbd5e1; table-layout: fixed; min-width: 1000px;">
                                            <thead>
                                                <tr style="background-color: #f8fafc; white-space: nowrap;">
                                                    <th rowspan="2" style="border: 1px solid #cbd5e1; padding: 12px 4px; width: 40px; background: #f8fafc; position: sticky; left: 0; z-index: 20; white-space: nowrap;">NO</th>
                                                    <th rowspan="2" style="border: 1px solid #cbd5e1; padding: 12px 8px; text-align: left; width: 160px; background: #f8fafc; position: sticky; left: 40px; z-index: 20; border-right: 2px solid #cbd5e1; white-space: nowrap;">KELAS / ROMBEL</th>
                                                    @foreach (['ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDDHA', 'KHONGHUCU'] as $ag)
                                                        <th colspan="3" style="border: 1px solid #cbd5e1; padding: 8px 4px; white-space: nowrap;">{{ $ag }}</th>
                                                    @endforeach
                                                </tr>
                                                <tr style="background-color: #f8fafc; white-space: nowrap;">
                                                    @for ($i = 0; $i < 6; $i++)
                                                        <th style="border: 1px solid #cbd5e1; padding: 4px; width: 35px; white-space: nowrap;">L</th>
                                                        <th style="border: 1px solid #cbd5e1; padding: 4px; width: 35px; white-space: nowrap;">P</th>
                                                        <th style="border: 1px solid #cbd5e1; padding: 4px; width: 45px; background-color: #f1f5f9; font-weight: 800; white-space: nowrap;">J</th>
                                                    @endfor
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($perAgama as $index => $row)
                                                    <tr style="background: white;">
                                                        <td style="border: 1px solid #cbd5e1; padding: 12px 4px; background: #fff; position: sticky; left: 0; z-index: 10;">{{ $index + 1 }}</td>
                                                        <td style="border: 1px solid #cbd5e1; padding: 12px 8px; text-align: left; background: #fff; position: sticky; left: 40px; z-index: 10; font-weight: 600; border-right: 2px solid #cbd5e1;">{{ $row['nama_rombel'] }}</td>
                                                        @foreach (['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'khonghucu'] as $ag)
                                                            <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row[$ag . '_l'] }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row[$ag . '_p'] }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 8px 4px; font-weight: 700; background: #f8fafc;">{{ $row[$ag . '_jml'] }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                                    {{-- 4. Daerah Asal --}}
                                    <div class="matrix-table-wrapper" data-title="SISWA PER DAERAH ASAL" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 3rem;">
                                        <div style="overflow-x: auto; position: relative;">
                                            <table style="width: 100%; border-collapse: collapse; font-size: 0.75rem; text-align: center; border: 1px solid #cbd5e1; table-layout: fixed; min-width: 600px;">
                                                <thead>
                                                    <tr style="background-color: #f8fafc;">
                                                        <th rowspan="2" style="border: 1px solid #cbd5e1; padding: 12px 4px; width: 40px; background: #f8fafc; position: sticky; left: 0; z-index: 20;">NO</th>
                                                        <th rowspan="2" style="border: 1px solid #cbd5e1; padding: 12px 8px; text-align: left; width: 160px; background: #f8fafc; position: sticky; left: 40px; z-index: 20; border-right: 2px solid #cbd5e1;">ROMBEL</th>
                                                        <th colspan="3" style="border: 1px solid #cbd5e1; padding: 8px 4px;">PAPUA</th>
                                                        <th colspan="3" style="border: 1px solid #cbd5e1; padding: 8px 4px;">NON-PAPUA</th>
                                                    </tr>
                                                    <tr style="background-color: #f8fafc;">
                                                        @for ($i = 0; $i < 2; $i++)
                                                            <th style="border: 1px solid #cbd5e1; padding: 4px; width: 35px;">L</th>
                                                            <th style="border: 1px solid #cbd5e1; padding: 4px; width: 35px;">P</th>
                                                            <th style="border: 1px solid #cbd5e1; padding: 4px; width: 45px; background-color: #f1f5f9; font-weight: 800;">J</th>
                                                        @endfor
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($perDaerah as $index => $row)
                                                        <tr style="background: white;">
                                                            <td style="border: 1px solid #cbd5e1; padding: 10px 4px; background: #fff; position: sticky; left: 0; z-index: 10;">{{ $index + 1 }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 10px 8px; text-align: left; background: #fff; position: sticky; left: 40px; z-index: 10; font-weight: 600; border-right: 2px solid #cbd5e1;">{{ $row['nama_rombel'] }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['papua_l'] }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['papua_p'] }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 8px 4px; font-weight: 700;">{{ $row['papua_jml'] }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['non_papua_l'] }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row['non_papua_p'] }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 8px 4px; font-weight: 700;">{{ $row['non_papua_jml'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- 5. Disabilitas --}}
                                    <div class="matrix-table-wrapper" data-title="SISWA DISABILITAS" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 3rem;">
                                        <div style="overflow-x: auto;">
                                            <table style="width: 100%; border-collapse: collapse; font-size: 0.75rem; text-align: center; border: 1px solid #cbd5e1;">
                                                <thead>
                                                    <tr style="background-color: #f8fafc;">
                                                        <th style="border: 1px solid #cbd5e1; padding: 12px 8px; width: 60px;">NO</th>
                                                        <th style="border: 1px solid #cbd5e1; padding: 12px 8px; text-align: left;">JENIS DISABILITAS</th>
                                                        <th style="border: 1px solid #cbd5e1; padding: 12px 8px; width: 80px;">L</th>
                                                        <th style="border: 1px solid #cbd5e1; padding: 12px 8px; width: 80px;">P</th>
                                                        <th style="border: 1px solid #cbd5e1; padding: 12px 8px; width: 100px; background-color: #f1f5f9; font-weight: 800;">JUMLAH</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($disabilitas as $index => $row)
                                                        <tr style="background: white;">
                                                            <td style="border: 1px solid #cbd5e1; padding: 10px 8px;">{{ $index + 1 }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 10px 8px; text-align: left; font-weight: 600;">{{ $row['jenis_disabilitas'] }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 10px 8px;">{{ $row['laki_laki'] }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 10px 8px;">{{ $row['perempuan'] }}</td>
                                                            <td style="border: 1px solid #cbd5e1; padding: 10px 8px; font-weight: 700; background: #f8fafc;">{{ $row['total'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @elseif (isset($previewData['type']) && $previewData['type'] === 'rekap_gtk_matrix')
                                @php
                                    $data = $previewData['data'];
                                @endphp
                                {{-- Rendering GTK Matrix tables here (Agama, Daerah, Status, Umur, Pendidikan) --}}
                                <div style="display: flex; flex-direction: column; gap: 2.5rem;">
                                    @foreach(['Agama' => 'agama', 'Daerah' => 'daerah', 'Umur' => 'umur'] as $title => $key)
                                        <div class="matrix-table-wrapper" data-title="DATA GTK MENURUT {{ $title }}" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                                            <div style="overflow-x: auto; position: relative;">
                                                <table style="width: 100%; border-collapse: collapse; font-size: 0.75rem; text-align: center; table-layout: fixed; min-width: 800px;">
                                                    <thead>
                                                        <tr style="background-color: #f8fafc; white-space: nowrap;">
                                                            <th rowspan="2" style="border: 1px solid #cbd5e1; padding: 12px 4px; width: 40px; background: #f8fafc; position: sticky; left: 0; z-index: 20; white-space: nowrap;">NO</th>
                                                            <th rowspan="2" style="border: 1px solid #cbd5e1; padding: 12px 8px; width: 200px; background: #f8fafc; position: sticky; left: 40px; z-index: 20; border-right: 2px solid #cbd5e1; white-space: nowrap;">JENIS GTK</th>
                                                            @if($key === 'agama')
                                                                @foreach(['ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDHA', 'KONGHUCU'] as $h) <th colspan="3" style="border: 1px solid #cbd5e1; padding: 8px 4px; white-space: nowrap;">{{ $h }}</th> @endforeach
                                                            @elseif($key === 'daerah')
                                                                @foreach(['PAPUA', 'NON-PAPUA'] as $h) <th colspan="3" style="border: 1px solid #cbd5e1; padding: 8px 4px; white-space: nowrap;">{{ $h }}</th> @endforeach
                                                            @elseif($key === 'umur')
                                                                @foreach(['20-29', '30-39', '40-49', '50-59', '60+'] as $h) <th colspan="3" style="border: 1px solid #cbd5e1; padding: 8px 4px; white-space: nowrap;">{{ $h }}</th> @endforeach
                                                            @endif
                                                        </tr>
                                                        <tr style="background-color: #f8fafc; white-space: nowrap;">
                                                            @php $cols = ($key === 'agama' ? 6 : ($key === 'daerah' ? 2 : 5)); @endphp
                                                            @for($i=0;$i<$cols;$i++) 
                                                                <th style="border: 1px solid #cbd5e1; padding: 4px; width: 35px; font-weight: 700; white-space: nowrap;">L</th>
                                                                <th style="border: 1px solid #cbd5e1; padding: 4px; width: 35px; font-weight: 700; white-space: nowrap;">P</th>
                                                                <th style="border: 1px solid #cbd5e1; padding: 4px; width: 45px; font-weight: 800; background: #f1f5f9; white-space: nowrap;">JML</th> 
                                                            @endfor
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($data[$key] as $idx => $row)
                                                            <tr style="background: white; transition: background 0.2s;">
                                                                <td style="border: 1px solid #cbd5e1; padding: 12px 4px; background: #fff; position: sticky; left: 0; z-index: 10;">{{ $idx + 1 }}</td>
                                                                <td style="border: 1px solid #cbd5e1; padding: 12px 10px; text-align: left; font-weight: 600; color: #334155; position: sticky; left: 40px; z-index: 10; background: #fff; border-right: 2px solid #cbd5e1;">{{ $row->jenis_gtk }}</td>
                                                                @php
                                                                    $subs = ($key === 'agama' ? ['islam','kristen_protestan','katolik','hindu','budha','konghucu'] : ($key === 'daerah' ? ['papua','non_papua'] : ['umur_20_29','umur_30_39','umur_40_49','umur_50_59','umur_60_ke_atas']));
                                                                @endphp
                                                                @foreach($subs as $s)
                                                                    <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row->{$s.'_l'} }}</td>
                                                                    <td style="border: 1px solid #cbd5e1; padding: 8px 4px;">{{ $row->{$s.'_p'} }}</td>
                                                                    <td style="border: 1px solid #cbd5e1; padding: 8px 4px; font-weight: 700; background: #f8fafc; color: #1e293b;">{{ $row->{$s.'_jml'} }}</td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif (isset($previewData['type']) && $previewData['type'] === 'rekap_summary')
                                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                                    @foreach ($previewData['sections'] as $sectionTitle => $data)
                                        <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 4px; overflow: hidden;">
                                            <div style="padding: 0.6rem 0.75rem; border-bottom: 1px solid #e5e7eb; background: #f8fafc;">
                                                <h4 style="font-size: 0.9rem; font-weight: 800; text-transform: uppercase; color: #334155; margin: 0;">{{ $sectionTitle }}</h4>
                                            </div>
                                            <div style="overflow-x: auto;">
                                                <table style="width: 100%; border-collapse: collapse; font-size: 0.75rem; text-align: center; border: 1px solid #333;">
                                                    <thead>
                                                        <tr style="background-color: #f3f4f6;">
                                                            <th rowspan="2" style="border: 1px solid #333; padding: 4px; width: 40px;">NO</th>
                                                            <th rowspan="2" style="border: 1px solid #333; padding: 4px; text-align: left;">URAIAN</th>
                                                            <th colspan="3" style="border: 1px solid #333; padding: 4px;">JUMLAH</th>
                                                        </tr>
                                                        <tr style="background-color: #f3f4f6;">
                                                            <th style="border: 1px solid #333; padding: 4px; width: 40px;">L</th>
                                                            <th style="border: 1px solid #333; padding: 4px; width: 40px;">P</th>
                                                            <th style="border: 1px solid #333; padding: 4px; width: 50px; background-color: rgba(59, 130, 246, 0.1);">JML</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($data as $index => $row)
                                                            <tr>
                                                                <td style="border: 1px solid #333; padding: 4px;">{{ $index + 1 }}</td>
                                                                <td style="border: 1px solid #333; padding: 4px; text-align: left;">{{ $row['label'] }}</td>
                                                                <td style="border: 1px solid #333; padding: 4px;">{{ $row['l'] }}</td>
                                                                <td style="border: 1px solid #333; padding: 4px;">{{ $row['p'] }}</td>
                                                                <td style="border: 1px solid #333; padding: 4px; font-weight: bold; background-color: rgba(59, 130, 246, 0.05);">{{ $row['total'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif ($key === 'identitas_sekolah')
                                <div style="overflow-x: auto;">
                                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem; text-align: left; border: none;">
                                        <tbody>
                                            @foreach ($previewData as $index => $item)
                                                <tr style="{{ isset($item['is_header']) ? 'font-weight: bold;' : '' }}">
                                                    {{-- Kolom Nomor --}}
                                                    <td style="padding: 2px 4px; vertical-align: top; width: 30px; color: #374151;">
                                                        {{ !isset($item['is_sub']) ? ($index + 1) : '' }}
                                                    </td>
                                                    
                                                    {{-- Kolom Uraian --}}
                                                    <td style="padding: 2px 4px; vertical-align: top; width: 350px; color: #374151; {{ isset($item['is_sub']) ? 'padding-left: 1.5rem !important;' : '' }}">
                                                        {{ $item['label'] }}
                                                    </td>

                                                    {{-- Kolom Titik Dua --}}
                                                    <td style="padding: 2px 4px; vertical-align: top; width: 15px; color: #374151; text-align: center;">:</td>

                                                    {{-- Kolom Nilai/Data --}}
                                                    <td style="padding: 2px 4px; vertical-align: top; color: #111827; font-weight: normal;">
                                                        {{ $item['value'] }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @elseif ($key === 'kondisi_sarpras')
                                <div style="overflow-x: auto;">
                                    <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem; text-align: left; border: 1px solid #333;">
                                        <thead>
                                            <tr style="background-color: #f3f4f6;">
                                                <th rowspan="2" style="border: 1px solid #333; padding: 4px; text-align: center; width: 40px;">No</th>
                                                <th rowspan="2" style="border: 1px solid #333; padding: 4px; text-align: center;">Keadaan Fisik</th>
                                                <th rowspan="2" style="border: 1px solid #333; padding: 4px; text-align: center; width: 60px;">Jumlah</th>
                                                <th colspan="2" style="border: 1px solid #333; padding: 4px; text-align: center;">Tingkat Kerusakan</th>
                                                <th colspan="2" style="border: 1px solid #333; padding: 4px; text-align: center;">Status Kepemilikan</th>
                                            </tr>
                                            <tr style="background-color: #f3f4f6;">
                                                <th style="border: 1px solid #333; padding: 4px; text-align: center; width: 60px;">Baik</th>
                                                <th style="border: 1px solid #333; padding: 4px; text-align: center; width: 60px;">Rusak</th>
                                                <th style="border: 1px solid #333; padding: 4px; text-align: center; width: 80px;">Milik</th>
                                                <th style="border: 1px solid #333; padding: 4px; text-align: center; width: 80px;">Bukan Milik</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($previewData as $index => $item)
                                                <tr>
                                                    <td style="border: 1px solid #333; padding: 4px; text-align: center;">{{ $index + 1 }}</td>
                                                    <td style="border: 1px solid #333; padding: 4px;">{{ $item['label'] }}</td>
                                                    <td style="border: 1px solid #333; padding: 4px; text-align: center;">{{ $item['details']['Jumlah'] }}</td>
                                                    <td style="border: 1px solid #333; padding: 4px; text-align: center;">{{ $item['details']['Tingkat Kerusakan_Baik'] }}</td>
                                                    <td style="border: 1px solid #333; padding: 4px; text-align: center;">{{ $item['details']['Tingkat Kerusakan_Rusak'] }}</td>
                                                    <td style="border: 1px solid #333; padding: 4px; text-align: center;">{{ $item['details']['Status Kepemilikan_Milik'] }}</td>
                                                    <td style="border: 1px solid #333; padding: 4px; text-align: center;">{{ $item['details']['Status Kepemilikan_Bukan Milik'] }}</td>
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
                                                        // List of headers that should NOT be centered (usually labels/names)
                                                        $notCentered = ['Alamat', 'Keterangan'];
                                                        $isCentered = !in_array($header, $notCentered);
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
                                                                $notCentered = ['Alamat', 'Keterangan'];
                                                                $isCentered = !in_array($header, $notCentered);
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
 
    {{-- Selection Modal removed as per user request --}}
    <input type="hidden" id="preview_group_all" value="all">

    <div id="preview-modal" role="dialog"
        style="display:none; position:fixed; inset:0; z-index:60; align-items:center; justify-content:center; background-color: rgba(0,0,0,0.5);">

        <div
            style="background-color:white; border-radius:8px; width:100%; max-width:60rem; max-height:90vh; overflow:hidden; display:flex; flex-direction:column;">

            <!-- Header -->
            <div
                style="padding:1rem 1.5rem; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center; position: relative; z-index: 70; background: #fff;">
                <h3 id="preview-modal-title" style="font-weight:600; margin:0;">Pratinjau Laporan Bulanan</h3>
                <button type="button" id="preview-modal-close"
                    style="border:none;background:none;cursor:pointer;font-size:1.5rem; color:#9ca3af; line-height:1; padding: 5px;">✕</button>
            </div>



            <div id="tab-data" class="tab-content active" style="overflow:auto; flex: 1;">
                <div id="preview-modal-body" style="padding:1.5rem;"></div>
            </div>


            <!-- Footer -->
            <div style="padding:1rem; border-top:1px solid #e5e7eb; display:flex; gap:1rem; position: relative; z-index: 70; background: #f9fafb;">
                <button type="button" id="preview-modal-pdf"
                    style="flex:1; background:#ef4444; color:white; padding:0.75rem; border:none; border-radius:6px; font-weight:500; cursor:pointer; transition:background-color 0.2s; z-index: 80;">
                    Cetak / Simpan PDF
                </button>
                <button type="button" id="preview-modal-close-2"
                    style="flex:1; background:#e5e7eb; padding:0.75rem; border:none; border-radius:6px; font-weight:500; cursor:pointer; transition:background-color 0.2s; z-index: 80;">
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
        data-email="{{ auth()->user()->sekolah?->email ?? '-' }}"
        data-website="{{ auth()->user()->sekolah?->website ?? '-' }}"
        data-kodepos="{{ auth()->user()->sekolah?->kodepos ?? '-' }}"
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



        // PREVIEW GABUNGAN
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
            'Halaman 1': 'HALAMAN 1: IDENTITAS & SARPRAS',
            'Halaman 2': 'HALAMAN 2: REKAP SISWA & GTK',
            'Halaman 3': 'HALAMAN 3: NOMINATIF & PENDIDIKAN GTK',
            'Halaman 4': 'HALAMAN 4: NOMINATIF SISWA',
            'Halaman 5': 'HALAMAN 5: REKENING & NPWP',
            'Halaman 6': 'HALAMAN 6: SEBARAN JAM MENGAJAR',
            'Halaman 7': 'HALAMAN 7: ABSENSI',
            'Halaman 8': 'HALAMAN 8: KELULUSAN',
        };

        // Simpan label grup yang dipilih agar bisa diakses oleh print listener
        let currentSelectedGroupLabel = 'LAPORAN BULANAN';

        previewBtn.addEventListener('click', function() {
            const checkedItems = document.querySelectorAll('.report-checkbox:checked');
            if (checkedItems.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: '<span style="font-size: 1.125rem; font-weight: 600; color: #111827;">Perhatian</span>',
                    html: '<span style="font-size: 0.875rem; color: #4b5563;">Pastikan data telah valid (tercentang) sebelum pratinjau</span>',
                    confirmButtonColor: '#3b82f6',
                });
                return;
            }

            // Set title modal
            const printArea = document.getElementById('print-area');
            document.getElementById('preview-modal-title').textContent = 'Laporan Periode ' + printArea.dataset.bulan + ' ' + printArea.dataset.tahun;

            const previewBody = document.getElementById('preview-modal-body');
            previewBody.innerHTML = '';

            // Otomatis 'all' karena user tidak lagi memilih bagian
            const selectedGroup = 'all';
            let sectionCounter = 0;
            const itemsToPreview = Array.from(checkedItems);
            itemsToPreview.forEach((checkbox, index) => {
                const key = checkbox.value;
                const modal = document.getElementById('modal-' + key);

                if (modal) {
                    const contentElement = modal.querySelector('.report-modal-content-wrapper');
                    const matrixWrappers = contentElement.querySelectorAll('.matrix-table-wrapper');

                    if (matrixWrappers.length > 0) {
                        // Flatten matrix tables into individual sections
                        matrixWrappers.forEach((wrapper, wIdx) => {
                            const letter = String.fromCharCode(65 + sectionCounter++);
                            const title = wrapper.getAttribute('data-title') || 'DATA';
                            const mt = (index === 0 && wIdx === 0) ? '0' : '2rem';

                            previewBody.innerHTML += `
                                <div style="margin-top:${mt}; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; background: #fff;">
                                    <div style="background:#f8fafc; padding:0.75rem 1rem; border-bottom: 1px solid #e5e7eb;">
                                        <h3 style="margin:0; font-size:0.9rem; font-weight:800; color:#111827; text-transform:uppercase;">${letter}. ${title}</h3>
                                    </div>
                                    <div class="preview-content-box" style="padding:1.5rem;">
                                        ${wrapper.innerHTML}
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        // Normal section
                        const letter = String.fromCharCode(65 + sectionCounter++);
                        let title = modal.querySelector('h2').innerText;
                        const content = contentElement ? contentElement.innerHTML : 'Data tidak dapat muat';
                        const mt = index === 0 ? '0' : '2rem';

                        previewBody.innerHTML += `
                            <div style="margin-top:${mt}; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; background: #fff;">
                                <div style="background:#f8fafc; padding:0.75rem 1rem; border-bottom: 1px solid #e5e7eb;">
                                    <h3 style="margin:0; font-size:0.9rem; font-weight:800; color:#111827; text-transform:uppercase;">${letter}. ${title}</h3>
                                </div>
                                <div class="preview-content-box" style="padding:1.5rem;">
                                    ${content}
                                </div>
                            </div>
                        `;
                    }
                }
            });

            // Simpan judul laporan berdasarkan grup yang dipilih
            currentSelectedGroupLabel = groupTitleMap[selectedGroup] || 'LAPORAN BULANAN';

            document.getElementById('preview-modal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });

        // Attach close listeners
        document.getElementById('preview-modal-close').addEventListener('click', closePreview);
        document.getElementById('preview-modal-close-2').addEventListener('click', closePreview);

        // Cetak / Simpan PDF menggunakan print area native (no external library)
        document.getElementById('preview-modal-pdf').addEventListener('click', function() {
            const activeTab = 'tab-data';
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
            const headerTemplate = (title) =>
                '<div style="display:table; width:100%; border-bottom: 3px solid #000; padding-bottom: 12px; margin-bottom: 16px;">' +
                    '<div style="display:table-cell; width:80px; vertical-align:middle; text-align:left;">' +
                        '<img src="' + logoLeft + '" style="height:70px; width:auto; display:block;" />' +
                    '</div>' +
                    '<div style="display:table-cell; vertical-align:middle; text-align:center; padding: 0 10px;">' +
                        '<p style="font-size:11px; margin:0 0 1px 0; font-weight:normal;">PEMERINTAH KABUPATEN TELUK BINTUNI</p>' +
                        '<p style="font-size:11px; margin:0 0 1px 0; font-weight:normal;">DINAS PENDIDIKAN, KEBUDAYAAN, PEMUDA, DAN OLAHRAGA</p>' +
                        '<h1 style="font-size:17px; margin:0 0 1px 0; text-transform:uppercase; font-weight:bold;">' + namaSekolah + '</h1>' +
                        '<p style="font-size:9px; margin:0;">' + alamat + ' - ' + desa + ', ' + kecamatan + ', ' + kabupaten + ', Papua Barat</p>' +
                        '<p style="font-size:9px; margin:0; font-style:italic; color:#000; text-decoration:none;">email : ' + printArea.dataset.email + ' Website : ' + printArea.dataset.website + ' Kode Pos: ' + printArea.dataset.kodepos + '</p>' +
                    '</div>' +
                    '<div style="display:table-cell; width:80px; vertical-align:middle; text-align:right;">' +
                        '<img src="' + logoRight + '" style="height:70px; width:auto; display:block; margin-left:auto;" />' +
                    '</div>' +
                '</div>' +
                '<div style="text-align:center; margin-bottom: 16px;">' +
                    '<h2 style="font-size:13px; font-weight:bold; text-transform:uppercase; margin:0 0 3px 0; text-decoration:underline;">' + title + '</h2>' +
                    '<p style="font-size:10px; margin:0;">Bulan: <strong>' + bulan + ' ' + tahun + '</strong></p>' +
                '</div>';

            let printContent = '';
            const checkedCheckboxes = document.querySelectorAll('.report-checkbox:checked');
            const checkedItemsMap = {};
            checkedCheckboxes.forEach(cb => checkedItemsMap[cb.value] = true);

            if (activeTab === 'tab-data') {
                let sectionCounter = 0;
                const pages = Object.keys(groupMapping);
                const orientationMap = {
                    'Halaman 1': 'portrait',
                    'Halaman 2': 'landscape',
                    'Halaman 3': 'landscape',
                    'Halaman 4': 'landscape',
                    'Halaman 5': 'portrait',
                    'Halaman 6': 'landscape',
                    'Halaman 7': 'landscape',
                    'Halaman 8': 'portrait',
                };                const printedPages = [];                pages.forEach((pageKey) => {
                    const itemKeys = groupMapping[pageKey];
                    const orientation = orientationMap[pageKey] || 'portrait';
                    let pageHtml = '';
                    let hasDataInPage = false;
                    let isFirstInPage = true;

                    itemKeys.forEach(key => {
                        if (checkedItemsMap[key]) {
                            const modal = document.getElementById('modal-' + key);
                            if (modal) {
                                const contentElement = modal.querySelector('.report-modal-content-wrapper');
                                // Filter strictly for content
                                if (!contentElement || contentElement.innerText.trim() === '') return;

                                const matrixWrappers = contentElement.querySelectorAll('.matrix-table-wrapper');
                                const sectionTitle = modal.querySelector('h2').innerText;

                                if (matrixWrappers.length > 0) {
                                    matrixWrappers.forEach(wrapper => {
                                        const letter = String.fromCharCode(65 + sectionCounter++);
                                        const subTitle = wrapper.getAttribute('data-title') || 'DATA';
                                        const mt = isFirstInPage ? '0' : '2.5rem';
                                        isFirstInPage = false;
                                        hasDataInPage = true;
                                        
                                        pageHtml += `
                                            <div style="margin-top:${mt}; page-break-inside: avoid; clear: both;">
                                                <h3 style="font-size:10pt; font-weight:bold; margin-top: 0; margin-bottom:8px; text-transform:uppercase;">${letter}. ${subTitle}</h3>
                                                <div style="margin-bottom:10px;">
                                                    ${wrapper.innerHTML}
                                                </div>
                                            </div>
                                        `;
                                    });
                                } else {
                                    const letter = String.fromCharCode(65 + sectionCounter++);
                                    const mt = isFirstInPage ? '0' : '2.5rem';
                                    isFirstInPage = false;
                                    hasDataInPage = true;

                                    pageHtml += `
                                        <div style="margin-top:${mt}; clear: both;">
                                            <h3 style="font-size:10pt; font-weight:bold; margin-top: 0; margin-bottom:8px; text-transform:uppercase;">${letter}. ${sectionTitle}</h3>
                                            <div style="margin-bottom:10px;">
                                                ${contentElement.innerHTML}
                                            </div>
                                        </div>
                                    `;
                                }
                            }
                        }
                    });

                    if (hasDataInPage) {
                        printedPages.push({
                            label: pageKey,
                            orientation: orientation,
                            html: pageHtml
                        });
                    }
                });

                // Build final content with proper page breaks
                printedPages.forEach((page, pIdx) => {
                    const isLast = pIdx === printedPages.length - 1;
                    printContent += `
                        <div class="print-page ${page.orientation}" style="${isLast ? '' : 'page-break-after: always;'}">
                            ${headerTemplate('LAPORAN BULANAN - ' + page.label)}
                            ${page.html}
                        </div>
                    `;
                });
            } else {
                printContent = '';
            }

            // Salin konten ke print area
            printArea.innerHTML = printContent;

            if (activeTab === 'tab-data') {
                // Strip UI elements and sticky positioning for print
                printArea.querySelectorAll('div').forEach(function(div) {
                    div.style.overflow = 'visible';
                    div.style.overflowX = 'visible';
                    div.style.position = 'static';
                    div.style.boxShadow = 'none';
                    div.style.border = 'none';
                });

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
                    
                    // Kill sticky columns for print
                    cell.style.position = 'static';
                    cell.style.left = 'auto';
                    cell.style.zIndex = 'auto';
                    cell.style.backgroundColor = 'transparent';
                    cell.style.width = 'auto';
                    cell.style.minWidth = '0';
                    cell.style.border = '1px solid #000';

                    if (isCentered) {
                        cell.classList.add('text-center');
                    }
                });

                // Force tables to use auto layout for print to avoid overlap/cut-off
                printArea.querySelectorAll('table').forEach(function(table) {
                    table.style.tableLayout = 'auto';
                    table.style.width = '100%';
                    table.style.minWidth = '0';
                    table.style.borderCollapse = 'collapse';

                    // Check if this table belongs to a matrix section (Siswa/GTK)
                    const isMatrix = table.closest('.matrix-table-wrapper');
                    
                    table.querySelectorAll('th, td').forEach(function(cell) {
                        cell.style.border = '1px solid #000';
                        cell.style.padding = '3px 2px';
                        cell.style.fontSize = '8pt';

                        // Centering for Matrix tables
                        if (isMatrix) {
                            cell.style.textAlign = 'center';
                            cell.style.verticalAlign = 'middle';
                            
                            // Narrow NO column
                            if (cell.innerText.trim() === 'NO' || (cell.parentElement.firstElementChild === cell && cell.innerText.trim().match(/^\d+$/))) {
                                cell.style.width = '25px';
                                cell.style.minWidth = '25px';
                            }
                        }
                    });
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

        function closePreview() {
            document.getElementById('preview-modal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }


        // submit
        submitBtn.addEventListener('click', function() {
            const checkedItems = document.querySelectorAll('.report-checkbox:checked');

            if (checkedItems.length !== totalItems) {
                Swal.fire({
                    icon: 'warning',
                    title: '<span style="font-size: 1.125rem; font-weight: 600; color: #111827;">Perhatian</span>',
                    html: '<span style="font-size: 0.875rem; color: #4b5563;">Semua item harus dicentang sebelum mengirim laporan</span>',
                    confirmButtonColor: '#10b981',
                });
                return;
            }

            Swal.fire({
                icon: 'success',
                title: '<span style="font-size: 1.125rem; font-weight: 600; color: #111827;">Berhasil</span>',
                html: '<span style="font-size: 0.875rem; color: #4b5563;">Laporan berhasil dikirim!</span>',
                confirmButtonColor: '#10b981',
            });
        });

        updateProgress();

    });
</script>

</x-filament-panels::page>