<div style="display: flex; flex-direction: column; gap: 32px;">
    <style>
        .matrix-table { width: 100%; border-collapse: collapse; font-size: 10px; text-align: center; border: 1px solid #111827; }
        .matrix-table th, .matrix-table td { border: 1px solid #111827; padding: 4px; }
        .matrix-table th { background-color: #f9fafb; font-weight: 800; }
        .matrix-sub-header { font-size: 10px; font-weight: 900; margin-bottom: 8px; text-transform: uppercase; color: #6b7280; letter-spacing: 0.5px; }
        .matrix-val-jml { font-weight: 800; background-color: #f3f4f6; }
    </style>

    <div>
        <h4 class="matrix-sub-header">1. JUMLAH SISWA BERDASARKAN KELAS / ROMBEL</h4>
        <div style="overflow-x: auto;">
            <table class="matrix-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 30px;">NO</th>
                        <th rowspan="2" style="text-align: left; width: 150px;">KELAS / ROMBEL</th>
                        @foreach(['AWAL BULAN', 'MUTASI MASUK', 'MUTASI KELUAR', 'PUTUS SEKOLAH', 'MENGULANG', 'AKHIR BULAN'] as $h)
                            <th colspan="3">{{ $h }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @for ($i = 0; $i < 6; $i++)
                            <th style="width: 25px;">L</th><th style="width: 25px;">P</th><th style="width: 35px;">JML</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['perKelas'] as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="text-align: left; font-weight: 800;">{{ $row['nama_rombel'] }}</td>
                            @foreach(['awal_bulan', 'mutasi', 'mutasi_keluar', 'putus_sekolah', 'mengulang', 'akhir_bulan'] as $pfx)
                                <td>{{ $row[$pfx.'_l'] }}</td><td>{{ $row[$pfx.'_p'] }}</td><td class="matrix-val-jml">{{ $row[$pfx.'_jml'] }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h4 class="matrix-sub-header">2. JUMLAH SISWA MENURUT UMUR</h4>
        <div style="overflow-x: auto;">
            <table class="matrix-table" style="font-size: 9px;">
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
                            <th>L</th><th>P</th><th class="matrix-val-jml">J</th>
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
                                <td>{{ $row[$px . '_l'] }}</td><td>{{ $row[$px . '_p'] }}</td><td class="matrix-val-jml">{{ $row[$px . '_jml'] }}</td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div style="display: flex; gap: 32px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 400px;">
            <h4 class="matrix-sub-header">3. JUMLAH SISWA MENURUT AGAMA</h4>
            <div style="overflow-x: auto;">
                <table class="matrix-table" style="font-size: 9px;">
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
                                <th>L</th><th>P</th><th class="matrix-val-jml">J</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['perAgama'] as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="text-align: left; font-weight: 800;">{{ $row['nama_rombel'] }}</td>
                                @foreach (['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'khonghucu'] as $ag)
                                    <td>{{ $row[$ag . '_l'] }}</td><td>{{ $row[$ag . '_p'] }}</td><td class="matrix-val-jml">{{ $row[$ag . '_jml'] }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <h4 class="matrix-sub-header">4. JUMLAH SISWA MENURUT DAERAH ASAL</h4>
            <div style="overflow-x: auto;">
                <table class="matrix-table" style="font-size: 9px;">
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
                                <th>L</th><th>P</th><th class="matrix-val-jml">J</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['perDaerah'] as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="text-align: left; font-weight: 800;">{{ $row['nama_rombel'] }}</td>
                                @foreach (['papua', 'non_papua'] as $da)
                                    <td>{{ $row[$da . '_l'] }}</td><td>{{ $row[$da . '_p'] }}</td><td class="matrix-val-jml">{{ $row[$da . '_jml'] }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
