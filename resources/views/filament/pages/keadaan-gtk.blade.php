<div style="padding: 2rem;">
    <h1 style="font-size: 1.875rem; font-weight: bold; margin-bottom: 0.5rem;">Analisis Keadaan GTK</h1>
    <p style="color: #6b7280; margin-bottom: 2rem;">Menampilkan berbagai data Guru dan Tenaga Kependidikan dari berbagai
        aspek</p>

    <div style="display: grid; gap: 2rem;">
        <!-- Tabel 1: GTK Menurut Agama -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background-color: #dbeafe; padding: 1.5rem; border-bottom: 1px solid #93c5fd;">
                <h2 style="font-size: 1.125rem; font-weight: bold; color: #1e3a8a;">Jumlah GTK Menurut Agama</h2>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; font-size: 0.875rem; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f3f4f6;">
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                <input type="checkbox" id="selectAllAgama" onchange="toggleAll('agama')">
                            </th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                NO</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; font-weight: bold;">
                                JENIS GTK</th>
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
                        @forelse ($gtkAgama as $item)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    <input type="checkbox" class="item-checkbox agama-checkbox"
                                        value="agama-{{ $item->id }}" onchange="updatePreview()">
                                </td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem;">{{ $item->jenis_gtk }}</td>
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

        <!-- Tabel 2: GTK Menurut Daerah Asal -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background-color: #dcfce7; padding: 1.5rem; border-bottom: 1px solid #86efac;">
                <h2 style="font-size: 1.125rem; font-weight: bold; color: #15803d;">Jumlah GTK Menurut Daerah Asal</h2>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; font-size: 0.875rem; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f3f4f6;">
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                <input type="checkbox" id="selectAllDaerah" onchange="toggleAll('daerah')">
                            </th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                NO</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; font-weight: bold;">
                                JENIS GTK</th>
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
                        @forelse ($gtkDaerah as $item)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    <input type="checkbox" class="item-checkbox daerah-checkbox"
                                        value="daerah-{{ $item->id }}" onchange="updatePreview()">
                                </td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem;">{{ $item->jenis_gtk }}</td>
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

        <!-- Tabel 3: GTK Menurut Status Kepegawaian -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background-color: #e9d5ff; padding: 1.5rem; border-bottom: 1px solid #d8b4fe;">
                <h2 style="font-size: 1.125rem; font-weight: bold; color: #6b21a8;">Jumlah GTK Menurut Status
                    Kepegawaian</h2>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; font-size: 0.875rem; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f3f4f6;">
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                <input type="checkbox" id="selectAllStatus" onchange="toggleAll('status')">
                            </th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                NO</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; font-weight: bold;">
                                JENIS GTK</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                PNS</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                PPPK</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                HONORER SEKOLAH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gtkStatusKepegawaian as $item)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    <input type="checkbox" class="item-checkbox status-checkbox"
                                        value="status-{{ $item->id }}" onchange="updatePreview()">
                                </td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem;">{{ $item->jenis_gtk }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $item->pns }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $item->pppk }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $item->honorer_sekolah }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    style="border: 1px solid #e5e7eb; padding: 1rem; text-align: center; color: #6b7280;">
                                    Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel 4: GTK Menurut Umur -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background-color: #fed7aa; padding: 1.5rem; border-bottom: 1px solid #fdba74;">
                <h2 style="font-size: 1.125rem; font-weight: bold; color: #92400e;">Jumlah GTK Menurut Umur</h2>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; font-size: 0.75rem; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f3f4f6;">
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                <input type="checkbox" id="selectAllUmur" onchange="toggleAll('umur')">
                            </th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                NO</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: left; font-weight: bold;">
                                JENIS GTK</th>
                            @for ($age = 13; $age <= 23; $age++)
                                <th colspan="3"
                                    style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center; font-weight: bold;">
                                    {{ $age }}</th>
                            @endfor
                        </tr>
                        <tr style="background-color: #f9fafb;">
                            <th colspan="2"></th>
                            @for ($age = 13; $age <= 23; $age++)
                                <th
                                    style="border: 1px solid #e5e7eb; padding: 0.25rem; text-align: center; font-weight: bold;">
                                    L</th>
                                <th
                                    style="border: 1px solid #e5e7eb; padding: 0.25rem; text-align: center; font-weight: bold;">
                                    P</th>
                                <th
                                    style="border: 1px solid #e5e7eb; padding: 0.25rem; text-align: center; font-weight: bold;">
                                    JML</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gtkUmur as $item)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    <input type="checkbox" class="item-checkbox umur-checkbox"
                                        value="umur-{{ $item->id }}" onchange="updatePreview()">
                                </td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.5rem;">{{ $item->jenis_gtk }}</td>
                                @for ($age = 13; $age <= 23; $age++)
                                    @php $prefix = 'umur_' . $age; @endphp
                                    <td
                                        style="border: 1px solid #e5e7eb; padding: 0.25rem; text-align: center; font-size: 0.7rem;">
                                        {{ $item->{$prefix . '_l'} ?? 0 }}</td>
                                    <td
                                        style="border: 1px solid #e5e7eb; padding: 0.25rem; text-align: center; font-size: 0.7rem;">
                                        {{ $item->{$prefix . '_p'} ?? 0 }}</td>
                                    <td
                                        style="border: 1px solid #e5e7eb; padding: 0.25rem; text-align: center; font-weight: bold; font-size: 0.7rem;">
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

        <!-- Tabel 5: GTK Menurut Pendidikan Terakhir -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <div style="background-color: #fecaca; padding: 1.5rem; border-bottom: 1px solid #fca5a5;">
                <h2 style="font-size: 1.125rem; font-weight: bold; color: #991b1b;">Jumlah GTK Menurut Pendidikan
                    Terakhir</h2>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; font-size: 0.875rem; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f3f4f6;">
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                <input type="checkbox" id="selectAllPendidikan" onchange="toggleAll('pendidikan')">
                            </th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                NO</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: left; font-weight: bold;">
                                JENIS GTK</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                SLTA</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                DI</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                DII</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                DIII</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                S1</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                S2</th>
                            <th
                                style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center; font-weight: bold;">
                                S3</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gtkPendidikan as $item)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    <input type="checkbox" class="item-checkbox pendidikan-checkbox"
                                        value="pendidikan-{{ $item->id }}" onchange="updatePreview()">
                                </td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem;">{{ $item->jenis_gtk }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $item->slta }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $item->di }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $item->dii }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $item->diii }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $item->s1 }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $item->s2 }}</td>
                                <td style="border: 1px solid #e5e7eb; padding: 0.75rem; text-align: center;">
                                    {{ $item->s3 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9"
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

<script>
    function toggleAll(table) {
        const selectAllCheckbox = document.getElementById('selectAll' + capitalize(table));
        const checkboxes = document.querySelectorAll('.' + table + '-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
        updatePreview();
    }

    function updatePreview() {
        const selectedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
        const selectedValues = Array.from(selectedCheckboxes).map(cb => cb.value);

        if (selectedValues.length === 0) {
            const previewSection = document.querySelector('.preview-section');
            if (previewSection) previewSection.style.display = 'none';
            return;
        }

        // For simplicity, preview the first selected
        const firstSelected = selectedValues[0];
        const [table, id] = firstSelected.split('-');

        // Here you would make an AJAX call to get the data
        // For now, just show a placeholder
        let previewSection = document.querySelector('.preview-section');
        if (!previewSection) {
            previewSection = document.createElement('div');
            previewSection.className = 'preview-section';
            previewSection.style =
                'margin-top: 2rem; background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem;';
            document.querySelector('div[style*="padding: 2rem"]').appendChild(previewSection);
        }
        previewSection.style.display = 'block';
        previewSection.innerHTML =
            '<h3 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem;">Preview Data</h3><p>Preview for ' +
            table + ' item ID: ' + id + '</p>';
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>
</div>
