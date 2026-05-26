<div style="display: flex; flex-direction: column; gap: 20px;">
    <style>
        .gtk-matrix-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .gtk-matrix-table,
        .gtk-matrix-table th,
        .gtk-matrix-table td {
            border: 1px solid #000;
        }

        .gtk-matrix-table th {
            padding: 6px 8px;
            font-size: 11px;
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .gtk-matrix-table td {
            padding: 6px 8px;
            font-size: 11px;
            color: #111827;
            text-align: center;
        }

        .gtk-matrix-sub-header {
            font-size: 11px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .gtk-matrix-val-jml {
            font-weight: 600;
            background-color: #f9fafb;
        }
    </style>

    @foreach (['Agama' => 'agama', 'Daerah' => 'daerah', 'Umur' => 'umur', 'Status Kepegawaian' => 'status', 'Pendidikan Terakhir' => 'pendidikan'] as $title => $key)
        @if (isset($data[$key]))
            <div>
                <h4 class="gtk-matrix-sub-header">DATA GTK BERDASARKAN {{ strtoupper($title) }}</h4>
                <div style="overflow-x: auto;">
                    <table class="gtk-matrix-table">
                        <thead>
                            <tr>
                                <th @if (in_array($key, ['agama', 'daerah', 'umur'])) rowspan="2" @endif style="width: 30px;">NO</th>
                                <th @if (in_array($key, ['agama', 'daerah', 'umur'])) rowspan="2" @endif
                                    style="text-align: left; width: 150px;">JENIS GTK</th>
                                @if ($key === 'agama')
                                    @foreach (['ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDHA', 'KONGHUCU'] as $h)
                                        <th colspan="3">{{ $h }}</th>
                                    @endforeach
                                @elseif($key === 'daerah')
                                    @foreach (['PAPUA', 'NON-PAPUA'] as $h)
                                        <th colspan="3">{{ $h }}</th>
                                    @endforeach
                                @elseif($key === 'umur')
                                    @foreach (['20-29', '30-39', '40-49', '50-59', '60+'] as $h)
                                        <th colspan="3">{{ $h }}</th>
                                    @endforeach
                                @elseif($key === 'status')
                                    @foreach (['Gol I A', 'Gol I B', 'Gol I C', 'Gol I D', 'Gol II A', 'Gol II B', 'Gol II C', 'Gol II D', 'Gol III A', 'Gol III B', 'Gol III C', 'Gol III D', 'Gol IV A', 'Gol IV B', 'Gol IV C', 'Gol IV D', 'Gol IV E', 'PPPK', 'HONORER SEKOLAH'] as $h)
                                        <th>{{ strtoupper($h) }}</th>
                                    @endforeach
                                @elseif($key === 'pendidikan')
                                    @foreach (['SLTA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3'] as $h)
                                        <th>{{ $h }}</th>
                                    @endforeach
                                @endif
                            </tr>
                            @if (in_array($key, ['agama', 'daerah', 'umur']))
                                <tr>
                                    @php $cols = ($key === 'agama' ? 6 : ($key === 'daerah' ? 2 : 5)); @endphp
                                    @for ($i = 0; $i < $cols; $i++)
                                        <th style="width: 25px;">L</th>
                                        <th style="width: 25px;">P</th>
                                        <th style="width: 35px;" class="gtk-matrix-val-jml">JML</th>
                                    @endfor
                                </tr>
                            @endif
                        </thead>
                        <tbody>
                            @foreach ($data[$key] as $idx => $row)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td style="text-align: left; font-weight: 800;">{{ $row->jenis_gtk }}</td>
                                    @if (in_array($key, ['agama', 'daerah', 'umur']))
                                        @php $subs = ($key === 'agama' ? ['islam','kristen_protestan','katolik','hindu','budha','konghucu'] : ($key === 'daerah' ? ['papua','non_papua'] : ['umur_20_29','umur_30_39','umur_40_49','umur_50_59','umur_60_ke_atas'])); @endphp
                                        @foreach ($subs as $s)
                                            <td>{{ $row->{$s . '_l'} }}</td>
                                            <td>{{ $row->{$s . '_p'} }}</td>
                                            <td class="gtk-matrix-val-jml">{{ $row->{$s . '_jml'} }}</td>
                                        @endforeach
                                    @elseif ($key === 'status')
                                        @foreach (['gol_i_a', 'gol_i_b', 'gol_i_c', 'gol_i_d', 'gol_ii_a', 'gol_ii_b', 'gol_ii_c', 'gol_ii_d', 'gol_iii_a', 'gol_iii_b', 'gol_iii_c', 'gol_iii_d', 'gol_iv_a', 'gol_iv_b', 'gol_iv_c', 'gol_iv_d', 'gol_iv_e', 'pppk', 'honorer_sekolah'] as $s)
                                            <td>{{ $row->{$s} ?? 0 }}</td>
                                        @endforeach
                                    @elseif ($key === 'pendidikan')
                                        @foreach (['slta', 'di', 'dii', 'diii', 's1', 's2', 's3'] as $s)
                                            <td>{{ $row->{$s} ?? 0 }}</td>
                                        @endforeach
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach
</div>
