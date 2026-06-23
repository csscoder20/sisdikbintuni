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

        .content-box {
            background-color: #f9fafb;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            margin-top: 0.5rem;
        }

        .dark .content-box {
            background-color: #1f2937;
            border-color: #374151;
            color: #d1d5db;
        }

        .content-box img {
            max-width: 100%;
            height: auto;
        }
    </style>

    @php
        $notifikasi = $getRecord();
    @endphp

    <table class="user-details-table">
        <tbody>
            <tr>
                <td class="label-col">Judul Pemberitahuan</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $notifikasi->subject ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Tipe</td>
                <td class="colon-col">:</td>
                <td class="value-col">
                    @php
                        $typeColor = match ($notifikasi->type) {
                            'release_note' => 'badge-success',
                            default => 'badge-info',
                        };
                        $typeLabel = match ($notifikasi->type) {
                            'release_note' => 'Rilis Note/Pembaruan Sistem',
                            'general' => 'Pemberitahuan Umum',
                            default => $notifikasi->type,
                        };
                    @endphp
                    <span class="user-details-badge {{ $typeColor }}">{{ $typeLabel }}</span>
                </td>
            </tr>
            <tr>
                <td class="label-col">Tanggal Kirim</td>
                <td class="colon-col">:</td>
                <td class="value-col">
                    {{ $notifikasi->created_at ? $notifikasi->created_at->format('d M Y, H:i') : '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Pengirim</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $notifikasi->sender?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Tipe Penerima</td>
                <td class="colon-col">:</td>
                <td class="value-col">
                    @php
                        $recipientLabel = match ($notifikasi->recipient_type) {
                            'all' => 'Semua Operator',
                            'schools' => 'Sekolah Tertentu',
                            'users' => 'Pengguna Tertentu',
                            default => $notifikasi->recipient_type,
                        };
                    @endphp
                    <span class="user-details-badge badge-info">{{ $recipientLabel }}</span>
                </td>
            </tr>
            <tr>
                <td class="label-col">Isi Pemberitahuan</td>
                <td class="colon-col">:</td>
                <td class="value-col">
                    {!! $notifikasi->content ?? '<span class="text-muted">Tidak ada konten.</span>' !!}
                </td>
            </tr>
        </tbody>
    </table>
</div>
