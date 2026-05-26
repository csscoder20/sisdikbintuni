<div class="report-preview-container">
    <style>
        /* 1. Page & Print Configuration */
        @page {
            size: A4 portrait;
            margin: 1cm;
        }

        @page landscape {
            size: A4 landscape;
            margin: 1cm;
        }

        @media print {
            .landscape-section {
                page: landscape !important;
                display: block !important;
                break-before: page !important;
                width: 100% !important;
            }

            .landscape-section table {
                width: 100% !important;
                font-size: 9px !important;
            }

            .report-preview-container {
                background-color: white !important;
                padding: 0 !important;
            }

            .document-paper {
                box-shadow: none !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Hide everything except the report */
            body * {
                visibility: hidden;
            }

            .fi-modal,
            .fi-modal *,
            .report-preview-container,
            .report-preview-container * {
                visibility: visible !important;
            }

            .fi-modal-header,
            .fi-modal-footer,
            .fi-modal-close-button,
            .summary-header,
            .stats-grid {
                display: none !important;
            }
        }

        /* 2. Screen Styles */
        .report-preview-container {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: transparent;
            padding: 0;
            max-height: 60vh;
            overflow-y: auto;
            overflow-x: auto;
            color: #1f2937;
        }

        .document-paper {
            background: white;
            width: 100%;
            max-width: none;
            margin: 0;
            box-shadow: none;
            border-radius: 0;
            overflow: hidden;
        }

        .document-header {
            /* Compact header by request */
            padding: 0;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .logo-img {
            height: 60px;
            width: auto;
        }

        .header-text {
            text-align: center;
            flex: 1;
        }

        .header-text h1 {
            font-size: 18px;
            font-weight: 900;
            margin: 4px 0;
            text-transform: uppercase;
        }

        .header-text p {
            font-size: 11px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
        }

        .header-sub {
            font-size: 9px !important;
            font-style: italic;
            color: #6b7280;
            margin-top: 4px !important;
        }

        .document-title-box {
            text-align: center;
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
        }

        .document-title-box h2 {
            font-size: 15px;
            font-weight: 900;
            text-decoration: underline;
            margin: 0;
        }

        .document-title-box p {
            font-size: 12px;
            font-weight: 700;
            color: #4b5563;
            margin: 4px 0 0;
        }

        .document-body {
            padding: 0;
        }

        .section-header {
            background-color: #f3f4f6;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 14px;
            border: 1px solid #d1d5db;
            margin-bottom: 16px;
            color: #111827;
            text-transform: uppercase;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .table-identitas {
            margin-bottom: 24px;
            border: none;
        }

        .table-identitas td {
            padding: 4px 8px;
            vertical-align: top;
            font-size: 12px;
            border: none !important;
            color: #111827;
        }

        .table-identitas tr {
            border: none !important;
        }

        .table-bordered {
            border: 1px solid #000;
        }

        .table-bordered thead tr {
            background-color: #f2f2f2;
            text-align: left;
        }

        .table-bordered th,
        .table-bordered td {
            padding: 6px 8px;
            font-size: 12px;
            color: #111827;
            border: 1px solid #000;
        }

        .table-bordered th {
            font-weight: bold;
        }

        .bg-muted {
            background-color: #f2f2f2;
        }

        /* Landscape Preview Handling */
        .landscape-section {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0;
            padding: 0;
        }

        .landscape-section table {
            min-width: 1000px;
        }

        /* Signature Area */
        .signature-grid {
            display: flex;
            justify-content: space-between;
            margin-top: 32px;
            padding: 0;
        }

        .sig-box {
            text-align: center;
            width: 200px;
            font-size: 12px;
        }

        .sig-space {
            height: 60px;
        }

        .sig-name {
            border-bottom: 1px solid #111827;
            font-weight: 800;
            text-transform: uppercase;
            padding-bottom: 2px;
        }

        .sig-title {
            font-size: 11px;
            margin-top: 4px;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 1024px) {
            .document-paper {
                max-width: 100%;
            }
        }

        @media (max-width: 640px) {
            .document-body {
                padding: 20px;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .logo-img {
                height: 40px;
            }

            .signature-grid {
                flex-direction: column;
                gap: 40px;
                align-items: center;
            }
        }

        /* 3. Custom Scrollbar Styling (Highly Visible) */
        .report-preview-container::-webkit-scrollbar,
        .report-preview-container *::-webkit-scrollbar,
        .landscape-section::-webkit-scrollbar {
            width: 12px !important;
            height: 12px !important;
            display: block !important;
        }

        .report-preview-container::-webkit-scrollbar-track,
        .report-preview-container *::-webkit-scrollbar-track,
        .landscape-section::-webkit-scrollbar-track {
            background: #f3f4f6 !important;
            border-radius: 6px !important;
        }

        .report-preview-container::-webkit-scrollbar-thumb,
        .report-preview-container *::-webkit-scrollbar-thumb,
        .landscape-section::-webkit-scrollbar-thumb {
            background: #9ca3af !important;
            border-radius: 6px !important;
            border: 3px solid #f3f4f6 !important;
        }

        .report-preview-container::-webkit-scrollbar-thumb:hover,
        .report-preview-container *::-webkit-scrollbar-thumb:hover,
        .landscape-section::-webkit-scrollbar-thumb:hover {
            background: #6b7280 !important;
        }

        /* Firefox Support */
        .report-preview-container,
        .report-preview-container *,
        .landscape-section {
            scrollbar-width: auto !important;
            scrollbar-color: #9ca3af #f3f4f6 !important;
        }
    </style>

    <div class="document-paper" id="report-document">
        <div class="document-header">
            <div class="header-content">
                <img src="{{ asset('assets/logo/logo-bintuni.png') }}" class="logo-img">
                <div class="header-text">
                    <p>Pemerintah Kabupaten Teluk Bintuni</p>
                    <p>Dinas Pendidikan, Kebudayaan, Pemuda dan Olahraga</p>
                    <h1>{{ \App\Models\Sekolah::find($this->getSchoolId())?->nama }}</h1>
                    <p class="header-sub">Email: {{ \App\Models\Sekolah::find($this->getSchoolId())?->email }} | Website:
                        {{ \App\Models\Sekolah::find($this->getSchoolId())?->website }}</p>
                </div>
                <img src="{{ asset('assets/logo/tut-wuri-handayani.png') }}" class="logo-img">
            </div>
            <div class="document-title-box">
                <h2>LAPORAN BULANAN SEKOLAH</h2>
                <p>Periode: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
            </div>
        </div>

        <div class="document-body">
            @php $sectionCounter = 0; @endphp
            @foreach ($checklist as $key => $label)
                @if ($checklistStatus[$key])
                    @php
                        $letter = chr(65 + $sectionCounter++);
                        $previewData = $this->getChecklistPreviewData($key);
                        $isLandscape = in_array($key, [
                            'nominatif_gtk',
                            'nominatif_siswa',
                            'riwayat_pendidikan_gtk',
                            'rekening_npwp_gtk',
                        ]);
                    @endphp

                    <div class="section-wrapper {{ $isLandscape ? 'landscape-section' : '' }}">
                        <div class="section-header">{{ $letter }}. {{ $label }}</div>

                        <div class="section-content">
                            @if (empty($previewData))
                                <p style="font-style: italic; color: #9ca3af; font-size: 14px;">Data tidak tersedia</p>
                            @else
                                @if (isset($previewData['type']) && $previewData['type'] === 'rekap_siswa_matrix')
                                    @include('livewire.report-sections.rekap-siswa', [
                                        'data' => $previewData['data'],
                                    ])
                                @elseif (isset($previewData['type']) && $previewData['type'] === 'rekap_gtk_matrix')
                                    @include('livewire.report-sections.rekap-gtk', [
                                        'data' => $previewData['data'],
                                    ])
                                @elseif ($key === 'identitas_sekolah')
                                    <table class="table-identitas">
                                        <tbody>
                                            @foreach ($previewData as $idx => $item)
                                                <tr style="{{ isset($item['is_header']) ? 'font-weight: 900;' : '' }}">
                                                    <td style="width: 25px; color: #9ca3af; font-size: 13px;">
                                                        {{ !isset($item['is_sub']) ? $idx + 1 : '' }}</td>
                                                    <td
                                                        style="width: 240px; font-size: 13px; {{ isset($item['is_sub']) ? 'padding-left: 20px;' : '' }}">
                                                        {{ $item['label'] }}</td>
                                                    <td style="width: 10px; font-size: 13px;">:</td>
                                                    <td style="font-weight: 600; font-size: 13px;">{{ $item['value'] }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @elseif ($key === 'kondisi_sarpras')
                                    <table class="table-bordered">
                                        <thead>
                                            <tr class="bg-muted">
                                                <th rowspan="2" style="width: 30px; text-align: center;">No</th>
                                                <th rowspan="2">Keadaan Fisik</th>
                                                <th rowspan="2" style="width: 60px; text-align: center;">Jumlah</th>
                                                <th colspan="2" style="text-align: center;">Tingkat Kerusakan</th>
                                                <th colspan="2" style="text-align: center;">Status Kepemilikan</th>
                                            </tr>
                                            <tr class="bg-muted">
                                                <th style="width: 60px; text-align: center;">Baik</th>
                                                <th style="width: 60px; text-align: center;">Rusak</th>
                                                <th style="width: 80px; text-align: center;">Milik</th>
                                                <th style="width: 80px; text-align: center;">Bukan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($previewData as $idx => $item)
                                                <tr>
                                                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                                                    <td>{{ $item['label'] }}</td>
                                                    <td style="text-align: center;">{{ $item['details']['Jumlah'] }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        {{ $item['details']['Tingkat Kerusakan_Baik'] }}</td>
                                                    <td style="text-align: center;">
                                                        {{ $item['details']['Tingkat Kerusakan_Rusak'] }}</td>
                                                    <td style="text-align: center;">
                                                        {{ $item['details']['Status Kepemilikan_Milik'] }}</td>
                                                    <td style="text-align: center;">
                                                        {{ $item['details']['Status Kepemilikan_Bukan Milik'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    @php
                                        $firstDetails = collect($previewData)->first()['details'] ?? [];
                                        $headers = is_array($firstDetails) ? array_keys($firstDetails) : ['Keterangan'];
                                    @endphp
                                    <table class="table-bordered">
                                        <thead>
                                            <tr class="bg-muted">
                                                <th style="width: 30px; text-align: center;">No</th>
                                                <th>Nama Lengkap / Rincian</th>
                                                @foreach ($headers as $header)
                                                    <th style="text-align: center;">{{ $header }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($previewData as $idx => $item)
                                                <tr class="{{ $loop->even ? 'bg-muted' : '' }}">
                                                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                                                    <td>{{ $item['label'] }}</td>
                                                    @if (is_array($item['details']))
                                                        @foreach ($headers as $header)
                                                            <td style="text-align: center;">
                                                                {{ $item['details'][$header] ?? '-' }}</td>
                                                        @endforeach
                                                    @else
                                                        <td style="text-align: center;"
                                                            colspan="{{ count($headers) }}">{{ $item['details'] }}
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @php
            $schoolId = $this->getSchoolId();
            $sekolah = \App\Models\Sekolah::find($schoolId);
            $kepsek = \App\Models\Gtk::where('sekolah_id', $schoolId)->where('jenis_gtk', 'Kepala Sekolah')->first();

            $laporanId = $this->selectedLaporanId;
            if ($laporanId) {
                $laporan = \App\Models\Laporan::find($laporanId);
            } else {
                $laporan = \App\Models\Laporan::where('sekolah_id', $schoolId)
                    ->orderBy('tahun', 'desc')
                    ->orderBy('bulan', 'desc')
                    ->first();
            }

            $dateString = '';
            if ($laporan) {
                if ($laporan->tanggal_submit) {
                    $dateString = \Carbon\Carbon::parse($laporan->tanggal_submit)->translatedFormat('d F Y');
                } else {
                    $reportDate = \Carbon\Carbon::createFromDate($laporan->tahun, $laporan->bulan, 1);
                    if ($reportDate->isCurrentMonth()) {
                        $dateString = \Carbon\Carbon::now()->translatedFormat('d F Y');
                    } else {
                        $dateString = $reportDate->endOfMonth()->translatedFormat('d F Y');
                    }
                }
            } else {
                $dateString = \Carbon\Carbon::now()->translatedFormat('d F Y');
            }
        @endphp
        <div class="signature-grid">
            <div class="sig-box"></div>
            <div class="sig-box">
                <p style="margin: 0;">Bintuni, {{ $dateString }}<br>Kepala {{ $sekolah?->nama ?? 'Sekolah' }}</p>
                <div class="sig-space"></div>
                <div class="sig-name">{{ $kepsek?->nama ?? '..........................' }}</div>
                <div class="sig-title" style="margin-top: 2px;">
                    NIP. {{ $kepsek?->nip ?? '..........................' }}
                </div>
            </div>
        </div>
    </div>
</div>
