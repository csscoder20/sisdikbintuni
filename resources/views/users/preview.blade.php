<div class="report-preview-container">
    <style>
        /* 1. Page & Print Configuration */
        @page {
            size: A4 portrait;
            margin: 1cm;
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
            padding: 0;
            margin-bottom: 20px;
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

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
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
            text-align: center;
        }

        .bg-muted {
            background-color: #f2f2f2;
        }

        /* 3. Custom Scrollbar Styling (Highly Visible) */
        .report-preview-container::-webkit-scrollbar,
        .report-preview-container *::-webkit-scrollbar {
            width: 12px !important;
            height: 12px !important;
            display: block !important;
        }

        .report-preview-container::-webkit-scrollbar-track,
        .report-preview-container *::-webkit-scrollbar-track {
            background: #f3f4f6 !important;
            border-radius: 6px !important;
        }

        .report-preview-container::-webkit-scrollbar-thumb,
        .report-preview-container *::-webkit-scrollbar-thumb {
            background: #9ca3af !important;
            border-radius: 6px !important;
            border: 3px solid #f3f4f6 !important;
        }

        .report-preview-container::-webkit-scrollbar-thumb:hover,
        .report-preview-container *::-webkit-scrollbar-thumb:hover {
            background: #6b7280 !important;
        }

        /* Firefox Support */
        .report-preview-container,
        .report-preview-container * {
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
                </div>
                <img src="{{ asset('assets/logo/tut-wuri-handayani.png') }}" class="logo-img">
            </div>
            <div class="document-title-box">
                <h2>DATA PENGGUNA SISTEM</h2>
            </div>
        </div>

        <div class="document-body">
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 20%">Nama</th>
                        <th style="width: 25%">Email</th>
                        <th style="width: 15%">Nomor WA</th>
                        <th style="width: 15%">Peran</th>
                        <th style="width: 20%">Sekolah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $index => $record)
                        @php
                            $roleMap = [
                                'operator' => 'Operator Sekolah',
                                'admin_dinas' => 'Admin Dinas',
                                'super_admin' => 'Administrator',
                                'pengawas' => 'Pengawas',
                            ];
                            $peran = $record->roles->pluck('name')->map(fn($r) => $roleMap[$r] ?? $r)->join(', ');
                        @endphp
                        <tr class="{{ $loop->even ? 'bg-muted' : '' }}">
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $record->name }}</td>
                            <td>{{ $record->email }}</td>
                            <td style="text-align: center;">{{ $record->nohp ?? '-' }}</td>
                            <td>{{ $peran }}</td>
                            <td>{{ $record->sekolah?->nama ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
