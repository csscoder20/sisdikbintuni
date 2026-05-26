<div style="display: flex; flex-direction: column; gap: 32px;">
    <style>
        .matrix-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .matrix-table,
        .matrix-table th,
        .matrix-table td {
            border: 1px solid #000;
        }

        .matrix-table th {
            padding: 6px 8px;
            font-size: 11px;
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .matrix-table td {
            padding: 6px 8px;
            font-size: 11px;
            color: #111827;
            text-align: center;
        }

        .matrix-sub-header {
            font-size: 11px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .matrix-val-jml {
            font-weight: 600;
            background-color: #f9fafb;
        }
    </style>

    <div>
        <h4 class="matrix-sub-header">JUMLAH SISWA BERDASARKAN KELAS / ROMBEL</h4>
        <div style="overflow-x: auto;">
            <table class="matrix-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 30px;">NO</th>
                        <th rowspan="2" style="text-align: left; width: 150px;">KELAS / ROMBEL</th>
                        @foreach (['AWAL BULAN', 'MUTASI MASUK', 'MUTASI KELUAR', 'PUTUS SEKOLAH', 'MENGULANG', 'AKHIR BULAN'] as $h)
                            <th colspan="3">{{ $h }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @for ($i = 0; $i < 6; $i++)
                            <th style="width: 25px;">L</th>
                            <th style="width: 25px;">P</th>
                            <th style="width: 35px;">JML</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['perKelas'] as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="text-align: left; font-weight: 800;">{{ $row['nama_rombel'] }}</td>
                            @foreach (['awal_bulan', 'mutasi', 'mutasi_keluar', 'putus_sekolah', 'mengulang', 'akhir_bulan'] as $pfx)
                                <td>{{ $row[$pfx . '_l'] }}</td>
                                <td>{{ $row[$pfx . '_p'] }}</td>
                                <td class="matrix-val-jml">{{ $row[$pfx . '_jml'] }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h4 class="matrix-sub-header">JUMLAH SISWA MENURUT UMUR</h4>
        <div style="overflow-x: auto;">
            <table class="matrix-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 30px;">NO</th>
                        <th rowspan="2" style="text-align: left; width: 120px;">KELAS / ROMBEL</th>
                        @for ($age = 13; $age <= 23; $age++)
                            <th colspan="3">{{ $age }} THN</th>
                        @endfor
                    </tr>
                    <tr>
                        @for ($age = 13; $age <= 23; $age++)
                            <th>L</th>
                            <th>P</th>
                            <th class="matrix-val-jml">J</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['perUmur'] as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="text-align: left; font-weight: 800;">{{ $row['nama_rombel'] }}</td>
                            @for ($age = 13; $age <= 23; $age++)
                                @php $px = 'umur_' . $age; @endphp
                                <td>{{ $row[$px . '_l'] }}</td>
                                <td>{{ $row[$px . '_p'] }}</td>
                                <td class="matrix-val-jml">{{ $row[$px . '_jml'] }}</td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h4 class="matrix-sub-header text-left">JUMLAH SISWA MENURUT AGAMA</h4>
        <div style="overflow-x: auto;">
            <table class="matrix-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 30px;">NO</th>
                        <th rowspan="2" style="text-align: left;">KELAS</th>
                        @foreach (['ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDDHA', 'KHONGHUCU'] as $ag)
                            <th colspan="3">{{ $ag }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @for ($i = 0; $i < 6; $i++)
                            <th>L</th>
                            <th>P</th>
                            <th class="matrix-val-jml">J</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['perAgama'] as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="text-align: left; font-weight: 800;">{{ $row['nama_rombel'] }}</td>
                            @foreach (['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'khonghucu'] as $ag)
                                <td>{{ $row[$ag . '_l'] }}</td>
                                <td>{{ $row[$ag . '_p'] }}</td>
                                <td class="matrix-val-jml">{{ $row[$ag . '_jml'] }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h4 class="matrix-sub-header">JUMLAH SISWA MENURUT DAERAH ASAL</h4>
        <div style="overflow-x: auto;">
            <table class="matrix-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 30px;">NO</th>
                        <th rowspan="2" style="text-align: left;">KELAS</th>
                        @foreach (['PAPUA', 'NON-PAPUA'] as $da)
                            <th colspan="3">{{ $da }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @for ($i = 0; $i < 2; $i++)
                            <th>L</th>
                            <th>P</th>
                            <th class="matrix-val-jml">J</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['perDaerah'] as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="text-align: left; font-weight: 800;">{{ $row['nama_rombel'] }}</td>
                            @foreach (['papua', 'non_papua'] as $da)
                                <td>{{ $row[$da . '_l'] }}</td>
                                <td>{{ $row[$da . '_p'] }}</td>
                                <td class="matrix-val-jml">{{ $row[$da . '_jml'] }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if (isset($data['disabilitas']))
        <div style="margin-top: 0;">
            <h4 class="matrix-sub-header">JUMLAH SISWA MENURUT DISABILITAS</h4>
            <div style="overflow-x: auto;">
                <table class="matrix-table">
                    <thead>
                        <tr>
                            <th style="width: 30px; text-align: center;">NO</th>
                            <th style="min-width: 140px; text-align: left;">JENIS DISABILITAS</th>
                            <th style="text-align: center; width: 40px;">L</th>
                            <th style="text-align: center; width: 40px;">P</th>
                            <th style="text-align: center; width: 50px;" class="matrix-val-jml">JML</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['disabilitas'] as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="text-align: left; font-weight: 800;">{{ $row['jenis_disabilitas'] ?? '-' }}
                                </td>
                                <td>{{ $row['laki_laki'] ?? 0 }}</td>
                                <td>{{ $row['perempuan'] ?? 0 }}</td>
                                <td class="matrix-val-jml">{{ $row['total'] ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
