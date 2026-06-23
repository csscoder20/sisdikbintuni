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

        .badge-primary {
            background-color: #e0e7ff;
            color: #3730a3;
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

        .dark .badge-primary {
            background-color: #312e81;
            color: #818cf8;
        }

        .dark .badge-info {
            background-color: #1e3a8a;
            color: #93c5fd;
        }

        .dark .badge-gray {
            background-color: #374151;
            color: #d1d5db;
        }
    </style>

    @php
        $user = $getRecord();
    @endphp

    <table class="user-details-table">
        <tbody>
            <tr>
                <td class="label-col">Nama Lengkap</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Alamat Email</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $user->email ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Nomor WA</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $user->nohp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Peran</td>
                <td class="colon-col">:</td>
                <td class="value-col">
                    @php
                        $roles = $user->roles->pluck('name')->implode(', ');
                        $roleColor = 'badge-gray';
                        if (str_contains($roles, 'super_admin')) {
                            $roleColor = 'badge-warning';
                        } elseif (str_contains($roles, 'admin_dinas')) {
                            $roleColor = 'badge-success';
                        } elseif (str_contains($roles, 'pengawas')) {
                            $roleColor = 'badge-info';
                        } elseif (str_contains($roles, 'operator')) {
                            $roleColor = 'badge-primary';
                        }
                    @endphp
                    @if ($roles)
                        <span class="user-details-badge {{ $roleColor }}">{{ $roles }}</span>
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label-col">Status</td>
                <td class="colon-col">:</td>
                <td class="value-col">
                    @php
                        $statusColor = match ($user->status) {
                            'active' => 'badge-success',
                            'pending' => 'badge-warning',
                            'rejected' => 'badge-danger',
                            default => 'badge-gray',
                        };
                        $statusLabel = match ($user->status) {
                            'active' => 'Aktif',
                            'pending' => 'Menunggu Verifikasi',
                            'rejected' => 'Tidak Aktif',
                            default => $user->status ?: '-',
                        };
                    @endphp
                    <span class="user-details-badge {{ $statusColor }}">{{ $statusLabel }}</span>
                </td>
            </tr>
            <tr>
                <td class="label-col">Asal Sekolah</td>
                <td class="colon-col">:</td>
                <td class="value-col">{{ $user->sekolah?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-col">Email Terverifikasi Pada</td>
                <td class="colon-col">:</td>
                <td class="value-col">
                    {{ $user->email_verified_at ? $user->email_verified_at->format('d M Y, H:i') : '-' }}</td>
            </tr>
        </tbody>
    </table>
</div>
