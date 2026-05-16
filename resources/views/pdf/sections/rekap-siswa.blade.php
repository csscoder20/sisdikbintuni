<div style="margin-bottom: 20px;">
    @foreach(['Per Kelas' => 'perKelas', 'Per Umur' => 'perUmur', 'Per Agama' => 'perAgama', 'Per Daerah' => 'perDaerah'] as $title => $key)
        @if(isset($data[$key]))
            <p style="font-weight: bold; margin-bottom: 5px; font-size: 10px;">DATA SISWA {{ strtoupper($title) }}</p>
            <table class="wide-table">
                <thead>
                    @if($key === 'perKelas')
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
                            @for($i=0;$i<6;$i++) <th>L</th><th>P</th><th style="background-color: #eee;">JML</th> @endfor
                        </tr>
                    @else
                        <!-- Simplified other tables for PDF -->
                        <tr>
                            <th>NO</th>
                            <th>ROMBEL</th>
                            <th colspan="100">Rincian Data (Disederhanakan untuk PDF)</th>
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @foreach($data[$key] as $idx => $row)
                        @if($key === 'perKelas')
                            <tr>
                                <td style="text-align: center;">{{ $idx + 1 }}</td>
                                <td>{{ $row['nama_rombel'] }}</td>
                                @foreach(['awal_bulan', 'mutasi', 'mutasi_keluar', 'putus_sekolah', 'mengulang', 'akhir_bulan'] as $p)
                                    <td style="text-align: center;">{{ $row[$p.'_l'] }}</td>
                                    <td style="text-align: center;">{{ $row[$p.'_p'] }}</td>
                                    <td style="text-align: center; background-color: #eee;">{{ $row[$p.'_jml'] }}</td>
                                @endforeach
                            </tr>
                        @else
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $row['nama_rombel'] }}</td>
                                <td colspan="100">Data tersedia di versi lengkap</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
</div>
