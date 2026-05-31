<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ekspor Data Pengguna</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #000;
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .bg-muted {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            position: relative;
        }

        .header p {
            margin: 2px 0;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-logo-left {
            position: absolute;
            left: 0;
            top: 0;
            height: 60px;
        }

        .header-logo-right {
            position: absolute;
            right: 0;
            top: 0;
            height: 60px;
        }

        .document-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('assets/logo/logo-bintuni.png') }}" class="header-logo-left">
        <p>PEMERINTAH KABUPATEN TELUK BINTUNI</p>
        <p>DINAS PENDIDIKAN, KEBUDAYAAN, PEMUDA, DAN OLAHRAGA</p>
        <img src="{{ public_path('assets/logo/tut-wuri-handayani.png') }}" class="header-logo-right">
    </div>

    <div class="document-title">DATA PENGGUNA SISTEM</div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%" class="text-center">No</th>
                <th style="width: 20%">Nama</th>
                <th style="width: 25%">Email</th>
                <th style="width: 15%">Nomor WA</th>
                <th style="width: 15%">Peran</th>
                <th style="width: 20%">Sekolah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $index => $record)
                @php
                    $roleMap = [
                        'operator' => 'Operator Sekolah',
                        'admin_dinas' => 'Admin Dinas',
                        'super_admin' => 'Administrator',
                        'pengawas' => 'Pengawas',
                    ];
                    $peran = $record->roles->pluck('name')->map(fn($r) => $roleMap[$r] ?? $r)->join(', ');
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $record->name }}</td>
                    <td>{{ $record->email }}</td>
                    <td>{{ $record->nohp ?? '-' }}</td>
                    <td>{{ $peran }}</td>
                    <td>{{ $record->sekolah?->nama ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
