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
            font-size: 0.75rem;
            outline: none;
            cursor: pointer;
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
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .badge {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.7rem;
        }
        .badge-present { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .badge-absent { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
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
                                $val = $attendanceData[$gtk->id][$d['day']] ?? null; 
                                if ($val === 1) $rowTotal++;
                            @endphp
                            <td class="day-header {{ $d['is_sunday'] ? 'sunday-cell' : '' }}">
                                <input 
                                    type="text" 
                                    value="{{ $val === 1 ? '1' : ($val === 0 ? '0' : '') }}"
                                    class="att-input"
                                    style="{{ $val === 1 ? 'color: #2563eb; font-weight: 800;' : ($val === 0 ? 'color: #dc2626; font-weight: 800;' : '') }}"
                                    maxlength="1"
                                    @if($d['is_sunday']) disabled placeholder=" " @endif
                                    oninput="this.value = this.value.replace(/[^0-1]/g, '')"
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
            <div class="badge badge-present">1</div>
            <span>Hadir (H)</span>
        </div>
        <div class="legend-item">
            <div class="badge badge-absent">0</div>
            <span>Tidak Hadir (A)</span>
        </div>
        <div style="margin-left: auto; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <span style="font-style: italic;">Data tersimpan otomatis saat Anda mengisi angka.</span>
        </div>
    </div>
</div>
