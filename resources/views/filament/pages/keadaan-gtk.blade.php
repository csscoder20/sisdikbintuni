@php
    $tenantName = \Filament\Facades\Filament::getTenant()?->nama ?? 'Sekolah';
@endphp

<div class="keadaan-gtk-page">
    {{-- Single Root Wrapper for Livewire 3 --}}
    <div>
        {{-- Header Section --}}
        <div style="margin-bottom: 2rem;">
            <h1 style="font-size: 1.875rem; font-weight: 800; color: #1e293b; letter-spacing: -0.025em; margin-bottom: 0.5rem;">
                📊 Analisis Keadaan GTK
            </h1>
            <p style="color: #64748b; font-size: 1rem;">
                Data Guru dan Tenaga Kependidikan untuk <strong>{{ $tenantName }}</strong>
            </p>
        </div>

        {{-- Stats Overview --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
            <div class="ks-stat-card" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white;">
                <div style="font-size: 0.875rem; font-weight: 600; opacity: 0.9; margin-bottom: 0.5rem;">Total GTK</div>
                <div style="font-size: 2rem; font-weight: 800;">{{ $totalGtk }}</div>
                <div style="font-size: 0.75rem; opacity: 0.8; margin-top: 0.5rem;">Orang Terdaftar</div>
            </div>
            <div class="ks-stat-card" style="background: white; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">🚹 Laki-Laki</div>
                <div style="font-size: 2rem; font-weight: 800; color: #1e293b;">{{ $totalGtkLakiLaki }}</div>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.5rem;">GTK Putra</div>
            </div>
            <div class="ks-stat-card" style="background: white; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem;">🚺 Perempuan</div>
                <div style="font-size: 2rem; font-weight: 800; color: #1e293b;">{{ $totalGtkPerempuan }}</div>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.5rem;">GTK Putri</div>
            </div>
        </div>

        <div style="display: grid; gap: 2.5rem;">
            
            {{-- Tabel 0: Jenis Kelamin (New) --}}
            <div class="ks-card" wire:key="card-jenis-kelamin">
                <div class="ks-card-header" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                    <h2 style="color: #475569;">👥 Jumlah GTK Berdasarkan Jenis Kelamin</h2>
                </div>
                <div class="ks-table-container">
                    <table class="ks-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">NO</th>
                                <th>JENIS GTK</th>
                                <th>LAKI-LAKI</th>
                                <th>PEREMPUAN</th>
                                <th style="background: #f1f5f9;">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gtkByJenis as $item)
                            <tr wire:key="row-jk-{{ $loop->index }}">
                                <td style="text-align: center; background: #f8fafc;">{{ ($gtkByJenis->currentPage() - 1) * $gtkByJenis->perPage() + $loop->iteration }}</td>
                                <td style="font-weight: 600; color: #0f172a; text-align: left;">{{ $item->jenis_gtk }}</td>
                                <td style="color: #2563eb; font-weight: 600;">{{ $item->laki_laki }}</td>
                                <td style="color: #db2777; font-weight: 600;">{{ $item->perempuan }}</td>
                                <td style="font-weight: 800; background: #f8fafc;">{{ $item->total }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="ks-empty">Data tidak tersedia</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="ks-card-footer">
                    {{ $gtkByJenis->links('pagination::ks-pagination') }}
                </div>
            </div>

            {{-- Tabel 1: Agama --}}
            <div class="ks-card" wire:key="card-agama">
                <div class="ks-card-header" style="background: linear-gradient(135deg, #eff6ff, #dbeafe);">
                    <h2 style="color: #1e40af;">🕌 Jumlah GTK Berdasarkan Agama</h2>
                </div>
                <div class="ks-table-container">
                    <table class="ks-table">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 50px;">NO</th>
                                <th rowspan="2">JENIS GTK</th>
                                <th colspan="3">ISLAM</th>
                                <th colspan="3">PROTESTAN</th>
                                <th colspan="3">KATOLIK</th>
                                <th colspan="3">HINDU</th>
                                <th colspan="3">BUDHA</th>
                            </tr>
                            <tr>
                                <th>L</th><th>P</th><th>Σ</th>
                                <th>L</th><th>P</th><th>Σ</th>
                                <th>L</th><th>P</th><th>Σ</th>
                                <th>L</th><th>P</th><th>Σ</th>
                                <th>L</th><th>P</th><th>Σ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gtkAgama as $item)
                            <tr wire:key="row-agama-{{ $loop->index }}">
                                <td style="text-align: center; background: #f8fafc;">{{ ($gtkAgama->currentPage() - 1) * $gtkAgama->perPage() + $loop->iteration }}</td>
                                <td style="font-weight: 600; color: #0f172a; text-align: left;">{{ $item->jenis_gtk }}</td>
                                <td>{{ $item->islam_l }}</td><td>{{ $item->islam_p }}</td><td style="font-weight: 700;">{{ $item->islam_jml }}</td>
                                <td>{{ $item->kristen_protestan_l }}</td><td>{{ $item->kristen_protestan_p }}</td><td style="font-weight: 700;">{{ $item->kristen_protestan_jml }}</td>
                                <td>{{ $item->katolik_l }}</td><td>{{ $item->katolik_p }}</td><td style="font-weight: 700;">{{ $item->katolik_jml }}</td>
                                <td>{{ $item->hindu_l }}</td><td>{{ $item->hindu_p }}</td><td style="font-weight: 700;">{{ $item->hindu_jml }}</td>
                                <td>{{ $item->budha_l }}</td><td>{{ $item->budha_p }}</td><td style="font-weight: 700;">{{ $item->budha_jml }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="17" class="ks-empty">Data tidak tersedia</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="ks-card-footer">
                    {{ $gtkAgama->links('pagination::ks-pagination') }}
                </div>
            </div>

            {{-- Tabel 2: Daerah Asal --}}
            <div class="ks-card" wire:key="card-daerah">
                <div class="ks-card-header" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
                    <h2 style="color: #15803d;">🌍 Jumlah GTK Berdasarkan Daerah Asal</h2>
                </div>
                <div class="ks-table-container">
                    <table class="ks-table">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 50px;">NO</th>
                                <th rowspan="2">JENIS GTK</th>
                                <th colspan="3">PAPUA</th>
                                <th colspan="3">NON PAPUA</th>
                            </tr>
                            <tr>
                                <th>L</th><th>P</th><th>Σ</th>
                                <th>L</th><th>P</th><th>Σ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gtkDaerah as $item)
                            <tr wire:key="row-daerah-{{ $loop->index }}">
                                <td style="text-align: center; background: #f8fafc;">{{ ($gtkDaerah->currentPage() - 1) * $gtkDaerah->perPage() + $loop->iteration }}</td>
                                <td style="font-weight: 600; color: #0f172a; text-align: left;">{{ $item->jenis_gtk }}</td>
                                <td>{{ $item->papua_l }}</td><td>{{ $item->papua_p }}</td><td style="font-weight: 700;">{{ $item->papua_jml }}</td>
                                <td>{{ $item->non_papua_l }}</td><td>{{ $item->non_papua_p }}</td><td style="font-weight: 700;">{{ $item->non_papua_jml }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="ks-empty">Data tidak tersedia</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="ks-card-footer">
                    {{ $gtkDaerah->links('pagination::ks-pagination') }}
                </div>
            </div>

            {{-- Tabel 3: Status --}}
            <div class="ks-card" wire:key="card-status">
                <div class="ks-card-header" style="background: linear-gradient(135deg, #faf5ff, #f3e8ff);">
                    <h2 style="color: #7e22ce;">🎖️ Status Kepegawaian per Jenis GTK</h2>
                </div>
                <div class="ks-table-container">
                    <table class="ks-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">NO</th>
                                <th>JENIS GTK</th>
                                <th>PNS</th>
                                <th>PPPK</th>
                                <th>HONORER SEKOLAH</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gtkStatusKepegawaian as $item)
                            <tr wire:key="row-status-{{ $loop->index }}">
                                <td style="text-align: center; background: #f8fafc;">{{ ($gtkStatusKepegawaian->currentPage() - 1) * $gtkStatusKepegawaian->perPage() + $loop->iteration }}</td>
                                <td style="font-weight: 600; color: #0f172a; text-align: left;">{{ $item->jenis_gtk }}</td>
                                <td>{{ $item->pns }}</td>
                                <td>{{ $item->pppk }}</td>
                                <td>{{ $item->honorer_sekolah }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="ks-empty">Data tidak tersedia</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="ks-card-footer">
                    {{ $gtkStatusKepegawaian->links('pagination::ks-pagination') }}
                </div>
            </div>

            {{-- Tabel 4: Umur --}}
            <div class="ks-card" wire:key="card-umur">
                <div class="ks-card-header" style="background: linear-gradient(135deg, #fff7ed, #ffedd5);">
                    <h2 style="color: #c2410c;">🎂 Jumlah GTK Berdasarkan Umur</h2>
                </div>
                <div class="ks-table-container">
                    <table class="ks-table">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 50px;">NO</th>
                                <th rowspan="2">JENIS GTK</th>
                                @for ($age = 13; $age <= 23; $age++)
                                    <th colspan="3">{{ $age }} Thn</th>
                                @endfor
                            </tr>
                            <tr>
                                @for ($age = 13; $age <= 23; $age++)
                                    <th style="font-size: 0.65rem;">L</th>
                                    <th style="font-size: 0.65rem;">P</th>
                                    <th style="font-size: 0.65rem;">Σ</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gtkUmur as $item)
                            <tr wire:key="row-umur-{{ $loop->index }}">
                                <td style="text-align: center; background: #f8fafc;">{{ ($gtkUmur->currentPage() - 1) * $gtkUmur->perPage() + $loop->iteration }}</td>
                                <td style="font-weight: 600; color: #0f172a; text-align: left; min-width: 150px;">{{ $item->jenis_gtk }}</td>
                                @for ($age = 13; $age <= 23; $age++)
                                    @php $prefix = 'umur_' . $age; @endphp
                                    <td style="font-size: 0.75rem;">{{ $item->{$prefix . '_l'} }}</td>
                                    <td style="font-size: 0.75rem;">{{ $item->{$prefix . '_p'} }}</td>
                                    <td style="font-size: 0.75rem; font-weight: 700; background: #fffcf9;">{{ $item->{$prefix . '_jml'} }}</td>
                                @endfor
                            </tr>
                            @empty
                            <tr><td colspan="35" class="ks-empty">Data tidak tersedia</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="ks-card-footer">
                    {{ $gtkUmur->links('pagination::ks-pagination') }}
                </div>
            </div>

            {{-- Tabel 5: Pendidikan --}}
            <div class="ks-card" wire:key="card-pendidikan">
                <div class="ks-card-header" style="background: linear-gradient(135deg, #fff1f2, #ffe4e6);">
                    <h2 style="color: #be123c;">🎓 Jumlah GTK Berdasarkan Pendidikan</h2>
                </div>
                <div class="ks-table-container">
                    <table class="ks-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">NO</th>
                                <th>JENIS GTK</th>
                                <th>SLTA</th>
                                <th>DI</th>
                                <th>DII</th>
                                <th>DIII</th>
                                <th>S1</th>
                                <th>S2</th>
                                <th>S3</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gtkPendidikan as $item)
                            <tr wire:key="row-pend-{{ $loop->index }}">
                                <td style="text-align: center; background: #f8fafc;">{{ ($gtkPendidikan->currentPage() - 1) * $gtkPendidikan->perPage() + $loop->iteration }}</td>
                                <td style="font-weight: 600; color: #0f172a; text-align: left;">{{ $item->jenis_gtk }}</td>
                                <td>{{ $item->slta }}</td>
                                <td>{{ $item->di }}</td>
                                <td>{{ $item->dii }}</td>
                                <td>{{ $item->diii }}</td>
                                <td>{{ $item->s1 }}</td>
                                <td>{{ $item->s2 }}</td>
                                <td>{{ $item->s3 }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="10" class="ks-empty">Data tidak tersedia</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="ks-card-footer">
                    {{ $gtkPendidikan->links('pagination::ks-pagination') }}
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
    .keadaan-gtk-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
    }
    .ks-stat-card {
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
        transition: transform 0.2s;
    }
    .ks-card {
        background: white;
        border-radius: 1.25rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .ks-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .ks-card-header h2 {
        font-size: 1.125rem;
        font-weight: 700;
        margin: 0;
    }
    .ks-table-container {
        overflow-x: auto;
    }
    .ks-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }
    .ks-table th {
        background: #f8fafc;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        color: #475569;
        text-align: center;
    }
    .ks-table td {
        padding: 0.75rem 1rem;
        border: 1px solid #f1f5f9;
        text-align: center;
        color: #475569;
    }
    .ks-empty {
        padding: 3rem !important;
        color: #94a3b8;
        font-style: italic;
    }
    .ks-card-footer {
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: center;
    }
</style>
@endpush
