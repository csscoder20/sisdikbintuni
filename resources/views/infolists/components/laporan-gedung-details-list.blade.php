<div class="user-details-list">
    <style>
        .user-details-table {
            width: 100%;
            border-collapse: collapse;
            font-family: inherit;
        }

        .user-details-table td {
            padding: 0.6rem 0;
            vertical-align: top;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
        }

        .user-details-table tr:last-child td {
            border-bottom: none;
        }

        .user-details-table .label-col {
            width: 35%;
            font-weight: 500;
            color: #111827;
        }

        .user-details-table .colon-col {
            width: 3%;
            font-weight: 500;
            color: #111827;
        }

        .user-details-table .value-col {
            width: 62%;
            font-weight: 400;
        }

        .user-details-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.15rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1.25rem;
            border-radius: 0.375rem;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background-color: #fef9c3;
            color: #854d0e;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-gray {
            background-color: #f3f4f6;
            color: #374151;
        }

        .dark .user-details-table td {
            color: #d1d5db;
            border-color: #374151;
        }

        .dark .user-details-table .label-col,
        .dark .user-details-table .colon-col {
            color: #f9fafb;
        }

        .dark .badge-success {
            background-color: #064e3b;
            color: #34d399;
        }

        .dark .badge-warning {
            background-color: #713f12;
            color: #fde047;
        }

        .dark .badge-danger {
            background-color: #7f1d1d;
            color: #fca5a5;
        }

        .dark .badge-info {
            background-color: #1e3a8a;
            color: #93c5fd;
        }

        .dark .badge-gray {
            background-color: #374151;
            color: #d1d5db;
        }

        .text-muted {
            color: #9ca3af;
            font-style: italic;
        }

        .dark .text-muted {
            color: #6b7280;
        }
    </style>

    @php
        $gedung = $getRecord();
    @endphp

    <table class="user-details-table">
        <tbody>
            <tr>
                <td class="label-col">Nama Ruang</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $gedung->nama_ruang ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Status Kepemilikan</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $gedung->status_kepemilikan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Jumlah Total</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $gedung->jumlah_total ?? '0' }}</td>
            </tr>
            <tr>
                <td class="label-col">Jumlah Baik</td>
                <td class="colon-col">:</td>
                <td class="value-col">
                    <span class="user-details-badge badge-success">{{ $gedung->jumlah_baik ?? '0' }}</span>
                </td>
            </tr>
            <tr>
                <td class="label-col">Jumlah Rusak</td>
                <td class="colon-col">:</td>
                <td class="value-col">
                    <span class="user-details-badge badge-danger">{{ $gedung->jumlah_rusak ?? '0' }}</span>
                </td>
            </tr>
            <tr>
                <td class="label-col">Periode Laporan</td>
                <td class="colon-col">:</td>
                <td class="value-col">
                    @if ($gedung->laporan)
                        Tahun {{ $gedung->laporan->tahun }} - Bulan {{ $gedung->laporan->bulan }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
