<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Bulanan - {{ $sekolah->nama }}</title>
    <style>
        @page { size: A4 portrait; margin: 1cm; }
        @page landscape_page { size: A4 landscape; margin: 1cm; }
        
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; line-height: 1.4; margin: 0; padding: 0; }
        
        .landscape-section {
            page: landscape_page;
            display: block;
            page-break-before: always;
            clear: both;
        }
        
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; margin: 5px 0; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10px; }
        
        .section { margin-bottom: 30px; page-break-inside: avoid; }
        .section-header { background: #eee; padding: 5px 10px; font-weight: bold; font-size: 12px; border: 1px solid #ccc; margin-bottom: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 4px; text-align: left; font-size: 9px; }
        th { background-color: #f2f2f2; font-weight: bold; }
        
        .table-identitas { border: none; }
        .table-identitas td { border: none; padding: 2px 5px; }
        
        .page-break { page-break-after: always; }
        
        .wide-table { font-size: 8px !important; }
        .wide-table th, .wide-table td { padding: 2px; }

        .signature-table { border: none; width: 100%; margin-top: 50px; }
        .signature-table td { border: none; width: 50%; text-align: center; }
        .sig-space { height: 60px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Pemerintah Kabupaten Teluk Bintuni</h1>
        <h1>Dinas Pendidikan, Kebudayaan, Pemuda dan Olahraga</h1>
        <h1>{{ $sekolah->nama }}</h1>
        <p>Email: {{ $sekolah->email ?? '-' }} | NPSN: {{ $sekolah->npsn }}</p>
        <p style="font-weight: bold; margin-top: 10px; font-size: 12px; text-decoration: underline;">LAPORAN BULANAN SEKOLAH</p>
        <p>Periode: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
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
                    <table class="wide-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Keadaan Fisik</th>
                                <th>Jumlah</th>
                                <th>Baik</th>
                                <th>Rusak</th>
                                <th>Milik</th>
                                <th>Bukan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $idx => $item)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $item['label'] }}</td>
                                    <td>{{ $item['details']['Jumlah'] }}</td>
                                    <td>{{ $item['details']['Tingkat Kerusakan_Baik'] }}</td>
                                    <td>{{ $item['details']['Tingkat Kerusakan_Rusak'] }}</td>
                                    <td>{{ $item['details']['Status Kepemilikan_Milik'] }}</td>
                                    <td>{{ $item['details']['Status Kepemilikan_Bukan Milik'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
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
            <td>
                <p>Mengetahui,<br>Kepala Sekolah</p>
                <div class="sig-space"></div>
                @php $kepsek = \App\Models\Gtk::where('sekolah_id', $sekolah->id)->where('jenis_gtk', 'kepala_sekolah')->first(); @endphp
                <p><strong>{{ $kepsek?->nama ?? '..........................' }}</strong></p>
                <p>NIP. {{ $kepsek?->nip ?? '..........................' }}</p>
            </td>
            <td>
                <p>Bintuni, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Operator Sekolah</p>
                <div class="sig-space"></div>
                <p><strong>{{ auth()->user()->name }}</strong></p>
                <p>Divalidasi secara sistem</p>
            </td>
        </tr>
    </table>
</body>
</html>
