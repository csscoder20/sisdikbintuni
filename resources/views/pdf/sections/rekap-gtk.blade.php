<div style="margin-bottom: 20px;">
    @foreach(['Agama' => 'agama', 'Daerah' => 'daerah', 'Umur' => 'umur'] as $title => $key)
        @if(isset($data[$key]))
            <p style="font-weight: bold; margin-bottom: 5px; font-size: 10px;">DATA GTK MENURUT {{ strtoupper($title) }}</p>
            <table class="wide-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 20px;">NO</th>
                        <th rowspan="2" style="width: 120px;">JENIS GTK</th>
                        @if($key === 'agama') 
                            @foreach(['ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDHA', 'KONGHUCU'] as $h) <th colspan="3">{{ $h }}</th> @endforeach
                        @elseif($key === 'daerah') 
                            @foreach(['PAPUA', 'NON-PAPUA'] as $h) <th colspan="3">{{ $h }}</th> @endforeach
                        @elseif($key === 'umur') 
                            @foreach(['20-29', '30-39', '40-49', '50-59', '60+'] as $h) <th colspan="3">{{ $h }}</th> @endforeach
                        @endif
                    </tr>
                    <tr>
                        @php $cols = ($key === 'agama' ? 6 : ($key === 'daerah' ? 2 : 5)); @endphp
                        @for($i=0;$i<$cols;$i++) 
                            <th>L</th><th>P</th><th style="background-color: #eee;">JML</th> 
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach($data[$key] as $idx => $row)
                        <tr>
                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                            <td style="font-weight: bold;">{{ $row->jenis_gtk }}</td>
                            @php $subs = ($key === 'agama' ? ['islam','kristen_protestan','katolik','hindu','budha','konghucu'] : ($key === 'daerah' ? ['papua','non_papua'] : ['umur_20_29','umur_30_39','umur_40_49','umur_50_59','umur_60_ke_atas'])); @endphp
                            @foreach($subs as $s) 
                                <td style="text-align: center;">{{ $row->{$s.'_l'} }}</td>
                                <td style="text-align: center;">{{ $row->{$s.'_p'} }}</td>
                                <td style="text-align: center; background-color: #eee;">{{ $row->{$s.'_jml'} }}</td> 
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
</div>
