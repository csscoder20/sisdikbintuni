<div x-data="{
    showLockedDialog: false,
    showHolidayDialog: false,
    showHadirDialog: false,
    showActionDialog: false,
    showCellMenu: false,
    holidayDay: null,
    hadirDay: null,
    actionDay: null,
    cellMenuX: 0,
    cellMenuY: 0,
    cellGtkId: null,
    cellDay: null,
    isHolidayActive: false,
    actionIsHoliday: false,
    lockedAlert() {
        this.showLockedDialog = true;
    },
    confirmHoliday(day, isHoliday) {
        this.holidayDay = day;
        this.isHolidayActive = isHoliday;
        this.showHolidayDialog = true;
    },
    doToggleHoliday() {
        this.showHolidayDialog = false;
        $wire.toggleHoliday(this.holidayDay);
    },
    confirmHadir(day) {
        this.hadirDay = day;
        this.showHadirDialog = true;
    },
    doMarkAllPresent() {
        this.showHadirDialog = false;
        $wire.markAllPresent(this.hadirDay);
    },
    openActionDialog(day, isHoliday) {
        this.actionDay = day;
        this.actionIsHoliday = isHoliday;
        this.showActionDialog = true;
    },
    openCellMenu(event, gtkId, day) {
        const rect = event.currentTarget.getBoundingClientRect();
        this.cellMenuX = Math.min(rect.left + (rect.width / 2), window.innerWidth - 96);
        this.cellMenuY = Math.min(rect.bottom + 8, window.innerHeight - 238);
        this.cellGtkId = gtkId;
        this.cellDay = day;
        this.showCellMenu = true;
    },
    chooseAttendance(value) {
        this.showCellMenu = false;
        $wire.updateAttendance(this.cellGtkId, this.cellDay, value);
    }
}"
    style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); position: relative;">
    {{-- Dialog: Periode Terkunci --}}
    <template x-teleport="body">
        <div x-show="showLockedDialog" x-cloak style="position: fixed; inset: 0; z-index: 9999;"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div style="position: absolute; inset: 0; background: rgba(15,23,42,0.5); backdrop-filter: blur(2px);">
            </div>

            {{-- Centering wrapper --}}
            <div
                style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; padding: 1rem; pointer-events: none;">
                {{-- Dialog Panel --}}
                <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); padding: 1.5rem; max-width: 26rem; width: 100%; pointer-events: auto;"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">
                    {{-- Close Button (X) --}}
                    <button type="button" x-on:click="showLockedDialog = false"
                        style="position: absolute; top: 0.75rem; right: 0.75rem; display: flex; align-items: center; justify-content: center; width: 1.75rem; height: 1.75rem; border-radius: 9999px; border: none; background: transparent; color: #9ca3af; cursor: pointer; transition: all 0.15s ease-in-out;"
                        onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#4b5563';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#9ca3af';">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" style="width: 1.15rem; height: 1.15rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div
                        style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 1.5rem;">
                        <div
                            style="width: 3.5rem; height: 3.5rem; border-radius: 9999px; background: #fef9c3; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="#ca8a04" style="width: 1.75rem; height: 1.75rem;">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <h3
                            style="font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0 0 0.5rem; text-align: center;">
                            Data tidak dapat disimpan</h3>
                        <p
                            style="font-size: 0.875rem; color: #6b7280; margin: 0; line-height: 1.5; text-align: center;">
                            Periode validasi sedang ditutup oleh Admin Dinas.</p>
                    </div>
                    <div style="width: 100%;">
                        <button type="button" x-on:click="showLockedDialog = false"
                            style="width: 100%; display: inline-flex; align-items: center; justify-content: center; height: 2.75rem; font-size: 0.875rem; font-weight: 700; border-radius: 0.5rem; border: none; background: #ef4444; color: white; cursor: pointer; transition: all 0.15s ease-in-out; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);"
                            onmouseover="this.style.filter='brightness(92%)'; this.style.transform='scale(1.02)';"
                            onmouseout="this.style.filter='none'; this.style.transform='none';">
                            Mengerti
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- Popover: Pilih Status Kehadiran per Cell --}}
    <template x-teleport="body">
        <div x-show="showCellMenu" x-cloak x-on:keydown.escape.window="showCellMenu = false"
            style="position: fixed; inset: 0; z-index: 9997;" x-transition.opacity>
            <div style="position: absolute; inset: 0;" x-on:click="showCellMenu = false"></div>
            <div class="attendance-popover"
                :style="`left: ${cellMenuX}px; top: ${cellMenuY}px; transform: translateX(-50%);`">
                <button type="button" class="attendance-option option-h" x-on:click="chooseAttendance('H')">
                    <span class="option-icon badge-h">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span>Hadir</span>
                    <strong>H</strong>
                </button>
                <button type="button" class="attendance-option option-i" x-on:click="chooseAttendance('I')">
                    <span class="option-icon badge-i">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" />
                            <path
                                d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" />
                        </svg>
                    </span>
                    <span>Izin</span>
                    <strong>I</strong>
                </button>
                <button type="button" class="attendance-option option-s" x-on:click="chooseAttendance('S')">
                    <span class="option-icon badge-s">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span>Sakit</span>
                    <strong>S</strong>
                </button>
                <button type="button" class="attendance-option option-a" x-on:click="chooseAttendance('A')">
                    <span class="option-icon badge-a">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span>Alpa</span>
                    <strong>A</strong>
                </button>
                <button type="button" class="attendance-option option-l" x-on:click="chooseAttendance('L')">
                    <span class="option-icon badge-l">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                            <path d="M15.5 14h-7c-.28 0-.5-.22-.5-.5v-3c0-.28.22-.5.5-.5h7c.28 0 .5.22.5.5v3c0 .28-.22.5-.5.5z" />
                        </svg>
                    </span>
                    <span>Libur</span>
                    <strong>L</strong>
                </button>
                <button type="button" class="attendance-option option-clear" x-on:click="chooseAttendance('')">
                    <span class="option-icon">-</span>
                    <span>Kosongkan</span>
                    <strong></strong>
                </button>
            </div>
        </div>
    </template>

    {{-- Dialog: Konfirmasi Toggle Libur Massal --}}
    <template x-teleport="body">
        <div x-show="showHolidayDialog" x-cloak style="position: fixed; inset: 0; z-index: 9999;"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div style="position: absolute; inset: 0; background: rgba(15,23,42,0.5); backdrop-filter: blur(2px);">
            </div>

            {{-- Centering wrapper --}}
            <div
                style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; padding: 1rem; pointer-events: none;">
                {{-- Dialog Panel --}}
                <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); padding: 1.5rem; max-width: 26rem; width: 100%; pointer-events: auto;"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">
                    {{-- Close Button (X) --}}
                    <button type="button" x-on:click="showHolidayDialog = false"
                        style="position: absolute; top: 0.75rem; right: 0.75rem; display: flex; align-items: center; justify-content: center; width: 1.75rem; height: 1.75rem; border-radius: 9999px; border: none; background: transparent; color: #9ca3af; cursor: pointer; transition: all 0.15s ease-in-out;"
                        onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#4b5563';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#9ca3af';">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" style="width: 1.15rem; height: 1.15rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div
                        style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 1.5rem;">
                        <div style="width: 3.5rem; height: 3.5rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;"
                            :style="isHolidayActive ? 'background:#fee2e2;' : 'background:#fef9c3;'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" style="width: 1.75rem; height: 1.75rem;"
                                :style="isHolidayActive ? 'color:#dc2626;' : 'color:#ca8a04;'">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <h3 style="font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0 0 0.5rem; text-align: center;"
                            x-text="isHolidayActive ? 'Hapus Libur Massal?' : 'Tandai Libur Massal?'"></h3>
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0; line-height: 1.5; text-align: center;"
                            x-text="isHolidayActive ? 'Status Libur (L) akan dihapus dari semua GTK pada tanggal ini.' : 'Semua GTK akan ditandai Libur (L) pada tanggal ini.'">
                        </p>
                    </div>
                    <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem; width: 100%;">
                        <button type="button" x-on:click="showHolidayDialog = false"
                            style="flex: 1; display: inline-flex; align-items: center; justify-content: center; height: 2.75rem; padding: 0 1.25rem; font-size: 0.875rem; font-weight: 700; border-radius: 0.5rem; border: 1px solid #e5e7eb; background: white; color: #1f2937; cursor: pointer; transition: all 0.15s ease-in-out; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);"
                            onmouseover="this.style.backgroundColor='#f9fafb'; this.style.transform='scale(1.02)';"
                            onmouseout="this.style.backgroundColor='#ffffff'; this.style.transform='none';">
                            Batal
                        </button>
                        <button type="button" x-on:click="doToggleHoliday()"
                            style="flex: 1; display: inline-flex; align-items: center; justify-content: center; height: 2.75rem; padding: 0 1.25rem; font-size: 0.875rem; font-weight: 700; border-radius: 0.5rem; border: none; cursor: pointer; color: white !important; transition: all 0.15s ease-in-out; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);"
                            :style="{ backgroundColor: isHolidayActive ? '#ef4444' : '#f97316', color: 'white' }"
                            onmouseover="this.style.filter='brightness(92%)'; this.style.transform='scale(1.02)';"
                            onmouseout="this.style.filter='none'; this.style.transform='none';"
                            x-text="isHolidayActive ? 'Ya, Hapus Libur' : 'Ya, Tandai Libur'"></button>
                    </div>
                </div>
            </div>
        </div>
    </template>
    @php
        $locked = $locked ?? false;
    @endphp

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
            font-size: 0.875rem;
            font-weight: 400;
            color: #374151 !important;
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
            box-shadow: 4px 0 4px -2px rgba(0, 0, 0, 0.05);
            font-size: 0.875rem;
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

        button.att-input {
            display: block;
            padding: 0;
        }

        button.att-input:hover:not(:disabled) {
            background-color: #f8fafc;
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

        .att-input[readonly] {
            cursor: not-allowed;
            opacity: .75;
        }

        .locked-notice {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            padding: 10px 14px;
            border: 1px solid #fecaca;
            border-radius: 8px;
            background: #fef2f2;
            color: #991b1b;
            font-size: 0.875rem;
            font-weight: 600;
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

        .badge-h {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .badge-i {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .badge-s {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-a {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .badge-l {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }

        .attendance-popover {
            position: fixed;
            width: 172px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
        }

        .attendance-option {
            display: grid;
            grid-template-columns: 24px 1fr 20px;
            align-items: center;
            gap: 8px;
            width: 100%;
            border: 0;
            border-bottom: 1px solid #f3f4f6;
            background: #ffffff;
            padding: 8px 10px;
            color: #374151;
            font-size: 0.78rem;
            font-weight: 600;
            text-align: left;
            cursor: pointer;
        }

        .attendance-option:last-child {
            border-bottom: 0;
        }

        .attendance-option:hover {
            background: #f9fafb;
        }

        .attendance-option strong {
            color: #6b7280;
            font-size: 0.72rem;
            text-align: right;
        }

        .option-icon {
            width: 22px;
            height: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            font-weight: 700;
        }

        .option-icon svg {
            width: 14px;
            height: 14px;
        }

        .option-clear .option-icon {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            color: #64748b;
        }

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
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2322c55e'%3E%3Cpath fill-rule='evenodd' d='M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z' clip-rule='evenodd' /%3E%3C/svg%3E");
        }

        .att-input.status-i:not(:focus) {
            color: transparent;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%233b82f6'%3E%3Cpath d='M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z' /%3E%3Cpath d='M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z' /%3E%3C/svg%3E");
        }

        .att-input.status-s:not(:focus) {
            color: transparent;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23f59e0b'%3E%3Cpath fill-rule='evenodd' d='M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z' clip-rule='evenodd' /%3E%3C/svg%3E");
        }

        .att-input.status-a:not(:focus) {
            color: transparent;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23ef4444'%3E%3Cpath fill-rule='evenodd' d='M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z' clip-rule='evenodd' /%3E%3C/svg%3E");
        }

        .att-input.status-l:not(:focus) {
            color: transparent;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%236b7280'%3E%3Cpath d='M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z'/%3E%3Cpath d='M15.5 14h-7c-.28 0-.5-.22-.5-.5v-3c0-.28.22-.5.5-.5h7c.28 0 .5.22.5.5v3c0 .28-.22.5-.5.5z'/%3E%3C/svg%3E");
        }

        .color-h {
            color: #16a34a;
            font-weight: 800;
        }

        .color-i {
            color: #2563eb;
            font-weight: 800;
        }

        .color-s {
            color: #d97706;
            font-weight: 800;
        }

        .color-a {
            color: #dc2626;
            font-weight: 800;
        }

        .color-l {
            color: #6b7280;
            font-weight: 800;
        }

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

        .btn-set-holiday {
            margin-top: 4px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2px 0;
            border-radius: 4px;
            color: #9ca3af;
            transition: all 0.2s;
            background: transparent;
            border: none;
            cursor: pointer;
        }

        .btn-set-holiday:hover {
            background: rgba(0, 0, 0, 0.05);
            color: #6b7280;
        }

        .btn-set-holiday:active {
            transform: scale(0.9);
        }

        /* Custom Pagination Styling */
        .custom-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #f3f4f6;
        }

        .pagination-info {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .pagination-nav {
            display: flex;
            gap: 6px;
        }

        .pagination-btn {
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            background: white;
            color: #374151;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .pagination-btn:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .pagination-btn.active {
            background: #0369a1;
            color: white;
            border-color: #0369a1;
        }

        .pagination-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f3f4f6;
        }
    </style>

    <div class="grid-header">
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
            <div style="width: 1px; background: #e5e7eb; height: 20px; margin: 0 12px;"></div>
            <div class="control-group">
                <label>Tampilkan</label>
                <select wire:model.live="perPage">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="all">Semua</option>
                </select>
            </div>
        </div>
    </div>

    @if ($locked)
        <div class="locked-notice">
            <x-filament::icon icon="heroicon-o-no-symbol" style="width: 18px; height: 18px; flex: none;" />
            <span>Periode validasi sedang ditutup. Pengisian kehadiran GTK sementara tidak dapat disimpan.</span>
        </div>
    @endif

    <div class="table-wrapper">
        <table class="att-table">
            <thead>
                <tr>
                    <th rowspan="2" class="sticky-no">No</th>
                    <th rowspan="2" class="sticky-name">Nama Lengkap GTK</th>
                    <th colspan="{{ count($days) }}"
                        style="background: #fff; border-bottom: 2px solid #cbd5e1; letter-spacing: 2px; font-size: 0.65rem;">
                        TANGGAL</th>
                    <th rowspan="2" class="total-col" style="border-right: none;">HADIR</th>
                </tr>
                <tr>
                    @foreach ($days as $d)
                        <th class="day-header {{ $d['is_sunday'] ? 'sunday-bg' : '' }}" style="padding: 10px 4px;">
                            <div style="font-size: 11px; font-weight: 700;">
                                {{ $d['day'] }}
                            </div>

                            @if (!$d['is_sunday'])
                                @php
                                    $isHoliday = collect($attendanceData)->pluck($d['day'])->contains('L');
                                @endphp
                                <button type="button"
                                    x-on:click="@if ($locked) lockedAlert() @else openActionDialog({{ $d['day'] }}, {{ $isHoliday ? 'true' : 'false' }}) @endif"
                                    class="btn-set-holiday" title="Opsi Kehadiran Massal"
                                    style="{{ $isHoliday ? 'color: #ef4444;' : '' }}">
                                    @if ($isHoliday)
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" style="width: 12px; height: 12px;">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM6.75 9.25a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" style="width: 12px; height: 12px;">
                                            <path
                                                d="M5.25 3A2.25 2.25 0 003 5.25v9.5A2.25 2.25 0 005.25 17h9.5A2.25 2.25 0 0017 14.75v-9.5A2.25 2.25 0 0014.75 3h-9.5zM12 11h2.25a.75.75 0 010 1.5H12V14.25a.75.75 0 01-1.5 0V12H8.25a.75.75 0 010-1.5H10.5V8.75a.75.75 0 011.5 0V11z" />
                                        </svg>
                                    @endif
                                </button>
                            @else
                                <div style="height: 18px;"></div>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($gtks as $index => $gtk)
                    <tr wire:key="gtk-row-{{ $gtk->id }}">
                        <td class="sticky-no text-center">{{ $gtks->firstItem() + $index }}</td>
                        <td class="sticky-name" style="font-weight: 400; color: #374151;">
                            {{ $this->formatGtkName($gtk) }}</td>
                        @php $rowTotal = 0; @endphp
                        @foreach ($days as $d)
                            @php
                                $val = $attendanceData[$gtk->id][$d['day']] ?? '';
                                if ($val === 'H') {
                                    $rowTotal++;
                                }

                                $statusClass = match ($val) {
                                    'H' => 'status-h color-h',
                                    'I' => 'status-i color-i',
                                    'S' => 'status-s color-s',
                                    'A' => 'status-a color-a',
                                    'L' => 'status-l color-l',
                                    default => '',
                                };
                            @endphp
                            <td class="day-header {{ $d['is_sunday'] ? 'sunday-cell' : '' }}">
                                <button type="button" class="att-input {{ $statusClass }}"
                                    wire:key="att-{{ $gtk->id }}-{{ $d['day'] }}"
                                    title="{{ $d['is_sunday'] ? 'Hari Minggu' : 'Pilih status kehadiran' }}"
                                    aria-label="Pilih status kehadiran {{ $this->formatGtkName($gtk) }} tanggal {{ $d['day'] }}"
                                    @if ($d['is_sunday']) disabled @endif
                                    @if ($locked && !$d['is_sunday']) x-on:click="lockedAlert()"
                                        x-on:keydown.enter.prevent="lockedAlert()"
                                        x-on:keydown.space.prevent="lockedAlert()"
                                    @elseif (!$d['is_sunday'])
                                        x-on:click="openCellMenu($event, {{ $gtk->id }}, {{ $d['day'] }})"
                                        x-on:keydown.enter.prevent="openCellMenu($event, {{ $gtk->id }}, {{ $d['day'] }})"
                                        x-on:keydown.space.prevent="openCellMenu($event, {{ $gtk->id }}, {{ $d['day'] }})" @endif>{{ $val }}</button>
                            </td>
                        @endforeach
                        <td class="total-col" style="border-right: none; color: #0369a1; font-size: 0.9rem;">
                            {{ $rowTotal }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($days) + 3 }}" style="padding: 80px 0;">
                            <div
                                style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                                <div
                                    style="width: 64px; height: 64px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                    <svg style="width: 32px; height: 32px; color: #9ca3af;" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                                <h3 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0;">Belum Ada
                                    Data GTK</h3>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Custom Pagination --}}
    @if ($gtks->hasPages())
        <div class="custom-pagination">
            <div class="pagination-info">
                Menampilkan <strong>{{ $gtks->firstItem() }}</strong> sampai <strong>{{ $gtks->lastItem() }}</strong>
                dari <strong>{{ $gtks->total() }}</strong> GTK
            </div>
            <div class="pagination-nav">
                {{-- Previous Page Link --}}
                @if ($gtks->onFirstPage())
                    <span class="pagination-btn disabled">Sebelumnya</span>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled"
                        class="pagination-btn">Sebelumnya</button>
                @endif

                {{-- Page Numbers --}}
                @foreach ($gtks->getUrlRange(max(1, $gtks->currentPage() - 2), min($gtks->lastPage(), $gtks->currentPage() + 2)) as $page => $url)
                    @if ($page == $gtks->currentPage())
                        <span class="pagination-btn active">{{ $page }}</span>
                    @else
                        <button wire:click="gotoPage({{ $page }})"
                            class="pagination-btn">{{ $page }}</button>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($gtks->hasMorePages())
                    <button wire:click="nextPage" wire:loading.attr="disabled"
                        class="pagination-btn">Selanjutnya</button>
                @else
                    <span class="pagination-btn disabled">Selanjutnya</span>
                @endif
            </div>
        </div>
    @endif

    <div class="legend">
        <div class="legend-item">
            <div class="badge badge-h">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    style="width: 14px; height: 14px;">
                    <path fill-rule="evenodd"
                        d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <span>Hadir (H)</span>
        </div>
        <div class="legend-item">
            <div class="badge badge-i">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    style="width: 14px; height: 14px;">
                    <path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" />
                    <path
                        d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" />
                </svg>
            </div>
            <span>Izin (I)</span>
        </div>
        <div class="legend-item">
            <div class="badge badge-s">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    style="width: 14px; height: 14px;">
                    <path fill-rule="evenodd"
                        d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <span>Sakit (S)</span>
        </div>
        <div class="legend-item">
            <div class="badge badge-a">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    style="width: 14px; height: 14px;">
                    <path fill-rule="evenodd"
                        d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <span>Alpa (A)</span>
        </div>
        <div class="legend-item">
            <div class="badge" style="background: #f3f4f6; color: #4b5563; border: 1px solid #e5e7eb;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    style="width: 14px; height: 14px;">
                    <path
                        d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                    <path
                        d="M15.5 14h-7c-.28 0-.5-.22-.5-.5v-3c0-.28.22-.5.5-.5h7c.28 0 .5.22.5.5v3c0 .28-.22.5-.5.5z" />
                </svg>
            </div>
            <span>Libur (L)</span>
        </div>
        <div style="margin-left: auto; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="16" x2="12" y2="12" />
                <line x1="12" y1="8" x2="12.01" y2="8" />
            </svg>
            <span style="font-style: italic;">
                {{ $locked ? 'Data tidak dapat disimpan karena periode validasi sedang ditutup.' : 'Data tersimpan otomatis saat Anda mengisi kode kehadiran.' }}
            </span>
        </div>
        {{-- Dialog: Pilih Aksi Massal --}}
        <template x-teleport="body">
            <div x-show="showActionDialog" x-cloak style="position: fixed; inset: 0; z-index: 9998;"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div style="position: absolute; inset: 0; background: rgba(15,23,42,0.5); backdrop-filter: blur(2px);"
                    x-on:click="showActionDialog = false"></div>

                {{-- Centering wrapper --}}
                <div
                    style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; padding: 1rem; pointer-events: none;">
                    {{-- Dialog Panel --}}
                    <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); padding: 1.75rem; max-width: 28rem; width: 100%; pointer-events: auto;"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                        {{-- Close Button (X) --}}
                        <button type="button" x-on:click="showActionDialog = false"
                            style="position: absolute; top: 0.75rem; right: 0.75rem; display: flex; align-items: center; justify-content: center; width: 1.75rem; height: 1.75rem; border-radius: 9999px; border: none; background: transparent; color: #9ca3af; cursor: pointer; transition: all 0.15s ease-in-out;"
                            onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#4b5563';"
                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#9ca3af';">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" style="width: 1.15rem; height: 1.15rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div style="margin-bottom: 1.5rem; text-align: center;">
                            <h3 style="font-size: 1.125rem; font-weight: 700; color: #1f2937; margin: 0 0 0.25rem;">
                                Pilih Aksi Kehadiran Massal</h3>
                            <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Silakan pilih aksi untuk seluruh
                                GTK pada Tanggal <span style="font-weight: 700; color: #111827;"
                                    x-text="actionDay"></span></p>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            {{-- Opsi 1: Hadir Semua --}}
                            <button type="button" x-on:click="showActionDialog = false; confirmHadir(actionDay)"
                                style="display: flex; align-items: center; gap: 1rem; width: 100%; padding: 1rem; border-radius: 0.5rem; border: 1px solid #bbf7d0; background: #f0fdf4; cursor: pointer; text-align: left; transition: all 0.2s;"
                                onmouseover="this.style.borderColor='#86efac'; this.style.backgroundColor='#e6fced'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(22, 163, 74, 0.08)';"
                                onmouseout="this.style.borderColor='#bbf7d0'; this.style.backgroundColor='#f0fdf4'; this.style.transform='none'; this.style.boxShadow='none';">
                                <div
                                    style="flex-shrink: 0; width: 2.5rem; height: 2.5rem; border-radius: 9999px; background: #dcfce7; display: flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="#15803d" style="width: 1.25rem; height: 1.25rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <span
                                        style="display: block; font-size: 0.95rem; font-weight: 700; color: #166534;">Tandai
                                        Hadir Semua</span>
                                    <span
                                        style="display: block; font-size: 0.78rem; color: #15803d; margin-top: 0.15rem;">Set
                                        status Hadir (H) untuk seluruh GTK.</span>
                                </div>
                            </button>

                            {{-- Opsi 2: Hapus Libur Massal (Hanya muncul jika saat ini statusnya Libur) --}}
                            <div x-show="actionIsHoliday" style="width: 100%;">
                                <button type="button"
                                    x-on:click="showActionDialog = false; confirmHoliday(actionDay, true)"
                                    style="display: flex; align-items: center; gap: 1rem; width: 100%; padding: 1rem; border-radius: 0.5rem; border: 1px solid #fecaca; background: #fef2f2; cursor: pointer; text-align: left; transition: all 0.2s;"
                                    onmouseover="this.style.borderColor='#fca5a5'; this.style.backgroundColor='#fee2e2'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(220, 38, 38, 0.08)';"
                                    onmouseout="this.style.borderColor='#fecaca'; this.style.backgroundColor='#fef2f2'; this.style.transform='none'; this.style.boxShadow='none';">
                                    <div
                                        style="flex-shrink: 0; width: 2.5rem; height: 2.5rem; border-radius: 9999px; background: #fee2e2; display: flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.5" stroke="#991b1b"
                                            style="width: 1.25rem; height: 1.25rem;">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span
                                            style="display: block; font-size: 0.95rem; font-weight: 700; color: #991b1b;">Hapus
                                            Libur Massal</span>
                                        <span
                                            style="display: block; font-size: 0.78rem; color: #b91c1c; margin-top: 0.15rem;">Hapus
                                            penanda Libur (L) dari semua GTK.</span>
                                    </div>
                                </button>
                            </div>

                            {{-- Opsi 3: Set Libur Massal (Hanya muncul jika saat ini statusnya BUKAN Libur) --}}
                            <div x-show="!actionIsHoliday" style="width: 100%;">
                                <button type="button"
                                    x-on:click="showActionDialog = false; confirmHoliday(actionDay, false)"
                                    style="display: flex; align-items: center; gap: 1rem; width: 100%; padding: 1rem; border-radius: 0.5rem; border: 1px solid #fef08a; background: #fffbeb; cursor: pointer; text-align: left; transition: all 0.2s;"
                                    onmouseover="this.style.borderColor='#fde68a'; this.style.backgroundColor='#fef9c3'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(202, 138, 4, 0.08)';"
                                    onmouseout="this.style.borderColor='#fef08a'; this.style.backgroundColor='#fffbeb'; this.style.transform='none'; this.style.boxShadow='none';">
                                    <div
                                        style="flex-shrink: 0; width: 2.5rem; height: 2.5rem; border-radius: 9999px; background: #fef9c3; display: flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2.5" stroke="#854d0e"
                                            style="width: 1.25rem; height: 1.25rem;">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span
                                            style="display: block; font-size: 0.95rem; font-weight: 700; color: #854d0e;">Set
                                            Libur Massal</span>
                                        <span
                                            style="display: block; font-size: 0.78rem; color: #a16207; margin-top: 0.15rem;">Tandai
                                            Libur (L) untuk semua GTK.</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Dialog: Konfirmasi Tandai Hadir Semua --}}
        <template x-teleport="body">
            <div x-show="showHadirDialog" x-cloak style="position: fixed; inset: 0; z-index: 9999;"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div style="position: absolute; inset: 0; background: rgba(15,23,42,0.5); backdrop-filter: blur(2px);">
                </div>

                {{-- Centering wrapper --}}
                <div
                    style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; padding: 1rem; pointer-events: none;">
                    {{-- Dialog Panel --}}
                    <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); padding: 1.5rem; max-width: 26rem; width: 100%; pointer-events: auto;"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                        {{-- Close Button (X) --}}
                        <button type="button" x-on:click="showHadirDialog = false"
                            style="position: absolute; top: 0.75rem; right: 0.75rem; display: flex; align-items: center; justify-content: center; width: 1.75rem; height: 1.75rem; border-radius: 9999px; border: none; background: transparent; color: #9ca3af; cursor: pointer; transition: all 0.15s ease-in-out;"
                            onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#4b5563';"
                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#9ca3af';">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" style="width: 1.15rem; height: 1.15rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div
                            style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 1.5rem;">
                            <div
                                style="width: 3.5rem; height: 3.5rem; border-radius: 9999px; background: #eff6ff; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="#3b82f6" style="width: 1.75rem; height: 1.75rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3
                                style="font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0 0 0.5rem; text-align: center;">
                                Tandai Hadir Semua GTK?</h3>
                            <p
                                style="font-size: 0.875rem; color: #6b7280; margin: 0; line-height: 1.5; text-align: center;">
                                Semua GTK akan ditandai Hadir (H) pada tanggal ini.</p>
                        </div>
                        <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem; width: 100%;">
                            <button type="button" x-on:click="showHadirDialog = false"
                                style="flex: 1; display: inline-flex; align-items: center; justify-content: center; height: 2.75rem; padding: 0 1.25rem; font-size: 0.875rem; font-weight: 700; border-radius: 0.5rem; border: 1px solid #e5e7eb; background: white; color: #1f2937; cursor: pointer; transition: all 0.15s ease-in-out; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);"
                                onmouseover="this.style.backgroundColor='#f9fafb'; this.style.transform='scale(1.02)';"
                                onmouseout="this.style.backgroundColor='#ffffff'; this.style.transform='none';">
                                Batal
                            </button>
                            <button type="button" x-on:click="doMarkAllPresent()"
                                style="flex: 1; display: inline-flex; align-items: center; justify-content: center; height: 2.75rem; padding: 0 1.25rem; font-size: 0.875rem; font-weight: 700; border-radius: 0.5rem; border: none; cursor: pointer; color: white !important; transition: all 0.15s ease-in-out; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); background:#16a34a;"
                                onmouseover="this.style.filter='brightness(92%)'; this.style.transform='scale(1.02)';"
                                onmouseout="this.style.filter='none'; this.style.transform='none';">Ya, Tandai
                                Hadir</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
