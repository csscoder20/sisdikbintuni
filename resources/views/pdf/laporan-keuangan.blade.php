<!DOCTYPE html>
<html>

<head>
    <title>Data Keuangan</title>
    <style>
        @page {
            margin: 0.4cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 9pt;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 3px;
            text-align: center;
            word-wrap: break-word;
            vertical-align: middle;
        }

        th {
            background-color: #1F4E78;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
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
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/logo/logo-bintuni.png'))) }}"
                style="height: 75px; width: auto;" />
        </div>
        <div style="display: table-cell; vertical-align: middle; text-align: center; padding: 0 10px;">
            <div style="font-size: 11pt; font-weight: normal; margin-bottom: 2px; text-transform: uppercase;">PEMERINTAH
                KABUPATEN TELUK BINTUNI</div>
            <div style="font-size: 10pt; font-weight: normal; margin-bottom: 2px; text-transform: uppercase;">DINAS
                PENDIDIKAN, KEBUDAYAAN, PEMUDA, DAN OLAHRAGA</div>
            <div style="font-size: 14pt; font-weight: bold; margin-bottom: 5px; text-transform: uppercase;">
                {{ $sekolah?->nama ?? 'NAMA SEKOLAH' }}</div>
            <div style="font-size: 8pt; font-weight: normal; margin-bottom: 2px;">
                {{ $sekolah?->alamat ?? '---' }}, {{ $sekolah?->desa ?? '-' }}, {{ $sekolah?->kecamatan ?? '-' }},
                {{ $sekolah?->kabupaten ?? 'Teluk Bintuni' }}, Papua Barat
            </div>
            <div style="font-size: 8pt; font-weight: normal; font-style: italic;">
                email : {{ $sekolah?->email ?? '-' }} - Website : {{ $sekolah?->website ?? '-' }} - Kode Pos:
                {{ $sekolah?->kodepos ?? '-' }}
            </div>
        </div>
        <div style="display: table-cell; width: 80px; vertical-align: middle; text-align: right;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/logo/tut-wuri-handayani.png'))) }}"
                style="height: 75px; width: auto;" />
        </div>
    </div>

    <h2>DATA KEUANGAN {{ strtoupper($sekolah?->nama ?? '') }}</h2>
    <div class="header-info">&nbsp;</div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">NO</th>
                @foreach ($columns as $key => $label)
                    <th>{{ strtoupper($label) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    @foreach ($columns as $key => $label)
                        @php
                            $value = $record->{$key};
                            if ($value instanceof \Carbon\Carbon || $value instanceof \DateTime) {
                                $value = $value->format('d/m/Y');
                            } elseif (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                                $value = \Carbon\Carbon::parse($value)->format('d/m/Y');
                            }
                            if (in_array($key, ['nominal', 'saldo']) && is_numeric($value)) {
                                $value = number_format($value, 0, ',', '.');
                            }
                        @endphp
                        <td>{{ $value ?? '-' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Ringkasan Saldo --}}
    @php
        $saldonAwal = $records->isNotEmpty() ? $records->first()->saldo : 0;
        $saldoAkhir = $records->isNotEmpty() ? $records->last()->saldo : 0;
        $totalDebit = $records->sum(function ($r) {
            $nom = $r->nominal ?? 0;
            return $r->jenis_transaksi === 'debit' || $nom > 0 ? abs($nom) : 0;
        });
        $totalKredit = $records->sum(function ($r) {
            $nom = $r->nominal ?? 0;
            return $r->jenis_transaksi === 'kredit' || $nom < 0 ? abs($nom) : 0;
        });
    @endphp

    <div style="margin-top: 30px; border: 1px solid #000; padding: 15px; font-size: 10pt;">
        <h3 style="margin-top: 0; margin-bottom: 10px; text-align: left;">RINGKASAN SALDO</h3>
        <table style="width: 50%; margin: 0; border-collapse: collapse;">
            <tr style="background-color: #f0f0f0;">
                <td style="border: 1px solid #000; padding: 5px; text-align: left; width: 60%; font-weight: bold;">Saldo
                    Awal</td>
                <td style="border: 1px solid #000; padding: 5px; text-align: right; width: 40%; font-weight: bold;">
                    {{ number_format($saldonAwal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px; text-align: left;">Total Debit</td>
                <td style="border: 1px solid #000; padding: 5px; text-align: right;">
                    {{ number_format($totalDebit, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px; text-align: left;">Total Kredit</td>
                <td style="border: 1px solid #000; padding: 5px; text-align: right;">
                    {{ number_format($totalKredit, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #1F4E78; color: #ffffff; font-weight: bold;">
                <td style="border: 1px solid #000; padding: 5px; text-align: left;">Saldo Akhir</td>
                <td style="border: 1px solid #000; padding: 5px; text-align: right;">
                    {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    @php
        $dateString = \Carbon\Carbon::now()->translatedFormat('d F Y');
        $kepsek = isset($sekolah)
            ? \App\Models\Gtk::where('sekolah_id', $sekolah->id)->where('jenis_gtk', 'Kepala Sekolah')->first()
            : null;
        $titleSign = 'Kepala Sekolah';
        $namaSign = $kepsek?->nama ?? '..........................';
        $nipSign = $kepsek?->nip ?? '..........................';
    @endphp
    <table
        style="width: 100%; border: none !important; margin-top: 30px; font-size: 10pt; background: transparent !important;">
        <tr style="background: transparent !important;">
            <td style="border: none !important; width: 60%; background: transparent !important;"></td>
            <td
                style="border: none !important; width: 40%; text-align: left; background: transparent !important; padding: 0;">
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
