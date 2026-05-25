<div class="pdf-section-block">
    @foreach (['Agama' => 'agama', 'Daerah' => 'daerah', 'Umur' => 'umur', 'Status Kepegawaian' => 'status', 'Pendidikan Terakhir' => 'pendidikan'] as $title => $key)
        @if (isset($data[$key]))
            @php
                $cols = match ($key) {
                    'agama' => 6,
                    'daerah' => 2,
                    'umur' => 5,
                    'status' => 19,
                    'pendidikan' => 7,
                    default => 0,
                };
                $titleColspan = 2 + $cols * ($key === 'agama' || $key === 'daerah' || $key === 'umur' ? 3 : 1);
            @endphp
            <table class="wide-table">
                <caption
                    style="caption-side: top; text-align: center; font-weight: bold; background-color: #f2f2f2; padding: 4px 0; break-after: avoid-page; page-break-after: avoid;">
                    DATA GTK BERDASARKAN {{ strtoupper($title) }}
                </caption>
                <thead>
                    <tr style="break-before: avoid-page; page-break-before: avoid;">
                        <th @if (in_array($key, ['agama', 'daerah', 'umur'])) rowspan="2" @endif style="width: 20px;">NO</th>
                        <th @if (in_array($key, ['agama', 'daerah', 'umur'])) rowspan="2" @endif style="width: 120px;">JENIS GTK</th>
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
                                <th>{{ $h }}</th>
                            @endforeach
                        @elseif($key === 'pendidikan')
                            @foreach (['SLTA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3'] as $h)
                                <th>{{ $h }}</th>
                            @endforeach
                        @endif
                    </tr>
                    @if (in_array($key, ['agama', 'daerah', 'umur']))
                        <tr>
                            @for ($i = 0; $i < $cols; $i++)
                                <th>L</th>
                                <th>P</th>
                                <th style="background-color: #eee;">JML</th>
                            @endfor
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @foreach ($data[$key] as $idx => $row)
                        <tr>
                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                            <td style="font-weight: bold;">{{ $row->jenis_gtk }}</td>
                            @if (in_array($key, ['agama', 'daerah', 'umur']))
                                @php $subs = ($key === 'agama' ? ['islam','kristen_protestan','katolik','hindu','budha','konghucu'] : ($key === 'daerah' ? ['papua','non_papua'] : ['umur_20_29','umur_30_39','umur_40_49','umur_50_59','umur_60_ke_atas'])); @endphp
                                @foreach ($subs as $s)
                                    <td style="text-align: center;">{{ $row->{$s . '_l'} }}</td>
                                    <td style="text-align: center;">{{ $row->{$s . '_p'} }}</td>
                                    <td style="text-align: center; background-color: #eee;">{{ $row->{$s . '_jml'} }}
                                    </td>
                                @endforeach
                            @elseif ($key === 'status')
                                @foreach (['gol_i_a', 'gol_i_b', 'gol_i_c', 'gol_i_d', 'gol_ii_a', 'gol_ii_b', 'gol_ii_c', 'gol_ii_d', 'gol_iii_a', 'gol_iii_b', 'gol_iii_c', 'gol_iii_d', 'gol_iv_a', 'gol_iv_b', 'gol_iv_c', 'gol_iv_d', 'gol_iv_e', 'pppk', 'honorer_sekolah'] as $s)
                                    <td style="text-align: center;">{{ $row->{$s} ?? 0 }}</td>
                                @endforeach
                            @elseif ($key === 'pendidikan')
                                @foreach (['slta', 'di', 'dii', 'diii', 's1', 's2', 's3'] as $s)
                                    <td style="text-align: center;">{{ $row->{$s} ?? 0 }}</td>
                                @endforeach
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
</div>
