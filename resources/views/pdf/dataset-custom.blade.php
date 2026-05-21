<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 0.4cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #333333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: {{ $fontSize ?? '9pt' }};
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 6px 4px;
            text-align: center;
            word-wrap: break-word;
            vertical-align: middle;
        }
        th {
            background-color: #1e293b;
            color: #ffffff;
            font-weight: bold;
            font-size: 0.95em;
            text-transform: uppercase;
            border: 1px solid #1e293b;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        h2 {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 5px;
            color: #0f172a;
            font-size: 14pt;
        }
        .header-info {
            text-align: center;
            margin-bottom: 15px;
            font-size: 9pt;
            color: #64748b;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-info {
            background-color: #e0f2fe;
            color: #0369a1;
        }
    </style>
</head>
<body>
    <div style="display: table; width: 100%; border-bottom: 3px double #0f172a; padding-bottom: 10px; margin-bottom: 15px;">
        <div style="display: table-cell; width: 80px; vertical-align: middle; text-align: left;">
            @if(file_exists(public_path('assets/logo/logo-bintuni.png')))
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/logo/logo-bintuni.png'))) }}" style="height: 75px; width: auto;" />
            @endif
        </div>
        <div style="display: table-cell; vertical-align: middle; text-align: center; padding: 0 10px;">
            <div style="font-size: 12pt; font-weight: bold; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 0.5px; color: #0f172a;">PEMERINTAH KABUPATEN TELUK BINTUNI</div>
            <div style="font-size: 10pt; font-weight: 500; margin-bottom: 2px; text-transform: uppercase; color: #334155;">DINAS PENDIDIKAN, KEBUDAYAAN, PEMUDA, DAN OLAHRAGA</div>
            <div style="font-size: 8pt; font-weight: normal; color: #64748b;">
                Alamat: Kompleks Perkantoran Pemerintah Daerah Bumi Saniari, KM 9, Teluk Bintuni, Papua Barat
            </div>
        </div>
        <div style="display: table-cell; width: 80px; vertical-align: middle; text-align: right;">
            @if(file_exists(public_path('assets/logo/tut-wuri-handayani.png')))
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/logo/tut-wuri-handayani.png'))) }}" style="height: 75px; width: auto;" />
            @endif
        </div>
    </div>

    <h2>{{ strtoupper($title) }}</h2>
    <div class="header-info">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIT
        @if(!empty($subTitle))
            | {{ $subTitle }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">NO</th>
                @foreach($columns as $key => $label)
                    <th style="{{ in_array($key, ['nama', 'alamat']) ? 'text-align: left;' : '' }}">{{ strtoupper($label) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $record)
                <tr>
                    <td style="font-weight: 500;">{{ $index + 1 }}</td>
                    @foreach($columns as $key => $label)
                        @php
                            $value = $record->{$key};
                            
                            // Formatting values
                            if ($key === 'jenis_kelamin') {
                                if (strtolower($value) === 'laki-laki' || strtolower($value) === 'l') {
                                    $value = 'L';
                                } elseif (strtolower($value) === 'perempuan' || strtolower($value) === 'p') {
                                    $value = 'P';
                                }
                            }

                            if ($value instanceof \Carbon\Carbon || $value instanceof \DateTime) {
                                $value = $value->format('d/m/Y');
                            } elseif (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                                $value = \Carbon\Carbon::parse($value)->format('d/m/Y');
                            }
                            
                            // Nice badge for status
                            $isStatus = in_array($key, ['status', 'status_sekolah', 'status_kepegawaian']);
                        @endphp
                        
                        <td style="{{ in_array($key, ['nama', 'alamat']) ? 'text-align: left;' : '' }}">
                            {{ $value ?? '-' }}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) + 1 }}" style="padding: 20px; color: #64748b; font-style: italic;">
                        Tidak ada data yang sesuai dengan kriteria filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @php
        $dateString = \Carbon\Carbon::now()->translatedFormat('d F Y');
        if (isset($sekolah) && $sekolah) {
            $kepsek = \App\Models\Gtk::where('sekolah_id', $sekolah->id)
                ->where('jenis_gtk', 'Kepala Sekolah')
                ->first();
            $titleSign = 'Kepala ' . $sekolah->nama;
            $namaSign = $kepsek?->nama ?? '..........................';
            $nipSign = $kepsek?->nip ?? '..........................';
        } else {
            $titleSign = 'Kepala Sekolah';
            $namaSign = '..........................';
            $nipSign = '..........................';
        }
    @endphp
    <table style="width: 100%; border: none !important; margin-top: 30px; font-size: 10pt; background: transparent !important;">
        <tr style="background: transparent !important;">
            <td style="border: none !important; width: 50%; background: transparent !important;"></td>
            <td style="border: none !important; width: 50%; text-align: left; background: transparent !important; padding: 0;">
                <p style="margin: 0 0 50px 0; line-height: 1.5; text-align: left; background: transparent !important;">
                    Bintuni, {{ $dateString }}<br>
                    {{ $titleSign }}
                </p>
                <p style="margin: 0; font-weight: bold; text-align: left; background: transparent !important;">
                    {{ $namaSign }}
                </p>
                <p style="margin: 0; margin-top: 5px; text-align: left; background: transparent !important;">
                    NIP. {{ $nipSign }}
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
