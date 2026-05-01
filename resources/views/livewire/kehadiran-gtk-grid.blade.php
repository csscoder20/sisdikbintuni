<div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <style>
        .grid-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f3f4f6;
        }
        .grid-title-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .grid-title h2 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
        }
        .grid-title p {
            margin: 0;
            font-size: 0.75rem;
            color: #6b7280;
        }
        .grid-controls {
            display: flex;
            gap: 12px;
            background: #f9fafb;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #f3f4f6;
        }
        .control-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .control-group label {
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #9ca3af;
        }
        .control-group select {
            border: none;
            background: transparent;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            cursor: pointer;
            outline: none;
        }
        .table-wrapper {
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            position: relative;
        }
        .att-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.75rem;
        }
        .att-table th {
            background: #f9fafb;
            padding: 8px;
            font-weight: 600;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
            border-right: 1px solid #f3f4f6;
            color: #4b5563;
        }
        .att-table td {
            padding: 0;
            border-bottom: 1px solid #f3f4f6;
            border-right: 1px solid #f3f4f6;
        }
        .sticky-no {
            position: sticky;
            left: 0;
            width: 40px;
            z-index: 40;
            background-color: #f9fafb !important;
            text-align: center !important;
            border-right: 1px solid #e5e7eb !important;
        }
        .sticky-name {
            position: sticky;
            left: 40px;
            min-width: 200px;
            z-index: 40;
            background-color: #f9fafb !important;
            text-align: left !important;
            padding-left: 12px !important;
            border-right: 2px solid #e5e7eb !important;
            box-shadow: 4px 0 4px -2px rgba(0,0,0,0.05);
        }
        .att-table td.sticky-no {
            background-color: white !important;
            z-index: 30;
        }
        .att-table td.sticky-name {
            background-color: white !important;
            z-index: 30;
        }
        .day-header {
            width: 30px;
            font-size: 0.7rem;
        }
        .sunday-bg {
            background-color: #ef4444 !important;
            color: white !important;
        }
        .sunday-cell {
            background-color: #ef4444 !important;
            border-right-color: #fca5a5 !important;
        }
        .sunday-cell-active {
            background-color: #fecaca !important;
        }
        .att-input {
            width: 100%;
            height: 34px;
            border: none;
            text-align: center;
            background: transparent;
            font-size: 0.8rem;
            outline: none;
            cursor: pointer;
            text-transform: uppercase;
        }
        .att-input:focus {
            background: #dbeafe;
        }
        .att-input:disabled {
            background: transparent;
            cursor: not-allowed;
            color: white;
            font-weight: bold;
        }
        .total-col {
            width: 60px;
            font-weight: 700;
            background: #f8fafc !important;
            text-align: center;
        }
        .legend {
            margin-top: 20px;
            display: flex;
            gap: 20px;
            font-size: 0.75rem;
            color: #6b7280;
            align-items: center;
            flex-wrap: wrap;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .badge {
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.75rem;
        }
        .badge-h { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-i { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .badge-s { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-a { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        
        /* Icon styling for inputs */
        .att-input {
            transition: all 0.2s;
        }
        
        .att-input:not(:focus) {
            background-repeat: no-repeat;
            background-position: center;
            background-size: 20px;
        }

        .att-input.status-h:not(:focus) {
            color: transparent;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='%2322c55e'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z' /%3E%3C/svg%3E");
        }
        .att-input.status-i:not(:focus) {
            color: transparent;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='%233b82f6'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75' /%3E%3C/svg%3E");
        }
        .att-input.status-s:not(:focus) {
            color: transparent;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='%23f59e0b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z' /%3E%3C/svg%3E");
        }
        .att-input.status-a:not(:focus) {
            color: transparent;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='%23ef4444'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z' /%3E%3C/svg%3E");
        }

        .color-h { color: #16a34a; font-weight: 800; }
        .color-i { color: #2563eb; font-weight: 800; }
        .color-s { color: #d97706; font-weight: 800; }
        .color-a { color: #dc2626; font-weight: 800; }

        .icon-placeholder {
            width: 32px;
            height: 32px;
            background: #e0f2fe;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0369a1;
        }
    </style>

    <div class="grid-header">
        <div class="grid-title-container">
            <div class="icon-placeholder">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
            </div>
            <div class="grid-title">
                <h2>Kehadiran Harian GTK</h2>
                <p>Rekap absensi bulanan tenaga pendidik dan kependidikan</p>
            </div>
        </div>

        <div class="grid-controls">
            <div class="control-group">
                <label>Bulan</label>
                <span style="font-size: 0.875rem; font-weight: 600; color: #374151;">
                    {{ Carbon\Carbon::create($tahun, $bulan, 1)->translatedFormat('F') }}
                </span>
            </div>
            <div style="width: 1px; background: #e5e7eb; height: 20px; margin: 0 12px;"></div>
            <div class="control-group">
                <label>Tahun</label>
                <span style="font-size: 0.875rem; font-weight: 600; color: #374151;">
                    {{ $tahun }}
                </span>
            </div>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="att-table">
            <thead>
                <tr>
                    <th rowspan="2" class="sticky-no">No</th>
                    <th rowspan="2" class="sticky-name">Nama Lengkap GTK</th>
                    <th colspan="{{ count($days) }}" style="background: #fff; border-bottom: 2px solid #cbd5e1; letter-spacing: 2px; font-size: 0.65rem;">TANGGAL</th>
                    <th rowspan="2" class="total-col" style="border-right: none;">HADIR</th>
                </tr>
                <tr>
                    @foreach($days as $d)
                        <th class="day-header {{ $d['is_sunday'] ? 'sunday-bg' : '' }}">
                            {{ $d['day'] }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($gtks as $index => $gtk)
                    <tr>
                        <td class="sticky-no text-center" style="color: #9ca3af;">{{ $index + 1 }}</td>
                        <td class="sticky-name" style="font-weight: 600; color: #374151;">{{ $gtk->nama }}</td>
                        @php $rowTotal = 0; @endphp
                        @foreach($days as $d)
                            @php 
                                $val = $attendanceData[$gtk->id][$d['day']] ?? ''; 
                                if ($val === 'H') $rowTotal++;
                                
                                $statusClass = match($val) {
                                    'H' => 'status-h color-h',
                                    'I' => 'status-i color-i',
                                    'S' => 'status-s color-s',
                                    'A' => 'status-a color-a',
                                    default => ''
                                };
                            @endphp
                            <td class="day-header {{ $d['is_sunday'] ? 'sunday-cell' : '' }}">
                                <input 
                                    type="text" 
                                    value="{{ $val }}"
                                    class="att-input {{ $statusClass }}"
                                    maxlength="1"
                                    @if($d['is_sunday']) disabled placeholder=" " @endif
                                    oninput="this.value = this.value.toUpperCase().replace(/[^HISA]/g, '')"
                                    onchange="@this.updateAttendance({{ $gtk->id }}, {{ $d['day'] }}, this.value)"
                                >
                            </td>
                        @endforeach
                        <td class="total-col" style="border-right: none; color: #0369a1; font-size: 0.9rem;">
                            {{ $rowTotal }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="legend">
        <div class="legend-item">
            <div class="badge badge-h">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span>Hadir (H)</span>
        </div>
        <div class="legend-item">
            <div class="badge badge-i">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
            </div>
            <span>Izin (I)</span>
        </div>
        <div class="legend-item">
            <div class="badge badge-s">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span>Sakit (S)</span>
        </div>
        <div class="legend-item">
            <div class="badge badge-a">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span>Alpa (A)</span>
        </div>
        <div style="margin-left: auto; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <span style="font-style: italic;">Data tersimpan otomatis saat Anda mengisi kode kehadiran.</span>
        </div>
    </div>
</div>
