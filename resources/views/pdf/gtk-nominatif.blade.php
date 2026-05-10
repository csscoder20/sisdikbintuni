<!DOCTYPE html>
<html>
<head>
    <title>Nominatif GTK</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            word-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        .header-info {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div style="display: table; width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px;">
        <div style="display: table-cell; width: 80px; vertical-align: middle; text-align: left;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/logo/logo-bintuni.png'))) }}" style="height: 75px; width: auto;" />
        </div>
        <div style="display: table-cell; vertical-align: middle; text-align: center; padding: 0 10px;">
            <div style="font-size: 11pt; font-weight: normal; margin-bottom: 2px; text-transform: uppercase;">PEMERINTAH KABUPATEN TELUK BINTUNI</div>
            <div style="font-size: 10pt; font-weight: normal; margin-bottom: 2px; text-transform: uppercase;">DINAS PENDIDIKAN, KEBUDAYAAN, PEMUDA, DAN OLAHRAGA</div>
            <div style="font-size: 14pt; font-weight: bold; margin-bottom: 5px; text-transform: uppercase;">{{ $sekolah?->nama ?? 'NAMA SEKOLAH' }}</div>
            <div style="font-size: 8pt; font-weight: normal; margin-bottom: 2px;">
                {{ $sekolah?->alamat ?? '---' }}, {{ $sekolah?->desa ?? '-' }}, {{ $sekolah?->kecamatan ?? '-' }}, {{ $sekolah?->kabupaten ?? 'Teluk Bintuni' }}, Papua Barat
            </div>
            <div style="font-size: 8pt; font-weight: normal; font-style: italic;">
                email : {{ $sekolah?->email ?? '-' }} - Website : {{ $sekolah?->website ?? '-' }} - Kode Pos: {{ $sekolah?->kodepos ?? '-' }}
            </div>
        </div>
        <div style="display: table-cell; width: 80px; vertical-align: middle; text-align: right;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/logo/tut-wuri-handayani.png'))) }}" style="height: 75px; width: auto;" />
        </div>
    </div>

    <h2>DAFTAR NOMINATIF GURU DAN TENAGA KEPENDIDIKAN</h2>
    <div class="header-info">
        Tanggal Export: {{ now()->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                @foreach($columns as $key => $label)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($records as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    @foreach($columns as $key => $label)
                        <td>{{ $record->{$key} ?? '-' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
