<div class="pdf-section-block">
    @foreach (['Agama' => 'agama', 'Daerah' => 'daerah', 'Umur' => 'umur'] as $title => $key)
        @if (isset($data[$key]))
            @php
                $cols = $key === 'agama' ? 6 : ($key === 'daerah' ? 2 : 5);
                $titleColspan = 2 + $cols * 3;
            @endphp
            <table class="wide-table">
                <caption
                    style="caption-side: top; text-align: center; font-weight: bold; background-color: #f2f2f2; padding: 4px 0; break-after: avoid-page; page-break-after: avoid;">
                    DATA GTK MENURUT {{ strtoupper($title) }}
                </caption>
                <thead>
                    <tr style="break-before: avoid-page; page-break-before: avoid;">
                        <th rowspan="2" style="width: 20px;">NO</th>
                        <th rowspan="2" style="width: 120px;">JENIS GTK</th>
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
                        @for ($i = 0; $i < $cols; $i++)
                            <th>L</th>
                            <th>P</th>
                            <th style="background-color: #eee;">JML</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data[$key] as $idx => $row)
                        <tr>
                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                            <td style="font-weight: bold;">{{ $row->jenis_gtk }}</td>
                            @php $subs = ($key === 'agama' ? ['islam','kristen_protestan','katolik','hindu','budha','konghucu'] : ($key === 'daerah' ? ['papua','non_papua'] : ['umur_20_29','umur_30_39','umur_40_49','umur_50_59','umur_60_ke_atas'])); @endphp
                            @foreach ($subs as $s)
                                <td style="text-align: center;">{{ $row->{$s . '_l'} }}</td>
                                <td style="text-align: center;">{{ $row->{$s . '_p'} }}</td>
                                <td style="text-align: center; background-color: #eee;">{{ $row->{$s . '_jml'} }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
</div>
