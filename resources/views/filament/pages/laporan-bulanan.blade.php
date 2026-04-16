<x-filament-panels::page>

    <style>
        label,
        button {
            font-size: .875rem;
        }
        
        .checklist-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1fr;
        }
        
        @media (min-width: 1024px) {
            .checklist-grid {
                grid-template-columns: repeat(4, 1fr);
            }
            .card-siswa { grid-column: 1; grid-row: 1 / span 2; }
            .card-gtk { grid-column: 2; grid-row: 1 / span 2; }
            .card-sarpras { grid-column: 3; grid-row: 1; }
            .card-sebaran { grid-column: 4; grid-row: 1; }
            .card-kehadiran { grid-column: 3; grid-row: 2; }
            .card-kelulusan { grid-column: 4; grid-row: 2; }
        }
    </style>
    <!-- Dashboard Header -->
    <div style="margin-bottom: 0rem;">
        <p style="color: #6b7280;">
            Pantau dan kelola semua data laporan bulanan sekolah Anda di satu tempat
        </p>
    </div>

    <!-- Dashboard Cards -->
    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 0rem;">
        <!-- GTK Card -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 0.5rem; border-left: 4px solid #3b82f6;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="background-color: #dbeafe; padding: 0.75rem; border-radius: 8px;">
                    <svg style="width: 24px; height: 24px; color: #1e40af;" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                    </svg>
                </div>
                <div>
                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Guru & Tenaga Kependidikan</p>
                    <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937; margin: 0;">
                        {{ \App\Models\Gtk::where('sekolah_id', auth()->user()->sekolah?->id)->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Siswa Card -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; border-left: 4px solid #10b981;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="background-color: #dcfce7; padding: 0.75rem; border-radius: 8px;">
                    <svg style="width: 24px; height: 24px; color: #047857;" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v2h8v-2zM2 8a2 2 0 11-4 0 2 2 0 014 0zM18 15v2h5v-2a4 4 0 00-5-3.87M9 11a6 6 0 0112 0v2H9v-2z" />
                    </svg>
                </div>
                <div>
                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Peserta Didik Aktif</p>
                    <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937; margin: 0;">
                        {{ \App\Models\Siswa::where('sekolah_id', auth()->user()->sekolah?->id)->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Rombel Card -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; border-left: 4px solid #a855f7;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="background-color: #e9d5ff; padding: 0.75rem; border-radius: 8px;">
                    <svg style="width: 24px; height: 24px; color: #7e22ce;" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                </div>
                <div>
                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Kelompok Belajar</p>
                    <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937; margin: 0;">
                        {{ \App\Models\Rombel::where('sekolah_id', auth()->user()->sekolah?->id)->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Sarpras Card -->
        <div
            style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; border-left: 4px solid #f59e0b;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="background-color: #fed7aa; padding: 0.75rem; border-radius: 8px;">
                    <svg style="width: 24px; height: 24px; color: #b45309;" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 012-2h6a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V3z" />
                    </svg>
                </div>
                <div>
                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Sarana Prasarana</p>
                    <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937; margin: 0;">
                        {{ \App\Models\LaporanGedung::whereHas('laporan', function ($query) {
                            $query->where('sekolah_id', auth()->user()->sekolah?->id);
                        })->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div
        style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 0.5rem;">
        <h2 style="font-size: 1rem; font-weight: bold; margin-bottom: 1rem; color: #1f2937;">Progres Laporan:</h2>
        <div style="background-color: #e5e7eb; border-radius: 4px; height: 24px; overflow: hidden; position: relative;">
            <div id="progressBar"
                style="background-color: #f97316; height: 24px; border-radius: 4px; transition: width 0.3s ease; width: 0%; display: flex; align-items: center; justify-content: center;">
                <span id="progressText"
                    style="color: white; font-size: 0.75rem; font-weight: bold; margin: 0;">0%</span>
            </div>
        </div>
    </div>

    <!-- Checklist Form Section -->
    <form id="reportForm"
        style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 0.5rem;">
        <h2 style="font-size: 1rem; font-weight: bold; margin-bottom: 1.5rem; color: #1f2937;">Checklist Pembaruan
            Laporan Bulanan</h2>

        <div class="checklist-grid">
            @foreach ($this->groups as $groupLabel => $items)
                @php
                    $doneInGroup = collect($items)->filter(fn($key) => $this->checklistStatus[$key] ?? false)->count();
                    $totalInGroup = count($items);
                    $isGroupDone = $doneInGroup === $totalInGroup;

                    $cardClass = '';
                    if ($groupLabel === 'Keadaan Siswa') $cardClass = 'card-siswa';
                    elseif ($groupLabel === 'Keadaan GTK') $cardClass = 'card-gtk';
                    elseif ($groupLabel === 'Sarpras') $cardClass = 'card-sarpras';
                    elseif ($groupLabel === 'Sebaran Jam') $cardClass = 'card-sebaran';
                    elseif ($groupLabel === 'Kehadiran') $cardClass = 'card-kehadiran';
                    elseif ($groupLabel === 'Kelulusan') $cardClass = 'card-kelulusan';
                @endphp
                <div class="{{ $cardClass }}" style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem;">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 2px solid {{ $isGroupDone ? '#10b981' : '#e5e7eb' }}; padding-bottom: 0.5rem;">
                        <h3 style="font-size: 0.9rem; font-weight: 700; color: #374151; margin: 0;">{{ $groupLabel }}</h3>
                        <span style="font-size: 0.75rem; font-weight: 600; color: {{ $isGroupDone ? '#059669' : '#6b7280' }};">
                            {{ $doneInGroup }}/{{ $totalInGroup }} Valid
                        </span>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @foreach ($items as $key)
                            @php
                                $label = $this->checklist[$key] ?? $key;
                                $isDone = $this->checklistStatus[$key] ?? false;
                            @endphp
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.25rem 0;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" id="checkbox-{{ $key }}" 
                                        {{ $isDone ? 'checked' : '' }}
                                        disabled
                                        value="{{ $key }}"
                                        class="report-checkbox"
                                        style="width: 16px; height: 16px; cursor: default; accent-color: #10b981;">
                                    <label for="checkbox-{{ $key }}"
                                        style="margin: 0; font-size: 0.8rem; color: {{ $isDone ? '#111827' : '#6b7280' }};">
                                        {{ $label }}
                                    </label>
                                </div>
                                @if($isDone)
                                    <svg style="width: 16px; height: 16px; color: #10b981;" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </form>

    <!-- Action Buttons -->
    <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
        <button type="button" id="previewBtn"
            style="flex: 1; background-color: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; border: none; font-weight: 500; cursor: pointer; transition: background-color 0.2s; font-size: 0.75rem;">
            PRATINJAU LAPORAN BULANAN
        </button>
        <button type="button" id="submitBtn"
            style="flex: 1; background-color: #10b981; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; border: none; font-weight: 500; cursor: pointer; transition: background-color 0.2s; font-size: 0.75rem;">
            KIRIMI LAPORAN BULANAN
        </button>
    </div>

    <!-- Modals for each checklist item -->
    @foreach ($this->checklist as $key => $label)
        @if ($this->checklistStatus[$key] ?? false)
            <div id="modal-{{ $key }}" role="dialog"
                style="display: none; position: fixed; inset: 0; z-index: 50; align-items: center; justify-content: center; background-color: rgba(0,0,0,0.5);">
                <div
                    style="background-color: white; border-radius: 8px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); width: 100%; max-width: 40rem; max-height: 90vh; overflow: hidden; margin: 0 1rem; display: flex; flex-direction: column;">
                    <!-- Modal Header -->
                    <div
                        style="border-bottom: 1px solid #e5e7eb; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; background-color: #f9fafb;">
                        <h2 style="font-size: 1.125rem; font-weight: bold; color: #1f2937; margin: 0;">
                            {{ $label }}</h2>
                        <button type="button" class="modal-close" data-key="{{ $key }}"
                            style="background: none; border: none; color: #9ca3af; cursor: pointer; padding: 0; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div style="flex: 1; overflow-y: auto; padding: 1.5rem;">
                        @php
                            $previewData = $this->getChecklistPreviewData($key);
                        @endphp

                        @if (empty($previewData))
                            <div style="text-align: center; padding: 2rem 0;">
                                <p style="color: #6b7280;">Tidak ada data untuk ditampilkan</p>
                            </div>
                        @else
                            @if ($key === 'identitas_sekolah')
                                <div style="overflow-x: auto; border: 1px solid #e5e7eb; border-radius: 8px;">
                                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem; text-align: left;">
                                        <tbody>
                                            @foreach ($previewData as $item)
                                                <tr style="border-bottom: 1px solid #e5e7eb; {{ $loop->even ? 'background-color: #f9fafb;' : 'background-color: white;' }}">
                                                    <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151; width: 30%; border-right: 1px solid #e5e7eb;">
                                                        {{ $item['label'] }}
                                                    </th>
                                                    <td style="padding: 0.75rem 1rem; color: #1f2937;">
                                                        {{ $item['value'] }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                @php
                                    $firstDetails = collect($previewData)->first()['details'] ?? [];
                                    $headers = is_array($firstDetails) ? array_keys($firstDetails) : ['Keterangan'];
                                @endphp
                                <div style="overflow-x: auto; border: 1px solid #e5e7eb; border-radius: 8px;">
                                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem; text-align: left;">
                                        <thead>
                                            <tr style="background-color: #f3f4f6; border-bottom: 2px solid #d1d5db;">
                                                <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151; border-right: 1px solid #e5e7eb; width: 50px; text-align: center;">No.</th>
                                                <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151; border-right: 1px solid #e5e7eb;">Nama Lengkap / Rincian</th>
                                                @foreach ($headers as $header)
                                                    <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151; border-right: 1px solid #e5e7eb;">{{ $header }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($previewData as $index => $item)
                                                <tr style="border-bottom: 1px solid #e5e7eb; {{ $loop->even ? 'background-color: #f9fafb;' : 'background-color: white;' }} hover:background-color: #f3f4f6;">
                                                    <td style="padding: 0.75rem 1rem; color: #6b7280; text-align: center; border-right: 1px solid #e5e7eb;">{{ $index + 1 }}</td>
                                                    <td style="padding: 0.75rem 1rem; font-weight: 500; color: #1f2937; border-right: 1px solid #e5e7eb;">{{ $item['label'] }}</td>
                                                    @if (is_array($item['details']))
                                                        @foreach ($headers as $header)
                                                            <td style="padding: 0.75rem 1rem; color: #4b5563; border-right: 1px solid #e5e7eb;">{{ $item['details'][$header] ?? '-' }}</td>
                                                        @endforeach
                                                    @else
                                                        <td style="padding: 0.75rem 1rem; color: #4b5563;" colspan="{{ count($headers) }}">{{ $item['details'] }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        @endif
                    </div>

                    <!-- Modal Footer -->
                    <div style="border-top: 1px solid #e5e7eb; padding: 1.5rem; background-color: #f9fafb;">
                        <button type="button" class="modal-close" data-key="{{ $key }}"
                            style="width: 100%; background-color: #e5e7eb; color: #1f2937; padding: 0.75rem; border-radius: 6px; border: none; font-weight: 500; cursor: pointer; transition: background-color 0.2s;">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <div id="preview-modal" role="dialog"
        style="display:none; position:fixed; inset:0; z-index:60; align-items:center; justify-content:center; background-color: rgba(0,0,0,0.5);">

        <div
            style="background-color:white; border-radius:8px; width:100%; max-width:60rem; max-height:90vh; overflow:hidden; display:flex; flex-direction:column;">

            <!-- Header -->
            <div
                style="padding:1rem 1.5rem; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between;">
                <h3 style="font-weight:600;">Pratinjau Laporan Bulanan</h3>
                <button id="preview-modal-close"
                    style="border:none;background:none;cursor:pointer;font-size:18px;">✕</button>
            </div>

            <!-- Body -->
            <div id="preview-modal-body" style="overflow:auto; padding:1.5rem;"></div>

            <!-- Footer -->
            <div style="padding:1rem; border-top:1px solid #e5e7eb; display:flex; gap:1rem;">
                <button type="button" id="preview-modal-pdf"
                    style="flex:1; background:#ef4444; color:white; padding:0.75rem; border:none; border-radius:6px; font-weight:500; cursor:pointer; transition:background-color 0.2s;">
                    Unduh PDF
                </button>
                <button type="button" id="preview-modal-close-2"
                    style="flex:1; background:#e5e7eb; padding:0.75rem; border:none; border-radius:6px; font-weight:500; cursor:pointer; transition:background-color 0.2s;">
                    Tutup
                </button>
            </div>

        </div>
    </div>

</x-filament-panels::page>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        const totalItems = {{ count($this->checklist) }};
        const checkboxes = document.querySelectorAll('.report-checkbox');

        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        const modalCloseButtons = document.querySelectorAll('.modal-close');
        const previewBtn = document.getElementById('previewBtn');
        const submitBtn = document.getElementById('submitBtn');

        // update progress
        function updateProgress() {
            const checkedCount = document.querySelectorAll('.report-checkbox:checked').length;
            const percentage = Math.round((checkedCount / totalItems) * 100);

            progressBar.style.width = percentage + '%';
            progressText.textContent = percentage + '%';
        }

        // Remove manual change listeners as they are disabled

        // close modal lama
        modalCloseButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const key = this.getAttribute('data-key');
                const modal = document.getElementById('modal-' + key);
                if (modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        });

        // close klik luar
        document.querySelectorAll('[role="dialog"]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        });

        // PREVIEW GABUNGAN
        previewBtn.addEventListener('click', function() {

            const checkedItems = document.querySelectorAll('.report-checkbox:checked');

            if (checkedItems.length === 0) {
                alert('Silakan pilih minimal satu item untuk pratinjau');
                return;
            }

            const previewBody = document.getElementById('preview-modal-body');
            previewBody.innerHTML = '';

            checkedItems.forEach(checkbox => {

                const key = checkbox.value;
                const modal = document.getElementById('modal-' + key);

                const sectionColors = {
                    identitas_sekolah: '#dbeafe',
                    gtk: '#dcfce7',
                    siswa: '#fef3c7',
                    rombel: '#ede9fe',
                    sarpras: '#fee2e2',
                    kurikulum: '#cffafe',
                    keuangan: '#fce7f3',
                };

                if (modal) {

                    const title = modal.querySelector('h2').innerHTML;
                    const content = modal.querySelector('[style*="overflow-y: auto"]')
                        .innerHTML;

                    const bgColor = sectionColors[key] || '#f3f4f6';

                    previewBody.innerHTML += `
                        <div style="margin-bottom:1.5rem; border:1px solid #e5e7eb; border-radius:8px; overflow:hidden;">
                            <div style="background:${bgColor}; padding:0.75rem 1rem; font-weight:600;">
                                ${title}
                            </div>
                            <div style="padding:1rem;">
                                ${content}
                            </div>
                        </div>
                    `;
                }
            });

            document.getElementById('preview-modal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });

        // download pdf
        document.getElementById('preview-modal-pdf').addEventListener('click', function() {
            const element = document.getElementById('preview-modal-body');
            
            // Setup PDF options - dioptimalkan untuk kecepatan
            const opt = {
                margin:       [10, 10, 10, 10],
                filename:     'Laporan_Bulanan_Review.pdf',
                image:        { type: 'jpeg', quality: 0.85 }, // Sedikit diturunkan agar lebih cepat
                html2canvas:  { scale: 1.5, useCORS: false, logging: false }, // Skala dikecilkan dari 2 ke 1.5 (menghemat waktu proses render 2x lipat)
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape', compress: true } // Kompresi dinyalakan
            };
            
            const btn = this;
            const originalText = btn.innerHTML;
            
            // Inject animated spinner SVG
            const spinnerSvg = `<svg style="animation: spin 1s linear infinite; display: inline-block; width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
            
            // Ensure @keyframes spin is available for inline style
            if (!document.getElementById('spin-keyframes')) {
                const style = document.createElement('style');
                style.id = 'spin-keyframes';
                style.innerHTML = `@keyframes spin { 100% { transform: rotate(360deg); } }`;
                document.head.appendChild(style);
            }

            btn.innerHTML = `<div style="display: flex; align-items: center; justify-content: center;">${spinnerSvg} Memproses Dokumen...</div>`;
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.style.cursor = 'not-allowed';

            html2pdf().set(opt).from(element).save().then(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
            });
        });

        // close preview modal
        document.getElementById('preview-modal-close').addEventListener('click', closePreview);
        document.getElementById('preview-modal-close-2').addEventListener('click', closePreview);

        function closePreview() {

            document.getElementById('preview-modal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // submit
        submitBtn.addEventListener('click', function() {
            const checkedItems = document.querySelectorAll('.report-checkbox:checked');

            if (checkedItems.length !== totalItems) {
                alert('Semua item harus dicentang sebelum mengirim laporan');
                return;
            }

            alert('Laporan berhasil dikirim!');
        });

        updateProgress();

    });
</script>
