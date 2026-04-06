<x-filament-panels::page>

    <style>
        label,
        button {
            font-size: .875rem;
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
                        {{ \App\Models\Gtk::where('id_sekolah', auth()->user()->sekolah?->id)->count() }}
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
                        {{ \App\Models\Siswa::whereHas('rombel', fn($q) => $q->where('id_sekolah', auth()->user()->sekolah?->id))->count() }}
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
                        {{ \App\Models\Rombel::where('id_sekolah', auth()->user()->sekolah?->id)->count() }}
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
                        {{ \App\Models\Sarpras::where('id_sekolah', auth()->user()->sekolah?->id)->count() }}
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

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0rem;">

            <!-- Checkbox Semua -->
            <div
                style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 0; grid-column: span 3; border-bottom:1px solid #e5e7eb; margin-bottom:0.5rem;">
                <input type="checkbox" id="checkbox-all"
                    style="width: 20px; height: 20px; cursor: pointer; accent-color: #10b981;">
                <label for="checkbox-all"
                    style="margin: 0; cursor: pointer; flex: 1; color: #1f2937; font-weight: 600;">
                    Semua
                </label>
            </div>

            @foreach ($this->checklist as $key => $label)
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 0;">
                    <input type="checkbox" id="checkbox-{{ $key }}" name="report_items[]"
                        value="{{ $key }}" class="report-checkbox"
                        style="width: 20px; height: 20px; cursor: pointer; accent-color: #3b82f6;">
                    <label for="checkbox-{{ $key }}"
                        style="margin: 0; cursor: pointer; flex: 1; color: #1f2937; font-weight: 500;">
                        {{ $label }}
                    </label>
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
                                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                    @foreach ($previewData as $item)
                                        <div
                                            style="display: flex; justify-content: space-between; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.75rem;">
                                            <p style="font-weight: 500; color: #4b5563; margin: 0;">
                                                {{ $item['label'] }}</p>
                                            <p style="color: #1f2937; margin: 0;">{{ $item['value'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                    @foreach ($previewData as $index => $item)
                                        <div style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 1rem;">
                                            <h4 style="font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;">
                                                {{ $index + 1 }}. {{ $item['label'] }}
                                            </h4>
                                            @if (is_array($item['details']))
                                                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                                    @foreach ($item['details'] as $detailKey => $detailValue)
                                                        <div
                                                            style="display: flex; justify-content: space-between; font-size: 0.875rem;">
                                                            <span style="color: #6b7280;">{{ $detailKey }}</span>
                                                            <span
                                                                style="font-weight: 500; color: #1f2937;">{{ $detailValue }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p style="color: #4b5563; margin: 0;">{{ $item['details'] }}</p>
                                            @endif
                                        </div>
                                    @endforeach
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
            <div style="padding:1rem; border-top:1px solid #e5e7eb;">
                <button id="preview-modal-close-2"
                    style="width:100%; background:#e5e7eb; padding:0.75rem; border:none; border-radius:6px;">
                    Tutup
                </button>
            </div>

        </div>
    </div>

</x-filament-panels::page>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const totalItems = {{ count($this->checklist) }};
        const checkboxes = document.querySelectorAll('.report-checkbox');
        const checkboxAll = document.getElementById('checkbox-all');

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

            checkboxAll.checked = checkedCount === checkboxes.length;
        }

        // checkbox semua
        checkboxAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = checkboxAll.checked);
            updateProgress();
        });

        // checkbox satuan
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateProgress);
        });

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
