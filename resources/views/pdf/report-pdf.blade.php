<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Bulanan - {{ $sekolah->nama }}</title>
    <style>
        @page { size: A4 portrait; margin: 1cm; }
        @page landscape_page { size: A4 landscape; margin: 1cm; }
        
        .landscape-section {
            page: landscape_page;
            display: block;
            break-before: page;
            clear: both;
        }
        
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; line-height: 1.4; margin: 0; padding: 0; }
        
        
        .header { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            border-bottom: 2px solid #000; 
            padding-bottom: 10px; 
            margin-bottom: 20px; 
        }
        .logo-left, .logo-right {
            width: 65px;
            height: 65px;
            object-fit: contain;
        }
        .header-text {
            text-align: center;
            flex: 1;
        }
        .header-text h1 { font-size: 13px; margin: 3px 0; text-transform: uppercase; font-weight: bold; }
        .header-text p { margin: 2px 0; font-size: 9px; }
        
        .section { margin-bottom: 30px; }
        tr { page-break-inside: avoid; break-inside: avoid; }
        .section-header { background: #eee; padding: 5px 10px; font-weight: bold; font-size: 12px; border: 1px solid #ccc; margin-bottom: 10px; }
        
        .sarpras-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .sarpras-table th, .sarpras-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9px;
            vertical-align: middle;
        }
        .sarpras-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 4px; text-align: left; font-size: 9px; }
        th { background-color: #f2f2f2; font-weight: bold; }
        
        .table-identitas { border: none; }
        .table-identitas td { border: none; padding: 2px 5px; }
        
        .page-break { page-break-after: always; }
        
        .wide-table { font-size: 9px !important; }
        .wide-table th, .wide-table td { padding: 4px; }

        .signature-table { border: none; width: 100%; margin-top: 50px; }
        .signature-table td { border: none; width: 50%; text-align: center; }
        .sig-space { height: 60px; }
    </style>
</head>
<body>
    <div class="header">
        @if(!empty($dinasLogo))
            <img src="{{ $dinasLogo }}" class="logo-left" alt="Logo Dinas">
        @else
            <div style="width: 65px;"></div>
        @endif
        
        <div class="header-text">
            <h1>Pemerintah Kabupaten Teluk Bintuni</h1>
            <h1>Dinas Pendidikan, Kebudayaan, Pemuda dan Olahraga</h1>
            <h1>{{ $sekolah->nama }}</h1>
            <p>Email: {{ $sekolah->email ?? '-' }} | NPSN: {{ $sekolah->npsn }}</p>
            <p style="font-weight: bold; margin-top: 10px; font-size: 12px; text-decoration: underline;">LAPORAN BULANAN SEKOLAH</p>
            <p>Periode: {{ $periode }}</p>
        </div>

        @if(!empty($sekolahLogo))
            <img src="{{ $sekolahLogo }}" class="logo-right" alt="Logo Sekolah">
        @else
            <div style="width: 65px;"></div>
        @endif
    </div>

    @php $sectionCounter = 0; @endphp
    @foreach ($checklist as $key => $label)
        @if ($checklistStatus[$key] && isset($reportData[$key]))
            @php 
                $letter = chr(65 + $sectionCounter++); 
                $data = $reportData[$key];
                $isWide = in_array($key, ['nominatif_gtk', 'nominatif_siswa', 'riwayat_pendidikan_gtk', 'rekening_npwp_gtk']);
            @endphp
            
            <div class="section {{ $isWide ? 'landscape-section' : '' }}">
                <div class="section-header">{{ $letter }}. {{ $label }}</div>
                
                @if ($key === 'identitas_sekolah')
                    <table class="table-identitas">
                        @foreach ($data as $item)
                            <tr>
                                <td style="width: 200px;">{{ $item['label'] }}</td>
                                <td style="width: 10px;">:</td>
                                <td style="font-weight: bold;">{{ $item['value'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                @elseif (isset($data['type']) && $data['type'] === 'rekap_siswa_matrix')
                    @include('pdf.sections.rekap-siswa', ['data' => $data['data']])
                @elseif (isset($data['type']) && $data['type'] === 'rekap_gtk_matrix')
                    @include('pdf.sections.rekap-gtk', ['data' => $data['data']])
                @elseif ($key === 'kondisi_sarpras')
                    <table class="sarpras-table">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 30px; text-align: center; vertical-align: middle;">No</th>
                                <th rowspan="2" style="text-align: left; vertical-align: middle;">Keadaan Fisik</th>
                                <th rowspan="2" style="width: 60px; text-align: center; vertical-align: middle;">Jumlah</th>
                                <th colspan="2" style="text-align: center;">Tingkat Kerusakan</th>
                                <th colspan="2" style="text-align: center;">Status Kepemilikan</th>
                            </tr>
                            <tr>
                                <th style="width: 50px; text-align: center;">Baik</th>
                                <th style="width: 50px; text-align: center;">Rusak</th>
                                <th style="width: 60px; text-align: center;">Milik</th>
                                <th style="width: 80px; text-align: center;">Bukan Milik</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalJumlah = 0;
                                $totalBaik = 0;
                                $totalRusak = 0;
                            @endphp
                            @foreach ($data as $idx => $item)
                                @php
                                    $jumlah = intval($item['details']['Jumlah'] ?? 0);
                                    $baik = intval($item['details']['Tingkat Kerusakan_Baik'] ?? 0);
                                    $rusak = intval($item['details']['Tingkat Kerusakan_Rusak'] ?? 0);
                                    
                                    $totalJumlah += $jumlah;
                                    $totalBaik += $baik;
                                    $totalRusak += $rusak;
                                @endphp
                                <tr>
                                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                                    <td>{{ $item['label'] }}</td>
                                    <td style="text-align: center;">{{ $jumlah === 0 ? '-' : $jumlah }}</td>
                                    <td style="text-align: center;">{{ $baik === 0 ? '-' : $baik }}</td>
                                    <td style="text-align: center;">{{ $rusak === 0 ? '-' : $rusak }}</td>
                                    <td style="text-align: center;">{{ $item['details']['Status Kepemilikan_Milik'] ?? '-' }}</td>
                                    <td style="text-align: center;">{{ $item['details']['Status Kepemilikan_Bukan Milik'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold; background-color: #f2f2f2;">
                                <td colspan="2" style="text-align: center; font-weight: bold;">Total</td>
                                <td style="text-align: center; font-weight: bold;">{{ $totalJumlah }}</td>
                                <td style="text-align: center; font-weight: bold;">{{ $totalBaik }}</td>
                                <td style="text-align: center; font-weight: bold;">{{ $totalRusak }}</td>
                                <td style="background-color: #f2f2f2;"></td>
                                <td style="background-color: #f2f2f2;"></td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    @php
                        $firstDetails = collect($data)->first()['details'] ?? [];
                        $headers = is_array($firstDetails) ? array_keys($firstDetails) : ['Keterangan'];
                    @endphp
                    <table class="{{ $isWide ? 'wide-table' : '' }}">
                        <thead>
                            <tr>
                                <th style="width: 20px;">No</th>
                                <th>Nama / Rincian</th>
                                @foreach ($headers as $header)
                                    <th>{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $idx => $item)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $item['label'] }}</td>
                                    @if (is_array($item['details']))
                                        @foreach ($headers as $header)
                                            <td>{{ $item['details'][$header] ?? '-' }}</td>
                                        @endforeach
                                    @else
                                        <td colspan="{{ count($headers) }}">{{ $item['details'] }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            
        @endif
    @endforeach

    <table class="signature-table">
        <tr>
            <td></td>
            <td>
                @php
                    $kepsek = \App\Models\Gtk::where('sekolah_id', $sekolah->id)
                        ->where('jenis_gtk', 'Kepala Sekolah')
                        ->first();
                    
                    $dateString = '';
                    if (isset($laporan) && $laporan) {
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
                <p>Bintuni, {{ $dateString }}<br>Kepala {{ $sekolah->nama }}</p>
                <div class="sig-space"></div>
                <p><strong>{{ $kepsek?->nama ?? '..........................' }}</strong></p>
                <p>NIP. {{ $kepsek?->nip ?? '..........................' }}</p>
            </td>
        </tr>
    </table>
</body>
</html>
