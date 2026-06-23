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
        $pengaduan = $getRecord();
    @endphp

    <table class="user-details-table">
        <tbody>
            <tr>
                <td class="label-col">Judul Pengaduan</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $pengaduan->judul ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Deskripsi Lengkap</td>
                <td class="colon-col">:</td>
                <td class="value-col" style="white-space: pre-wrap;">{{ $pengaduan->deskripsi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Riwayat Proses</td>
                <td class="colon-col">:</td>
                <td class="value-col">
                    <ul style="list-style: none; padding: 0; margin: 0; line-height: 1.6;">
                        <li>
                            <span class="user-details-badge badge-gray" style="margin-right: 0.5rem;">Pending</span>:
                            <span class="text-muted">Pengaduan dibuat
                                {{ $pengaduan->created_at ? $pengaduan->created_at->format('d M Y') : '-' }}</span>
                        </li>
                        @if ($pengaduan->status === 'diproses' || $pengaduan->status === 'selesai')
                            <li style="margin-top: 0.5rem;">
                                <span class="user-details-badge badge-warning"
                                    style="margin-right: 0.5rem;">Diproses</span>:
                                <span class="text-muted">
                                    @if ($pengaduan->status === 'diproses')
                                        {{ $pengaduan->jawaban ?? 'Sedang ditindaklanjuti oleh tim.' }}
                                    @else
                                        Telah ditindaklanjuti oleh tim.
                                    @endif

                                </span>
                            </li>
                        @endif
                        @if ($pengaduan->status === 'selesai')
                            <li style="margin-top: 0.5rem;">
                                <span class="user-details-badge badge-success"
                                    style="margin-right: 0.5rem;">Selesai</span>:
                                <span class="text-muted">
                                    {{ $pengaduan->jawaban ?? 'Pengaduan telah selesai.' }}
                                </span>
                            </li>
                        @endif
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
</div>
