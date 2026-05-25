<div style="display: flex; flex-direction: column; gap: 32px;">
    <style>
        .gtk-matrix-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            text-align: center;
            border: 1px solid #111827;
        }

        .gtk-matrix-table th,
        .gtk-matrix-table td {
            border: 1px solid #111827;
            padding: 4px;
        }

        .gtk-matrix-table th {
            background-color: #f9fafb;
            font-weight: 800;
        }

        .gtk-matrix-sub-header {
            font-size: 10px;
            font-weight: 900;
            margin-bottom: 8px;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.5px;
        }

        .gtk-matrix-val-jml {
            font-weight: 800;
            background-color: #f3f4f6;
        }
    </style>

    @foreach (['Agama' => 'agama', 'Daerah' => 'daerah', 'Umur' => 'umur'] as $title => $key)
        <div>
            <h4 class="gtk-matrix-sub-header">DATA GTK BERDASARKAN {{ $title }}</h4>
            <div style="overflow-x: auto;">
                <table class="gtk-matrix-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 30px;">NO</th>
                            <th rowspan="2" style="text-align: left; width: 150px;">JENIS GTK</th>
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
                            @endif
                        </tr>
                        <tr>
                            @php $cols = ($key === 'agama' ? 6 : ($key === 'daerah' ? 2 : 5)); @endphp
                            @for ($i = 0; $i < $cols; $i++)
                                <th style="width: 25px;">L</th>
                                <th style="width: 25px;">P</th>
                                <th style="width: 35px;" class="gtk-matrix-val-jml">JML</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data[$key] as $idx => $row)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td style="text-align: left; font-weight: 800;">{{ $row->jenis_gtk }}</td>
                                @php $subs = ($key === 'agama' ? ['islam','kristen_protestan','katolik','hindu','budha','konghucu'] : ($key === 'daerah' ? ['papua','non_papua'] : ['umur_20_29','umur_30_39','umur_40_49','umur_50_59','umur_60_ke_atas'])); @endphp
                                @foreach ($subs as $s)
                                    <td>{{ $row->{$s . '_l'} }}</td>
                                    <td>{{ $row->{$s . '_p'} }}</td>
                                    <td class="gtk-matrix-val-jml">{{ $row->{$s . '_jml'} }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
