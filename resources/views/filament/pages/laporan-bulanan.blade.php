<x-filament-panels::page>
    <style>
        .operator-dashboard-section-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .operator-dashboard-left-panel {
            padding: 1.25rem;
        }

        .operator-dashboard-right-panel {
            padding: 1.25rem;
        }

        .operator-dashboard-info-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .operator-dashboard-section-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>

    <div style="display: flex; flex-direction: column; gap: 1.5rem; width: 100%;">
        {{-- Dashboard Cards --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">

            {{-- GTK Card --}}
            <div
                style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; border-left: 4px solid #3b82f6;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="background-color: #dbeafe; padding: 0.75rem; border-radius: 8px;">
                        <svg style="width: 24px; height: 24px; color: #1e40af;" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                        </svg>
                    </div>
                    <div>
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Guru & Tenaga Kependidikan</p>
                        <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937; margin: 0;">
                            {{ \App\Models\Gtk::where('sekolah_id', $this->getSchoolId())->count() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Siswa Card --}}
            <div
                style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; border-left: 4px solid #10b981;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="background-color: #dcfce7; padding: 0.75rem; border-radius: 8px;">
                        <svg style="width: 24px; height: 24px; color: #047857;" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v2h8v-2zM2 8a2 2 0 11-4 0 2 2 0 014 0zM18 15v2h5v-2a4 4 0 00-5-3.87M9 11a6 6 0 0112 0v2H9v-2z" />
                        </svg>
                    </div>
                    <div>
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Peserta Didik Aktif</p>
                        <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937; margin: 0;">
                            {{ \App\Models\Siswa::where('sekolah_id', $this->getSchoolId())->count() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Rombel Card --}}
            <div
                style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; border-left: 4px solid #a855f7;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="background-color: #e9d5ff; padding: 0.75rem; border-radius: 8px;">
                        <svg style="width: 24px; height: 24px; color: #7e22ce;" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Rombel</p>
                        <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937; margin: 0;">
                            {{ \App\Models\Rombel::where('sekolah_id', $this->getSchoolId())->count() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Sarpras Card --}}
            <div
                style="background-color: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; border-left: 4px solid #f59e0b;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="background-color: #fed7aa; padding: 0.75rem; border-radius: 8px;">
                        <svg style="width: 24px; height: 24px; color: #b45309;" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 3a2 2 0 012-2h6a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V3z" />
                        </svg>
                    </div>
                    <div>
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Gedung/Ruang</p>
                        <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937; margin: 0;">
                            {{ \App\Models\LaporanGedung::whereHas('laporan', function ($query) {
                                $query->where('sekolah_id', $this->getSchoolId());
                            })->count() }}
                        </p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Section Dua Kolom --}}
        <div class="operator-dashboard-section-grid">
            <div class="operator-dashboard-info-card">
                <div class="operator-dashboard-left-panel">
                    <div>
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <h3
                                    style="margin: 0.25rem 0 0 0; font-size: 1rem; line-height: 1.5rem; font-weight: 600; color: #111827;">
                                    Riwayat Pelaporan</h3>
                            </div>
                            @php
                                $hasValidatedReport = $this->getRiwayatLaporanDashboard()->contains(
                                    fn($laporan) => ($laporan->status ?? 'draft') === 'valid',
                                );
                            @endphp
                            @if ($hasValidatedReport)
                                <div
                                    style="padding: 0.375rem 0.625rem; background: #dcfce7; color: #166534; border-radius: 9999px; font-size: 0.75rem; line-height: 1rem; font-weight: 600;">
                                    PDF Tersedia
                                </div>
                            @endif
                        </div>

                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #f9fafb; text-align: left;">
                                        <th
                                            style="padding: 0.75rem; font-size: 0.75rem; line-height: 1rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                                            Periode</th>
                                        <th
                                            style="padding: 0.75rem; font-size: 0.75rem; line-height: 1rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                                            Status</th>
                                        @if ($hasValidatedReport)
                                            <th
                                                style="padding: 0.75rem; font-size: 0.75rem; line-height: 1rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                                                Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($this->getRiwayatLaporanDashboard() as $laporan)
                                        <tr style="border-bottom: 1px solid #f3f4f6;">
                                            <td
                                                style="padding: 0.875rem 0.75rem; color: #111827; font-size: 0.875rem; line-height: 1.25rem; font-weight: 500;">
                                                {{ \Carbon\Carbon::create($laporan->tahun, $laporan->bulan, 1)->translatedFormat('F Y') }}
                                            </td>
                                            <td style="padding: 0.875rem 0.75rem;">
                                                <span
                                                    style="display: inline-flex; align-items: center; padding: 0.25rem 0.625rem; border-radius: 9999px; background: {{ $laporan->status === 'valid' ? '#dcfce7' : '#fef3c7' }}; color: {{ $laporan->status === 'valid' ? '#166534' : '#92400e' }}; font-size: 0.75rem; line-height: 1rem; font-weight: 600;">
                                                    {{ ucfirst($laporan->status ?? 'draft') }}
                                                </span>
                                            </td>
                                            @if (($laporan->status ?? 'draft') === 'valid')
                                                <td style="padding: 0.875rem 0.75rem;">
                                                    <a href="{{ route('cetak-laporan.pdf', $laporan->sekolah) . '?laporan_id=' . $laporan->id }}"
                                                        target="_blank"
                                                        style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 0.75rem; background: #fee2e2; color: #b91c1c; border-radius: 0.75rem; text-decoration: none; font-size: 0.875rem; line-height: 1.25rem; font-weight: 600; border: 1px solid #fecaca;">
                                                        <svg style="width: 0.95rem; height: 0.95rem;"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        PDF
                                                    </a>
                                                </td>
                                            @elseif($hasValidatedReport)
                                                <td
                                                    style="padding: 0.875rem 0.75rem; color: #9ca3af; font-size: 0.875rem; line-height: 1.25rem;">
                                                    -
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3"
                                                style="padding: 1.5rem; text-align: center; color: #6b7280;">
                                                Belum ada riwayat pelaporan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="operator-dashboard-info-card">
                <div class="operator-dashboard-right-panel">
                    <div
                        style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <h3
                                style="margin: 0.25rem 0 0 0; font-size: 1rem; line-height: 1.5rem; font-weight: 600; color: #111827;">
                                Log Aktivitas Anda</h3>
                        </div>
                        <div
                            style="padding: 0.375rem 0.625rem; background: #ecfeff; color: #155e75; border-radius: 9999px; font-size: 0.75rem; line-height: 1rem; font-weight: 600;">
                            5 Terakhir
                        </div>
                    </div>

                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #f9fafb; text-align: left;">
                                    <th
                                        style="padding: 0.75rem; font-size: 0.75rem; line-height: 1rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                                        Waktu</th>
                                    <th
                                        style="padding: 0.75rem; font-size: 0.75rem; line-height: 1rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                                        Aktivitas</th>
                                    <th
                                        style="padding: 0.75rem; font-size: 0.75rem; line-height: 1rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                                        IP Address</th>
                                    <th
                                        style="padding: 0.75rem; font-size: 0.75rem; line-height: 1rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                                        Lokasi Akses</th>
                                    <th
                                        style="padding: 0.75rem; font-size: 0.75rem; line-height: 1rem; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                                        Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($this->getOperatorActivityLogs() as $log)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td
                                            style="padding: 0.875rem 0.75rem; color: #111827; font-size: 0.875rem; line-height: 1.25rem; white-space: nowrap;">
                                            {{ optional($log->created_at)->format('d/m/Y H:i') ?? '-' }}
                                        </td>
                                        <td style="padding: 0.875rem 0.75rem;">
                                            <span
                                                style="display: inline-flex; align-items: center; padding: 0.25rem 0.625rem; border-radius: 9999px; background: #f3f4f6; color: #374151; font-size: 0.75rem; line-height: 1rem; font-weight: 600;">
                                                {{ $log->event ?? '-' }}
                                            </span>
                                        </td>
                                        <td
                                            style="padding: 0.875rem 0.75rem; color: #111827; font-size: 0.875rem; line-height: 1.25rem; white-space: nowrap;">
                                            {{ $log->ip_address ?? '-' }}
                                        </td>
                                        <td
                                            style="padding: 0.875rem 0.75rem; color: #374151; font-size: 0.875rem; line-height: 1.25rem; white-space: nowrap;">
                                            {{ $this->getActivityLogAccessLocation($log) }}
                                        </td>
                                        <td
                                            style="padding: 0.875rem 0.75rem; color: #374151; font-size: 0.875rem; line-height: 1.25rem;">
                                            {{ $log->description ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="padding: 1.5rem; text-align: center; color: #6b7280;">
                                            Belum ada log aktivitas operator.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const syncKey = 'operator-access-location-synced-at';
            const syncedAt = Number(localStorage.getItem(syncKey) || 0);
            const sixHours = 6 * 60 * 60 * 1000;

            if (!navigator.geolocation || !window.fetch || Date.now() - syncedAt < sixHours) {
                return;
            }

            const formatPlaceName = (data) => {
                const address = data?.address || {};
                const parts = [
                    address.suburb || address.village || address.town || address.city || address.municipality,
                    address.county || address.city_district,
                    address.state,
                    address.country,
                ].filter(Boolean);

                return [...new Set(parts)].join(', ') || data?.display_name || null;
            };

            const reverseGeocode = async (latitude, longitude) => {
                try {
                    const url = new URL('https://nominatim.openstreetmap.org/reverse');
                    url.searchParams.set('format', 'jsonv2');
                    url.searchParams.set('lat', latitude);
                    url.searchParams.set('lon', longitude);
                    url.searchParams.set('zoom', '14');
                    url.searchParams.set('addressdetails', '1');
                    url.searchParams.set('accept-language', 'id');

                    const response = await fetch(url.toString(), {
                        headers: {
                            Accept: 'application/json',
                        },
                    });

                    if (!response.ok) {
                        return null;
                    }

                    return formatPlaceName(await response.json());
                } catch (error) {
                    return null;
                }
            };

            navigator.geolocation.getCurrentPosition(async (position) => {
                const {
                    latitude,
                    longitude,
                    accuracy
                } = position.coords;
                const placeName = await reverseGeocode(latitude, longitude);

                const response = await fetch(@js(route('activity-log.access-location')), {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @js(csrf_token()),
                    },
                    body: JSON.stringify({
                        latitude,
                        longitude,
                        accuracy,
                        place_name: placeName,
                    }),
                });

                if (response.ok) {
                    localStorage.setItem(syncKey, String(Date.now()));
                    window.location.reload();
                }
            }, () => {
                localStorage.setItem(syncKey, String(Date.now()));
            }, {
                enableHighAccuracy: false,
                maximumAge: sixHours,
                timeout: 10000,
            });
        })();
    </script>
</x-filament-panels::page>
