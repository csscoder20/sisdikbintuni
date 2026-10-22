<x-filament-panels::page>
    @php($database = $this->getDatabaseInfo())

    <style>
        .database-backup-page { display: flex; flex-direction: column; gap: 1.5rem; }
        .database-backup-page .backup-overview { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; }
        .database-backup-page .backup-stat { border: 1px solid rgb(229 231 235); border-radius: .75rem; padding: 1rem; background: rgb(255 255 255); }
        .database-backup-page .backup-stat-label { margin: 0; color: rgb(107 114 128); font-size: .8125rem; line-height: 1.25rem; }
        .database-backup-page .backup-stat-value { margin: .3rem 0 0; font-size: 1rem; font-weight: 700; line-height: 1.4; overflow-wrap: anywhere; }
        .database-backup-page .backup-stat-value.is-success { color: rgb(22 163 74); }
        .database-backup-page .backup-stat-value.is-danger { color: rgb(220 38 38); }
        .database-backup-page .backup-action-panel { display: flex; align-items: center; justify-content: space-between; gap: 1.25rem; margin-top: 1.5rem; padding: 1rem; border-radius: .75rem; background: rgb(249 250 251); }
        .database-backup-page .backup-action-description { margin: 0; color: rgb(75 85 99); font-size: .875rem; line-height: 1.45; max-width: 45rem; }
        .database-backup-page .backup-table-wrap { overflow-x: auto; }
        .database-backup-page .backup-table { width: 100%; min-width: 42rem; border-collapse: collapse; font-size: .875rem; }
        .database-backup-page .backup-table th { padding: .75rem; border-bottom: 1px solid rgb(229 231 235); color: rgb(107 114 128); font-size: .75rem; font-weight: 600; text-align: left; text-transform: uppercase; letter-spacing: .025em; }
        .database-backup-page .backup-table td { padding: .875rem .75rem; border-bottom: 1px solid rgb(243 244 246); vertical-align: middle; }
        .database-backup-page .backup-table tbody tr:last-child td { border-bottom: 0; }
        .database-backup-page .backup-file-name { font-weight: 600; }
        .database-backup-page .backup-empty { padding: 2.5rem .75rem !important; text-align: center; color: rgb(107 114 128); }
        .dark .database-backup-page .backup-stat { border-color: rgb(255 255 255 / .1); background: rgb(255 255 255 / .03); }
        .dark .database-backup-page .backup-stat-label, .dark .database-backup-page .backup-table th, .dark .database-backup-page .backup-empty { color: rgb(156 163 175); }
        .dark .database-backup-page .backup-action-panel { background: rgb(255 255 255 / .05); }
        .dark .database-backup-page .backup-action-description { color: rgb(209 213 219); }
        .dark .database-backup-page .backup-table th { border-color: rgb(255 255 255 / .1); }
        .dark .database-backup-page .backup-table td { border-color: rgb(255 255 255 / .06); }
        @media (max-width: 1024px) { .database-backup-page .backup-overview { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 640px) { .database-backup-page .backup-overview { grid-template-columns: 1fr; } .database-backup-page .backup-action-panel { align-items: stretch; flex-direction: column; } .database-backup-page .backup-action-panel > .fi-btn { width: 100%; justify-content: center; } }
    </style>

    <div class="database-backup-page">
        <x-filament::section>
            <x-slot name="heading">Cadangan database manual</x-slot>
            <x-slot name="description">Buat satu file cadangan PostgreSQL yang utuh sebelum pemeliharaan, pembaruan, atau perubahan data penting.</x-slot>

            <div class="backup-overview">
                <div class="backup-stat">
                    <p class="backup-stat-label">Database</p>
                    <p class="backup-stat-value">{{ $database['database'] }}</p>
                </div>
                <div class="backup-stat">
                    <p class="backup-stat-label">Koneksi</p>
                    <p class="backup-stat-value">{{ strtoupper($database['connection']) }}</p>
                </div>
                <div class="backup-stat">
                    <p class="backup-stat-label">Ukuran database</p>
                    <p class="backup-stat-value">{{ $database['size'] }}</p>
                </div>
                <div class="backup-stat">
                    <p class="backup-stat-label">Status koneksi</p>
                    <p class="backup-stat-value {{ $database['status'] === 'Terhubung' ? 'is-success' : 'is-danger' }}">{{ $database['status'] }}</p>
                </div>
            </div>

            <div class="backup-action-panel">
                <p class="backup-action-description">File disimpan di penyimpanan privat aplikasi dalam format <strong>.dump</strong>. Hanya Super Admin yang dapat membuat backup ini.</p>
                <x-filament::button wire:click="mountAction('confirmBackup')" icon="heroicon-o-circle-stack" color="warning">
                    Buat Backup Sekarang
                </x-filament::button>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Riwayat backup</x-slot>
            <x-slot name="description">Menampilkan 20 proses backup database terakhir.</x-slot>

            <div class="backup-table-wrap">
                <table class="backup-table">
                    <thead>
                        <tr>
                            <th class="px-3 py-3 font-medium">Waktu</th>
                            <th class="px-3 py-3 font-medium">File</th>
                            <th class="px-3 py-3 font-medium">Format</th>
                            <th class="px-3 py-3 font-medium">Ukuran</th>
                            <th class="px-3 py-3 font-medium">Dibuat oleh</th>
                            <th class="px-3 py-3 font-medium">Status</th>
                            <th class="px-3 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->getBackups() as $backup)
                            <tr>
                                <td>{{ $backup->created_at->translatedFormat('d M Y, H:i') }}</td>
                                <td class="backup-file-name">{{ $backup->file_name ?? '—' }}</td>
                                <td>{{ $backup->format === 'plain' ? '.sql' : '.dump' }}</td>
                                <td>{{ $backup->size ? number_format($backup->size / 1024 / 1024, 2, ',', '.') . ' MB' : '—' }}</td>
                                <td>{{ $backup->user?->name ?? 'Sistem' }}</td>
                                <td>
                                    <x-filament::badge :color="match ($backup->status) { 'completed' => 'success', 'failed' => 'danger', default => 'warning' }">
                                        {{ match ($backup->status) { 'completed' => 'Selesai', 'failed' => 'Gagal', default => 'Diproses' } }}
                                    </x-filament::badge>
                                </td>
                                <td>
                                    @if ($backup->status === 'completed')
                                        <x-filament::button wire:click="downloadBackup({{ $backup->id }})" icon="heroicon-o-arrow-down-tray" size="sm" color="gray">
                                            Download
                                        </x-filament::button>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="backup-empty">Belum ada riwayat backup.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
