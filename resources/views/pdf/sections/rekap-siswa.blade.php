<div class="pdf-section-block">
    @foreach (['Berdasarkan Kelas' => 'perKelas', 'Berdasarkan Umur' => 'perUmur', 'Berdasarkan Agama' => 'perAgama', 'Berdasarkan Daerah' => 'perDaerah', 'Berdasarkan Disabilitas' => 'disabilitas'] as $title => $key)
        @if (isset($data[$key]))
            @php
                $columns = match ($key) {
                    'perUmur' => collect(range(13, 23))
                        ->mapWithKeys(fn($age) => ["umur_{$age}" => "{$age} Tahun"])
                        ->all(),
                    'perAgama' => [
                        'islam' => 'Islam',
                        'kristen' => 'Kristen',
                        'katolik' => 'Katolik',
                        'hindu' => 'Hindu',
                        'buddha' => 'Buddha',
                        'khonghucu' => 'Konghucu',
                    ],
                    'perDaerah' => [
                        'papua' => 'Papua',
                        'non_papua' => 'Non Papua',
                    ],
                    'disabilitas' => [
                        'tidak' => 'Tidak',
                        'tuna_netra' => 'Tuna Netra',
                        'tuna_rungu' => 'Tuna Rungu',
                        'tuna_wicara' => 'Tuna Wicara',
                        'tuna_daksa' => 'Tuna Daksa',
                        'tuna_grahita' => 'Tuna Grahita',
                        'tuna_lainnya' => 'Tuna Lainnya',
                    ],
                    default => [],
                };

                $titleColspan = match ($key) {
                    'perKelas' => 20,
                    'disabilitas' => 5,
                    default => count($columns) * 3 + 5,
                };
            @endphp

            <table class="wide-table" style="{{ $key === 'perUmur' ? 'font-size: 7px !important;' : '' }}">
                <thead>
                    <tr>
                        <th colspan="{{ $titleColspan }}"
                            style="text-align: center; font-weight: bold; background-color: #f2f2f2;">
                            DATA SISWA {{ strtoupper($title) }}
                        </th>
                    </tr>
                    @if ($key === 'perKelas')
                        <tr>
                            <th rowspan="2" style="width: 20px;">NO</th>
                            <th rowspan="2">ROMBEL</th>
                            <th colspan="3">AWAL BULAN</th>
                            <th colspan="3">MUTASI MASUK</th>
                            <th colspan="3">MUTASI KELUAR</th>
                            <th colspan="3">PUTUS SEKOLAH</th>
                            <th colspan="3">MENGULANG</th>
                            <th colspan="3">AKHIR BULAN</th>
                        </tr>
                        <tr>
                            @for ($i = 0; $i < 6; $i++)
                                <th>L</th>
                                <th>P</th>
                                <th style="background-color: #eee;">JML</th>
                            @endfor
                        </tr>
                    @elseif(in_array($key, ['perUmur', 'perAgama', 'perDaerah']))
                        <tr>
                            <th rowspan="2" style="width: 20px; text-align: center;">NO</th>
                            <th rowspan="2" style="min-width: 70px;">ROMBEL</th>
                            @foreach ($columns as $label)
                                <th colspan="3" style="text-align: center;">{{ strtoupper($label) }}</th>
                            @endforeach
                            <th colspan="3" style="text-align: center; background-color: #e5e7eb;">TOTAL</th>
                        </tr>
                        <tr>
                            @foreach ($columns as $label)
                                <th style="text-align: center;">L</th>
                                <th style="text-align: center;">P</th>
                                <th style="text-align: center; background-color: #eee;">JML</th>
                            @endforeach
                            <th style="text-align: center; background-color: #e5e7eb;">L</th>
                            <th style="text-align: center; background-color: #e5e7eb;">P</th>
                            <th style="text-align: center; background-color: #d1d5db;">JML</th>
                        </tr>
                    @elseif($key === 'disabilitas')
                        <tr>
                            <th style="width: 20px; text-align: center;">NO</th>
                            <th style="min-width: 140px;">{{ strtoupper($title) }}</th>
                            <th style="text-align: center;">L</th>
                            <th style="text-align: center;">P</th>
                            <th style="text-align: center; background-color: #eee;">JML</th>
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @php
                        $grandTotals = collect($columns)
                            ->mapWithKeys(
                                fn($label, $field) => [
                                    $field => ['l' => 0, 'p' => 0, 'jml' => 0],
                                ],
                            )
                            ->all();

                        $grandTotalL = 0;
                        $grandTotalP = 0;
                        $grandTotalJml = 0;
                    @endphp

                    @foreach ($data[$key] as $idx => $row)
                        @if ($key === 'perKelas')
                            <tr>
                                <td style="text-align: center;">{{ $idx + 1 }}</td>
                                <td>{{ $row['nama_rombel'] }}</td>
                                @foreach (['awal_bulan', 'mutasi', 'mutasi_keluar', 'putus_sekolah', 'mengulang', 'akhir_bulan'] as $p)
                                    <td style="text-align: center;">{{ $row[$p . '_l'] }}</td>
                                    <td style="text-align: center;">{{ $row[$p . '_p'] }}</td>
                                    <td style="text-align: center; background-color: #eee;">{{ $row[$p . '_jml'] }}</td>
                                @endforeach
                            </tr>
                        @elseif(in_array($key, ['perUmur', 'perAgama', 'perDaerah']))
                            @php
                                $rowTotalL = 0;
                                $rowTotalP = 0;
                                $rowTotalJml = 0;
                            @endphp
                            <tr>
                                <td style="text-align: center;">{{ $idx + 1 }}</td>
                                <td>{{ $row['nama_rombel'] }}</td>
                                @foreach ($columns as $field => $label)
                                    @php
                                        $l = (int) ($row[$field . '_l'] ?? 0);
                                        $p = (int) ($row[$field . '_p'] ?? 0);
                                        $jml = (int) ($row[$field . '_jml'] ?? $l + $p);

                                        $rowTotalL += $l;
                                        $rowTotalP += $p;
                                        $rowTotalJml += $jml;

                                        $grandTotals[$field]['l'] += $l;
                                        $grandTotals[$field]['p'] += $p;
                                        $grandTotals[$field]['jml'] += $jml;
                                    @endphp
                                    <td style="text-align: center;">{{ $l }}</td>
                                    <td style="text-align: center;">{{ $p }}</td>
                                    <td style="text-align: center; background-color: #eee;">{{ $jml }}</td>
                                @endforeach

                                @php
                                    $grandTotalL += $rowTotalL;
                                    $grandTotalP += $rowTotalP;
                                    $grandTotalJml += $rowTotalJml;
                                @endphp
                                <td style="text-align: center; background-color: #e5e7eb; font-weight: bold;">
                                    {{ $rowTotalL }}</td>
                                <td style="text-align: center; background-color: #e5e7eb; font-weight: bold;">
                                    {{ $rowTotalP }}</td>
                                <td style="text-align: center; background-color: #d1d5db; font-weight: bold;">
                                    {{ $rowTotalJml }}</td>
                            </tr>
                        @elseif($key === 'disabilitas')
                            <tr>
                                <td style="text-align: center;">{{ $idx + 1 }}</td>
                                <td>{{ $row['jenis_disabilitas'] ?? '-' }}</td>
                                <td style="text-align: center;">{{ $row['laki_laki'] ?? 0 }}</td>
                                <td style="text-align: center;">{{ $row['perempuan'] ?? 0 }}</td>
                                <td style="text-align: center; background-color: #eee;">{{ $row['total'] ?? 0 }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                @if (in_array($key, ['perUmur', 'perAgama', 'perDaerah']))
                    <tfoot>
                        <tr>
                            <td colspan="2"
                                style="text-align: center; font-weight: bold; background-color: #f2f2f2;">TOTAL</td>
                            @foreach ($columns as $field => $label)
                                <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">
                                    {{ $grandTotals[$field]['l'] }}</td>
                                <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">
                                    {{ $grandTotals[$field]['p'] }}</td>
                                <td style="text-align: center; font-weight: bold; background-color: #e5e7eb;">
                                    {{ $grandTotals[$field]['jml'] }}</td>
                            @endforeach
                            <td style="text-align: center; font-weight: bold; background-color: #e5e7eb;">
                                {{ $grandTotalL }}</td>
                            <td style="text-align: center; font-weight: bold; background-color: #e5e7eb;">
                                {{ $grandTotalP }}</td>
                            <td style="text-align: center; font-weight: bold; background-color: #d1d5db;">
                                {{ $grandTotalJml }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        @endif
    @endforeach
</div>
