<div style="padding: 2rem;">
    <h1 style="font-size: 1.875rem; font-weight: bold; margin-bottom: 0.5rem;">Analisis Keadaan Siswa</h1>
    <p style="color: #6b7280; margin-bottom: 2rem;">Menampilkan berbagai data siswa dari berbagai aspek</p>

    <div style="display: grid; gap: 2rem;">
        <!-- Tabel 1: Siswa Berdasarkan Kelas/Rombel -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background-color: #dbeafe; padding: 1.5rem; border-bottom: 1px solid #93c5fd;">
                <h2 style="font-size: 1.125rem; font-weight: bold; color: #1e3a8a;">Jumlah Siswa Berdasarkan Kelas/Rombel
                </h2>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; font-size: 0.875rem; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f3f4f6;">
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                NO</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; font-weight: bold;">
                                KELAS/ROMBEL</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                AWAL BULAN</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                MUTASI</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                AKHIR BULAN</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                PINDAH SEKOLAH</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                MENGULANG</th>
                        </tr>
                        <tr style="background-color: #f9fafb;">
                            <th colspan="2"></th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                L</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                P</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                JML</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                L</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                P</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                JML</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                L</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                P</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                JML</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                L</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                P</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                JML</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                L</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                P</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                JML</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswaPerKelas as $item)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem;">{{ $item->nama_rombel }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->awal_bulan_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->awal_bulan_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->awal_bulan_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->mutasi_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->mutasi_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->mutasi_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->akhir_bulan_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->akhir_bulan_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->akhir_bulan_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->pindah_sekolah_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->pindah_sekolah_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->pindah_sekolah_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->mengulang_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->mengulang_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->mengulang_jml }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="17"
                                    style="border: 1px solid #e5e7eb; padding: 1rem; text-align: center; color: #6b7280;">
                                    Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel 2: Siswa Menurut Umur -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background-color: #dcfce7; padding: 1.5rem; border-bottom: 1px solid #86efac;">
                <h2 style="font-size: 1.125rem; font-weight: bold; color: #15803d;">Jumlah Siswa Menurut Umur</h2>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; font-size: 0.875rem; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f3f4f6;">
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                NO</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; font-weight: bold;">
                                KELAS/ROMBEL</th>
                            @for ($age = 13; $age <= 23; $age++)
                                <th colspan="3"
                                    style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                    {{ $age }}</th>
                            @endfor
                        </tr>
                        <tr style="background-color: #f9fafb;">
                            <th colspan="2"></th>
                            @for ($age = 13; $age <= 23; $age++)
                                <th
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    L</th>
                                <th
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    P</th>
                                <th
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    JML</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswaPerUmur as $item)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem;">{{ $item->nama_rombel }}</td>
                                @for ($age = 13; $age <= 23; $age++)
                                    @php $prefix = 'umur_' . $age; @endphp
                                    <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                        {{ $item->{$prefix . '_l'} ?? 0 }}</td>
                                    <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                        {{ $item->{$prefix . '_p'} ?? 0 }}</td>
                                    <td
                                        style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                        {{ $item->{$prefix . '_jml'} ?? 0 }}</td>
                                @endfor
                            </tr>
                        @empty
                            <tr>
                                <td colspan="35"
                                    style="border: 1px solid #e5e7eb; padding: 1rem; text-align: center; color: #6b7280;">
                                    Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel 3: Siswa Menurut Agama -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background-color: #e9d5ff; padding: 1.5rem; border-bottom: 1px solid #d8b4fe;">
                <h2 style="font-size: 1.125rem; font-weight: bold; color: #6b21a8;">Jumlah Siswa Menurut Agama</h2>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; font-size: 0.875rem; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f3f4f6;">
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                NO</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; font-weight: bold;">
                                KELAS/ROMBEL</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                ISLAM</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                KRISTEN PROTESTAN</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                KATOLIK</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                HINDU</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                BUDHA</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                KONGHUCU</th>
                        </tr>
                        <tr style="background-color: #f9fafb;">
                            <th colspan="2"></th>
                            @for ($i = 0; $i < 6; $i++)
                                <th
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    L</th>
                                <th
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    P</th>
                                <th
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    JML</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswaPerAgama as $item)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem;">{{ $item->nama_rombel }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->islam_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->islam_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->islam_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->kristen_protestan_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->kristen_protestan_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->kristen_protestan_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->katolik_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->katolik_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->katolik_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->hindu_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->hindu_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->hindu_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->budha_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->budha_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->budha_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->konghucu_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->konghucu_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->konghucu_jml }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="20"
                                    style="border: 1px solid #e5e7eb; padding: 1rem; text-align: center; color: #6b7280;">
                                    Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel 4: Siswa Menurut Daerah Asal -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background-color: #fed7aa; padding: 1.5rem; border-bottom: 1px solid #fdba74;">
                <h2 style="font-size: 1.125rem; font-weight: bold; color: #92400e;">Jumlah Siswa Menurut Daerah Asal
                </h2>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; font-size: 0.875rem; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f3f4f6;">
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                NO</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; font-weight: bold;">
                                KELAS/ROMBEL</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                PAPUA</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                NON-PAPUA</th>
                        </tr>
                        <tr style="background-color: #f9fafb;">
                            <th colspan="2"></th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                L</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                P</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                JML</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                L</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                P</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                JML</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswaPDaerah as $item)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem;">{{ $item->nama_rombel }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->papua_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->papua_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->papua_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->non_papua_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->non_papua_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->non_papua_jml }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8"
                                    style="border: 1px solid #e5e7eb; padding: 1rem; text-align: center; color: #6b7280;">
                                    Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel 5: Siswa Disabilitas -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background-color: #fecaca; padding: 1.5rem; border-bottom: 1px solid #fca5a5;">
                <h2 style="font-size: 1.125rem; font-weight: bold; color: #991b1b;">Jumlah Siswa Disabilitas</h2>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; font-size: 0.875rem; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f3f4f6;">
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                NO</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; font-weight: bold;">
                                KELAS/ROMBEL</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                TUNA RUNGU</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                NETRA</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                WICARA</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                DAKSA</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                GRAHITA</th>
                            <th colspan="3"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                LAINNYA</th>
                        </tr>
                        <tr style="background-color: #f9fafb;">
                            <th colspan="2"></th>
                            @for ($i = 0; $i < 6; $i++)
                                <th
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    L</th>
                                <th
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    P</th>
                                <th
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    JML</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswaDisabilitas as $item)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem;">{{ $item->nama_rombel }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->tuna_rungu_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->tuna_rungu_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->tuna_rungu_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->netra_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->netra_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->netra_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->wicara_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->wicara_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->wicara_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->daksa_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->daksa_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->daksa_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->grahita_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->grahita_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->grahita_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->lainnya_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $item->lainnya_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $item->lainnya_jml }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="20"
                                    style="border: 1px solid #e5e7eb; padding: 1rem; text-align: center; color: #6b7280;">
                                    Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel 6: Siswa Penerima Beasiswa -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background-color: #e0e7ff; padding: 1.5rem; border-bottom: 1px solid #c7d2fe;">
                <h2 style="font-size: 1.125rem; font-weight: bold; color: #3730a3;">Jumlah Siswa Penerima Beasiswa</h2>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; font-size: 0.875rem; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f3f4f6;">
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                NO</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; font-weight: bold;">
                                JENIS BEASISWA</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                L</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                P</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                JML</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; font-weight: bold;">
                                KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswaBeasiswa as $item)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem;">{{ $item->jenis_beasiswa }}
                                </td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $item->penerima_l }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $item->penerima_p }}</td>
                                <td
                                    style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                    {{ $item->penerima_jml }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem;">{{ $item->keterangan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    style="border: 1px solid #e5e7eb; padding: 1rem; text-align: center; color: #6b7280;">
                                    Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
