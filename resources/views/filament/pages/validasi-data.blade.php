<x-filament-panels::page>
<style>
:root{--ok:#10b981;--err:#ef4444;--act:#3b82f6;}
*{box-sizing:border-box;}
/* Inherit Filament font — no override */
.vd{display:flex;flex-direction:column;gap:0;}
.vd-top{flex-shrink:0;padding:0 0 1rem;}
.vd-stepper{display:flex;align-items:flex-start;justify-content:center;gap:0;overflow-x:auto;padding-bottom:4px;}
.vd-step{display:flex;flex-direction:column;align-items:center;flex:1;min-width:90px;position:relative;}
.vd-step:not(:last-child)::after{content:'';position:absolute;top:19px;left:calc(50% + 22px);right:calc(-50% + 22px);height:2px;background:#e5e7eb;z-index:0;}
.vd-step.done:not(:last-child)::after,.vd-step.active:not(:last-child)::after{background:var(--ok);}
.vd-circle{width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.875rem;z-index:1;border:2px solid #e5e7eb;background:#fff;color:#9ca3af;transition:all .3s;cursor:pointer;}
.vd-step.done .vd-circle{background:var(--ok);border-color:var(--ok);color:#fff;}
.vd-step.active .vd-circle{background:var(--act);border-color:var(--act);color:#fff;box-shadow:0 0 0 4px rgba(59,130,246,.2);}
.vd-step.invalid .vd-circle{background:var(--err);border-color:var(--err);color:#fff;}
.vd-label{font-size:0.75rem;font-weight:600;color:#9ca3af;margin-top:5px;text-align:center;line-height:1.3;}
.vd-step.active .vd-label{color:var(--act);font-weight:700;}
.vd-step.done .vd-label{color:var(--ok);}
.vd-card{display:flex;flex-direction:column;background:#fff;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,.08);overflow:hidden;}

.vd-cb{padding:1.25rem 1.5rem;display:flex;flex-direction:column;gap:.875rem;}
.vd-footer{flex-shrink:0;display:flex;gap:.75rem;padding:1rem 1.5rem;border-top:1px solid #f1f5f9;background:#f8fafc;}
.btn{padding:.5rem 1.25rem;border-radius:8px;font-weight:600;font-size:0.875rem;border:none;cursor:pointer;transition:all .2s;display:inline-flex;align-items:center;gap:5px;}
.btn-back{background:#e5e7eb;color:#374151;}.btn-back:hover:not(:disabled){background:#d1d5db;}.btn-back:disabled{opacity:.4;cursor:not-allowed;}
.btn-next{background:linear-gradient(135deg,var(--act),#2563eb);color:#fff;margin-left:auto;}.btn-next:hover:not(:disabled){filter:brightness(1.1);transform:translateY(-1px);}.btn-next:disabled{background:#9ca3af;transform:none;cursor:not-allowed;}
.btn-finish{background:linear-gradient(135deg,var(--ok),#059669);color:#fff;margin-left:auto;}.btn-finish:hover:not(:disabled){filter:brightness(1.1);transform:translateY(-1px);}.btn-finish:disabled{background:#9ca3af;transform:none;cursor:not-allowed;}
/* Alerts */
.alert-err{background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:.75rem 1rem;}
.alert-err h4{color:#dc2626;font-size:0.875rem;font-weight:600;margin:0 0 .375rem;}
.alert-err ul{margin:0;padding-left:1.25rem;color:#b91c1c;font-size:0.875rem;line-height:1.6;}

.alert-warn{background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;padding:.75rem 1rem;}
.alert-warn h4{color:#b45309;font-size:0.875rem;font-weight:600;margin:0 0 .375rem;}
.alert-warn ul{margin:0;padding-left:1.25rem;color:#92400e;font-size:0.875rem;line-height:1.6;}
/* Stat box */
.stat{display:flex;align-items:center;gap:1rem;background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1px solid #bfdbfe;border-radius:8px;padding:.75rem 1.25rem;}
.stat-num{font-size:1.75rem;font-weight:800;color:#1d4ed8;line-height:1;}
.stat-lbl{font-size:0.875rem;color:#3b82f6;font-weight:600;}
.stat-sub{font-size:0.75rem;color:#6b7280;margin-top:2px;}
/* Table */
.tbl{width:100%;border-collapse:collapse;font-size:0.875rem;}
.tbl th{background:#f8fafc;padding:8px 10px;text-align:left;font-weight:600;color:#374151;border-bottom:2px solid #e5e7eb;white-space:nowrap;font-size:0.75rem;text-transform:uppercase;letter-spacing:.04em;}
.tbl td{padding:7px 10px;border-bottom:1px solid #f3f4f6;color:#4b5563;vertical-align:middle;}
.tbl tr:last-child td{border-bottom:none;}
.tbl tr:hover td{background:#fafafa;}
/* Badges */
.bOk{background:#dcfce7;color:#15803d;padding:2px 8px;border-radius:999px;font-size:0.75rem;font-weight:600;}
.bErr{background:#fee2e2;color:#dc2626;padding:2px 8px;border-radius:999px;font-size:0.75rem;font-weight:600;}
.bBlue{background:#dbeafe;color:#1d4ed8;padding:2px 8px;border-radius:999px;font-size:0.75rem;font-weight:600;}
.more{font-size:0.75rem;color:#9ca3af;text-align:center;padding:.4rem;font-style:italic;}
/* Period badge */
.period-badge{display:inline-flex;align-items:center;gap:7px;background:linear-gradient(135deg,#059669,#10b981);color:#fff;padding:5px 14px;border-radius:999px;font-size:0.875rem;font-weight:600;margin-bottom:.75rem;}
/* Big success */
.big-ok{display:flex;align-items:center;gap:1rem;background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #86efac;border-radius:10px;padding:1rem 1.25rem;}
.big-ok-title{font-weight:700;color:#15803d;font-size:0.875rem;}
.big-ok-sub{font-size:0.75rem;color:#166534;margin-top:2px;}
/* Badge status in header */
.fi-pagination { padding: 15px 0; }
.badge-valid{background:#dcfce7;color:#15803d;padding:4px 12px;border-radius:999px;font-size:0.875rem;font-weight:600;}
.badge-invalid{background:#fee2e2;color:#dc2626;padding:4px 12px;border-radius:999px;font-size:0.875rem;font-weight:600;}
</style>


<div class="vd">
@php
    $cur      = $this->currentStep;
    $total    = $this->totalSteps;
    $labels   = $this->getStepLabels();
    $statuses = $this->stepStatuses;
    $isValid  = $statuses[$cur] ?? false;
    $allValid = collect($statuses)->every(fn($s) => $s);
    $icons  = [1=>'🏫',2=>'🏗️',3=>'📚',4=>'👥',5=>'💰',6=>'👧',7=>'👨‍🏫',8=>'🎓',9=>'💳',10=>'⏰',11=>'📋'];
    $colors = [1=>'#dbeafe',2=>'#fef3c7',3=>'#ede9fe',4=>'#f3e8ff',5=>'#dcfce7',6=>'#fce7f3',7=>'#fee2e2',8=>'#ffedd5',9=>'#e0e7ff',10=>'#ccfbf1',11=>'#e0f2fe'];
@endphp

{{-- Top: stepper --}}
<div class="vd-top">
    <div class="vd-stepper">
        @foreach($labels as $n => $lbl)
            @php
                $cls = 'vd-step';
                if ($n === $cur) $cls .= ' active';
                else $cls .= ($statuses[$n] ?? false) ? ' done' : ' invalid';
            @endphp
            <div class="{{ $cls }}" wire:click="$set('currentStep',{{ $n }})" style="cursor:pointer;">
                <div class="vd-circle">
                    @if($n === $cur) {{ $n }}
                    @elseif($statuses[$n] ?? false) ✓
                    @else ✕
                    @endif
                </div>
                <div class="vd-label">{{ $lbl }}</div>
            </div>
        @endforeach
    </div>
</div>

{{-- Main card --}}
<div class="vd-card">



    {{-- Body --}}
    <div class="vd-cb">

    {{-- ======================== STEP 1 ======================== --}}
    @if($cur === 1)
        @php $missing = $this->getMissingProfilFields(); $rows = $this->getProfilRows(); @endphp
        @if(empty($missing))
            <div class="big-ok">
                <span style="font-size:1.6rem;">✅</span>
                <div>
                <div class="big-ok-title">Semua data profil sekolah sudah lengkap!</div>
                    <div class="big-ok-sub">{{ $rows->total() }} field terisi dengan benar</div>
                </div>
            </div>
        @else
            <div class="alert-err">
                <p style="font-size:.8rem;color:#b91c1c;margin:0;">Terdapat <strong>{{ count($missing) }} field</strong> yang belum diisi. Silakan lengkapi data profil sekolah terlebih dahulu. <a href="{{ route('filament.'.filament()->getId().'.pages.profil', ['tenant' => filament()->getTenant()]) }}" style="color:#b91c1c;font-weight:600;">Perbaiki Sekarang!</a></p>
            </div>
        @endif
        <div style="flex:1;overflow:auto;">
            <table class="tbl">
                <thead><tr><th width="5%">No</th><th width="35%">Data Sekolah</th><th>Nilai</th><th width="12%">Status</th></tr></thead>
                <tbody>
                    @foreach($rows as $r)
                    <tr>
                        <td style="color:#9ca3af;">{{ ($rows->currentPage() - 1) * $rows->perPage() + $loop->iteration }}</td>
                        <td style="color:#374151;">{{ $r['label'] }}</td>
                        <td style="color:{{ $r['ok'] ? '#111827' : '#dc2626' }};">{{ $r['value'] ?? '—' }}</td>
                        <td>@if($r['ok'])<span class="bOk">✓</span>@else<span class="bErr">✕</span>@endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($rows->hasPages())
                <div class="mt-4 px-2">
                    <x-filament::pagination :paginator="$rows" class="fi-pagination" />
                </div>
            @endif
        </div>
    @endif

    {{-- ======================== STEP 2 ======================== --}}
    @if($cur === 2)
        @php $list = $this->getSarprasList(); $total = $this->getSarprasCount(); @endphp
        @if($total === 0)
            <div class="alert-err">
                <p style="font-size:.8rem;color:#b91c1c;margin:0;">Belum ada data Sarana &amp; Prasarana. Tambahkan minimal 1 data gedung/ruang untuk melanjutkan. <a href="{{ route('filament.'.filament()->getId().'.resources.laporan-gedung.index', ['tenant' => filament()->getTenant()]) }}" style="color:#b91c1c;font-weight:600;">Perbaiki Sekarang!</a></p>
            </div>
        @else
            <div class="stat">
                <div class="stat-num">{{ $total }}</div>
                <div><div class="stat-lbl">Data Sarana & Prasarana</div><div class="stat-sub">Gedung & ruang yang tercatat</div></div>
            </div>
        @endif
        <div style="flex:1;overflow:auto;">
            @if(!$list->isEmpty())
            <table class="tbl">
                <thead><tr><th width="5%">No</th><th>Nama Sarpras</th><th width="8%">Jml</th><th width="8%">Baik</th><th width="8%">Rusak</th><th width="15%">Kepemilikan</th><th width="12%">Status</th></tr></thead>
                <tbody>
                    @foreach($list as $i => $s)
                    <tr>
                        <td>{{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}</td>
                        <td>{{ $s->nama_ruang ?? '-' }}</td>
                        <td>{{ $s->jumlah_total ?? 0 }}</td>
                        <td><span class="bOk">{{ $s->jumlah_baik ?? 0 }}</span></td>
                        <td><span class="bErr">{{ $s->jumlah_rusak ?? 0 }}</span></td>
                        <td><span class="bBlue">{{ $s->status_kepemilikan ?? '-' }}</span></td>
                        <td style="text-align:center;"><span class="bOk">✓</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 px-2">
                <x-filament::pagination :paginator="$list" class="fi-pagination" />
            </div>
            @endif
        </div>
    @endif

    {{-- ======================== STEP 3 ======================== --}}
    @if($cur === 3)
        @php
            $list = $this->getMapelList();
            $total = $this->getMapelCount();
        @endphp
        @if($total === 0)
            <div class="alert-err">
                <p style="font-size:.8rem;color:#b91c1c;margin:0;">Belum ada data Mata Pelajaran. Harap tambahkan data mapel terlebih dahulu. <a href="{{ route('filament.'.filament()->getId().'.resources.mapels.index', ['tenant' => filament()->getTenant()]) }}" style="color:#b91c1c;font-weight:600;">Perbaiki Sekarang!</a></p>
            </div>
        @else
            @if(!$statuses[3])
                <div class="alert-err" style="margin-bottom:1rem;">
                    <p style="font-size:.8rem;color:#b91c1c;margin:0;"><strong>Validasi Gagal:</strong> Belum ada guru yang ditugaskan ke mata pelajaran manapun. Silakan isi sebaran jam mengajar. <a href="{{ route('filament.'.filament()->getId().'.resources.sebaran-jam-ajar.index', ['tenant' => filament()->getTenant()]) }}" style="color:#b91c1c;font-weight:600;">Perbaiki Sekarang!</a></p>
                </div>
            @endif
            <div class="stat">
                <div class="stat-num">{{ $total }}</div>
                <div><div class="stat-lbl">Mata Pelajaran</div><div class="stat-sub">Total: {{ $total }} mapel</div></div>
            </div>
        @endif
        <div style="flex:1;overflow:auto;">
            @if($total > 0)
            <table class="tbl">
                <thead><tr><th width="5%">No</th><th>Nama Mapel</th><th width="15%">Kode</th><th width="15%">Jenjang</th><th width="15%">JJP</th><th width="12%">Status</th></tr></thead>
                <tbody>
                    @foreach($list as $i => $m)
                    <tr>
                        <td>{{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}</td>
                        <td>{{ $m->nama_mapel }}</td>
                        <td>{{ $m->kode_mapel ?? '-' }}</td>
                        <td>{{ $m->jenjang ?? '-' }}</td>
                        <td>{{ $m->jjp ?? '-' }}</td>
                        <td style="text-align:center;">
                            @if($m->mengajars_exists)
                                <span class="bOk">✓</span>
                            @else
                                <span class="bErr">✕</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 px-2">
                <x-filament::pagination :paginator="$list" class="fi-pagination" />
            </div>
            @endif
        </div>
    @endif

    {{-- ======================== STEP 4 ======================== --}}
    @if($cur === 4)
        @php 
            $list = $this->getRombelList(); 
            $emptyRombels = $this->getEmptyRombels(); 
            $totalRombel = $this->getRombelCount();
            $totalSiswaRombel = $this->getTotalSiswaRombel();
        @endphp
        @if($totalRombel === 0)
            <div class="alert-err">
                <p style="font-size:.8rem;color:#b91c1c;margin:0;">Belum ada data Rombel. Harap tambahkan rombel terlebih dahulu. <a href="{{ route('filament.'.filament()->getId().'.resources.rombel.index', ['tenant' => filament()->getTenant()]) }}" style="color:#b91c1c;font-weight:600;">Perbaiki Sekarang!</a></p>
            </div>
        @else
            <div class="stat">
                <div class="stat-num">{{ $totalRombel }}</div>
                <div><div class="stat-lbl">Rombel Terdaftar</div><div class="stat-sub">Total siswa: {{ $totalSiswaRombel }} orang</div></div>
                @if(!empty($emptyRombels))
                <div style="width:2px;background:#93c5fd;margin:0 10px;align-self:stretch;"></div>
                <div style="flex:1;">
                    <p style="font-size:.8rem;color:#dc2626;margin:0;font-weight:500;">Terdapat <strong>{{ count($emptyRombels) }} rombel</strong> yang belum memiliki siswa. Harap tambahkan siswa ke rombel tersebut. <a href="{{ route('filament.'.filament()->getId().'.resources.rombel.index', ['tenant' => filament()->getTenant()]) }}" style="color:#dc2626;font-weight:700;">Perbaiki Sekarang!</a></p>
                </div>
                @endif
            </div>
        @endif
        <div style="flex:1;overflow:auto;">
            @if(!$list->isEmpty())
            <table class="tbl">
                <thead><tr><th width="5%">No</th><th>Nama Rombel</th><th width="12%">Tingkat</th><th width="15%">Jumlah Siswa</th><th width="12%">Status</th></tr></thead>
                <tbody>
                    @foreach($list as $i => $r)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $r->nama }}</td>
                        <td>{{ $r->tingkat ?? '-' }}</td>
                        <td>{{ $r->siswa_count }}</td>
                        <td>@if($r->siswa_count > 0)<span class="bOk">✓</span>@else<span class="bErr">✕</span>@endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 px-2">
                <x-filament::pagination :paginator="$list" class="fi-pagination" />
            </div>
            @endif
        </div>
    @endif

    {{-- ======================== STEP 5 ======================== --}}
    @if($cur === 5)
        @php
            $list = $this->getLaporanKeuanganList();
            $total = $this->getLaporanKeuanganCount();
        @endphp
        @if($total === 0)
            <div class="alert-err">
                <p style="font-size:.8rem;color:#b91c1c;margin:0;">Belum ada data Keuangan. Harap lengkapi laporan keuangan terlebih dahulu. <a href="{{ route('filament.'.filament()->getId().'.resources.laporan-keuangan.index', ['tenant' => filament()->getTenant()]) }}" style="color:#b91c1c;font-weight:600;">Perbaiki Sekarang!</a></p>
            </div>
        @else
            <div class="stat">
                <div class="stat-num">{{ $total }}</div>
                <div><div class="stat-lbl">Laporan Keuangan</div><div class="stat-sub">Total: {{ $total }} laporan</div></div>
            </div>
        @endif
        <div style="flex:1;overflow:auto;">
            @if($total > 0)
            <table class="tbl">
                <thead><tr><th width="5%">No</th><th>Tanggal</th><th width="15%">Jenis</th><th>Keterangan</th><th width="15%">Nominal</th><th width="15%">Saldo</th><th width="12%">Status</th></tr></thead>
                <tbody>
                    @foreach($list as $i => $k)
                    <tr>
                        <td>{{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}</td>
                        <td>{{ $k->tanggal ? $k->tanggal->format('d M Y') : '-' }}</td>
                        <td style="font-weight:600;color:{{ $k->jenis_transaksi === 'kredit' ? '#15803d' : '#dc2626' }};">{{ ucfirst($k->jenis_transaksi ?? '-') }}</td>
                        <td>{{ $k->keterangan ?? '-' }}</td>
                        <td>Rp {{ number_format($k->nominal ?? 0, 0, ',', '.') }}</td>
                        <td style="font-weight:700;color:{{ ($k->saldo ?? 0) < 0 ? '#dc2626' : '#15803d' }};">Rp {{ number_format($k->saldo ?? 0, 0, ',', '.') }}</td>
                        <td style="text-align:center;"><span class="bOk">✓</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 px-2">
                <x-filament::pagination :paginator="$list" class="fi-pagination" />
            </div>
            @endif
        </div>
    @endif

    {{-- ======================== STEP 6 ======================== --}}
    @if($cur === 6)
        @php $list = $this->getSiswaList(); $total = $this->getSiswaCount(); $incompleteSiswa = $this->getIncompleteSiswaInfo(); @endphp
        @if($total === 0)
            <div class="alert-err">
                <p style="font-size:.8rem;color:#b91c1c;margin:0;">Belum ada data Nominatif Siswa. Harap tambahkan data siswa terlebih dahulu. <a href="{{ route('filament.'.filament()->getId().'.resources.siswa.index', ['tenant' => filament()->getTenant()]) }}" style="color:#b91c1c;font-weight:600;">Perbaiki Sekarang!</a></p>
            </div>
        @else
            <div class="stat">
                <div class="stat-num">{{ $total }}</div>
                <div><div class="stat-lbl">Total Peserta Didik</div><div class="stat-sub">{{ $total - count($incompleteSiswa) }} lengkap · {{ count($incompleteSiswa) }} belum lengkap</div></div>
                @if(!empty($incompleteSiswa))
                <div style="width:2px;background:#93c5fd;margin:0 10px;align-self:stretch;"></div>
                <div style="flex:1;">
                    <p style="font-size:.8rem;color:#dc2626;margin:0;font-weight:500;">Terdapat <strong>{{ count($incompleteSiswa) }} siswa</strong> dengan data yang belum lengkap. Silakan lengkapi data siswa tersebut. <a href="{{ route('filament.'.filament()->getId().'.resources.siswa.index', ['tenant' => filament()->getTenant()]) }}" style="color:#dc2626;font-weight:700;">Perbaiki Sekarang!</a></p>
                </div>
                @endif
            </div>
        @endif
        <div style="flex:1;overflow:auto;">
            @if($total > 0)
            <table class="tbl" style="min-width: 2500px;">
                <thead>
                    <tr>
                        <th width="40px">No</th>
                        <th>Nama Siswa</th>
                        <th>NISN</th>
                        <th>No. KK</th>
                        <th>NIK</th>
                        <th>No. BPJS</th>
                        <th>Tempat Lahir</th>
                        <th>Tgl Lahir</th>
                        <th>JK</th>
                        <th>Agama</th>
                        <th>Alamat</th>
                        <th>Desa/Kel</th>
                        <th>Kecamatan</th>
                        <th>Kabupaten</th>
                        <th>Provinsi</th>
                        <th>Asal</th>
                        <th>Ayah</th>
                        <th>Ibu</th>
                        <th>Wali</th>
                        <th>HP Ortu</th>
                        <th>Disabilitas</th>
                        <th>Beasiswa</th>
                        <th>Status Siswa</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $i => $s)
                    <tr>
                        <td>{{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}</td>
                        <td>{{ $s->nama }}</td>
                        <td>{{ $s->nisn ?? '-' }}</td>
                        <td>{{ $s->nokk ?? '-' }}</td>
                        <td>{{ $s->nik ?? '-' }}</td>
                        <td>{{ $s->nobpjs ?? '-' }}</td>
                        <td>{{ $s->tempat_lahir ?? '-' }}</td>
                        <td>{{ $s->tanggal_lahir ?? '-' }}</td>
                        <td>{{ $s->jenis_kelamin ? (stripos($s->jenis_kelamin,'laki')!==false?'L':'P') : '-' }}</td>
                        <td>{{ $s->agama ?? '-' }}</td>
                        <td>{{ $s->alamat ?? '-' }}</td>
                        <td>{{ $s->desa ?? '-' }}</td>
                        <td>{{ $s->kecamatan ?? '-' }}</td>
                        <td>{{ $s->kabupaten ?? '-' }}</td>
                        <td>{{ $s->provinsi ?? '-' }}</td>
                        <td>{{ $s->daerah_asal ?? '-' }}</td>
                        <td>{{ $s->nama_ayah ?? '-' }}</td>
                        <td>{{ $s->nama_ibu ?? '-' }}</td>
                        <td>{{ $s->nama_wali ?? '-' }}</td>
                        <td>{{ $s->nohp_ortuwali ?? '-' }}</td>
                        <td>{{ $s->disabilitas ?? '-' }}</td>
                        <td>{{ $s->beasiswa ?? '-' }}</td>
                        <td>{{ $s->status ?? '-' }}</td>
                        <td style="text-align:center;">@if($this->isSiswaComplete($s))<span class="bOk">✓</span>@else<span class="bErr">✕</span>@endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 px-2">
                <x-filament::pagination :paginator="$list" class="fi-pagination" />
            </div>
            @endif
        </div>
    @endif

    {{-- ======================== STEP 7 ======================== --}}
    @if($cur === 7)
        @php $list = $this->getGtkList(); $total = $this->getGtkCount(); $incompleteGtk = $this->getIncompleteGtkInfo(); @endphp
        @if($total === 0)
            <div class="alert-err">
                <p style="font-size:.8rem;color:#b91c1c;margin:0;">Belum ada data Nominatif GTK. Harap tambahkan data GTK terlebih dahulu. <a href="{{ route('filament.'.filament()->getId().'.resources.gtk.index', ['tenant' => filament()->getTenant()]) }}" style="color:#b91c1c;font-weight:600;">Perbaiki Sekarang!</a></p>
            </div>
        @else
            <div class="stat">
                <div class="stat-num">{{ $total }}</div>
                <div><div class="stat-lbl">Total GTK</div><div class="stat-sub">{{ $total - count($incompleteGtk) }} lengkap · {{ count($incompleteGtk) }} belum lengkap</div></div>
                @if(!empty($incompleteGtk))
                <div style="width:2px;background:#93c5fd;margin:0 10px;align-self:stretch;"></div>
                <div style="flex:1;">
                    <p style="font-size:.8rem;color:#dc2626;margin:0;font-weight:500;">Terdapat <strong>{{ count($incompleteGtk) }} GTK</strong> dengan data yang belum lengkap. Silakan lengkapi data GTK tersebut. <a href="{{ route('filament.'.filament()->getId().'.resources.gtk.index', ['tenant' => filament()->getTenant()]) }}" style="color:#dc2626;font-weight:700;">Perbaiki Sekarang!</a></p>
                </div>
                @endif
            </div>
        @endif
        <div style="flex:1;overflow:auto;">
            @if($total > 0)
            <table class="tbl" style="min-width: 2500px;">
                <thead>
                    <tr>
                        <th width="40px">No</th>
                        <th>Nama GTK</th>
                        <th>NIP</th>
                        <th>NIK</th>
                        <th>Nokarpeg</th>
                        <th>NUPTK</th>
                        <th>Tempat Lahir</th>
                        <th>Tgl Lahir</th>
                        <th>JK</th>
                        <th>Agama</th>
                        <th>Alamat</th>
                        <th>Desa/Kel</th>
                        <th>Kecamatan</th>
                        <th>Kabupaten</th>
                        <th>Provinsi</th>
                        <th>Pendidikan</th>
                        <th>Asal</th>
                        <th>Jenis GTK</th>
                        <th>Status Kepegawaian</th>
                        <th>TMT PNS</th>
                        <th>Pangkat/Gol</th>
                        <th>TMT Pangkat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $i => $g)
                    <tr>
                        <td>{{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}</td>
                        <td>{{ $g->nama }}</td>
                        <td>{{ $g->nip ?? '-' }}</td>
                        <td>{{ $g->nik ?? '-' }}</td>
                        <td>{{ $g->nokarpeg ?? '-' }}</td>
                        <td>{{ $g->nuptk ?? '-' }}</td>
                        <td>{{ $g->tempat_lahir ?? '-' }}</td>
                        <td>{{ $g->tanggal_lahir ?? '-' }}</td>
                        <td>{{ $g->jenis_kelamin ? (stripos($g->jenis_kelamin,'laki')!==false?'L':'P') : '-' }}</td>
                        <td>{{ $g->agama ?? '-' }}</td>
                        <td>{{ $g->alamat ?? '-' }}</td>
                        <td>{{ $g->desa ?? '-' }}</td>
                        <td>{{ $g->kecamatan ?? '-' }}</td>
                        <td>{{ $g->kabupaten ?? '-' }}</td>
                        <td>{{ $g->provinsi ?? '-' }}</td>
                        <td>{{ $g->pendidikan_terakhir ?? '-' }}</td>
                        <td>{{ $g->daerah_asal ?? '-' }}</td>
                        <td><span class="bBlue">{{ $g->jenis_gtk ?? '-' }}</span></td>
                        <td>{{ $g->status_kepegawaian ?? '-' }}</td>
                        <td>{{ $g->tmt_pns ?? '-' }}</td>
                        <td>{{ $g->pangkat_gol_terakhir ?? '-' }}</td>
                        <td>{{ $g->tmt_pangkat_gol_terakhir ?? '-' }}</td>
                        <td style="text-align:center;">@if($this->isGtkComplete($g))<span class="bOk">✓</span>@else<span class="bErr">✕</span>@endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 px-2">
                <x-filament::pagination :paginator="$list" class="fi-pagination" />
            </div>
            @endif
        </div>
    @endif

    {{-- ======================== STEP 8 ======================== --}}
    @if($cur === 8)
        @php
            $list = $this->getGtkPendidikanList();
            $total = $this->getGtkCount();
            $tanpaPendidikan = $this->getGtkTanpaPendidikan();
        @endphp
        @if($total === 0)
            <div class="alert-err">
                <p style="font-size:.8rem;color:#b91c1c;margin:0;">Belum ada data Nominatif GTK. Harap tambahkan data GTK terlebih dahulu. <a href="{{ route('filament.'.filament()->getId().'.resources.gtk.index', ['tenant' => filament()->getTenant()]) }}" style="color:#b91c1c;font-weight:600;">Perbaiki Sekarang!</a></p>
            </div>
        @else
            <div class="stat">
                <div class="stat-num">{{ $total }}</div>
                <div><div class="stat-lbl">Riwayat Pendidikan GTK</div><div class="stat-sub">{{ $total - count($tanpaPendidikan) }} lengkap · {{ count($tanpaPendidikan) }} belum mengisi</div></div>
                @if(!empty($tanpaPendidikan))
                <div style="width:2px;background:#93c5fd;margin:0 10px;align-self:stretch;"></div>
                <div style="flex:1;">
                    <p style="font-size:.8rem;color:#dc2626;margin:0;font-weight:500;">Terdapat <strong>{{ count($tanpaPendidikan) }} GTK</strong> yang belum mengisi riwayat pendidikan terakhir. Harap lengkapi data tersebut. <a href="{{ route('filament.'.filament()->getId().'.resources.gtk.index', ['tenant' => filament()->getTenant()]) }}" style="color:#dc2626;font-weight:700;">Perbaiki Sekarang!</a></p>
                </div>
                @endif
            </div>
        @endif
        <div style="flex:1;overflow:auto;">
            @if($total > 0)
            <table class="tbl" style="min-width: 4000px;">
                <thead>
                    <tr>
                        <th width="40px" rowspan="2">No</th>
                        <th rowspan="2">Nama GTK</th>
                        <th colspan="1">SD</th>
                        <th colspan="1">SMP</th>
                        <th colspan="1">SMA</th>
                        <th colspan="3">D1</th>
                        <th colspan="3">D2</th>
                        <th colspan="3">D3</th>
                        <th colspan="3">S1</th>
                        <th colspan="3">S2</th>
                        <th colspan="3">S3</th>
                        <th colspan="3">Akta IV</th>
                        <th colspan="2">Gelar</th>
                        <th width="80px" rowspan="2" style="text-align:center;">Status</th>
                    </tr>
                    <tr>
                        <th>Thn Tamat</th> {{-- SD --}}
                        <th>Thn Tamat</th> {{-- SMP --}}
                        <th>Thn Tamat</th> {{-- SMA --}}
                        
                        <th>Thn Tamat</th><th>Jurusan</th><th>PT</th> {{-- D1 --}}
                        <th>Thn Tamat</th><th>Jurusan</th><th>PT</th> {{-- D2 --}}
                        <th>Thn Tamat</th><th>Jurusan</th><th>PT</th> {{-- D3 --}}
                        <th>Thn Tamat</th><th>Jurusan</th><th>PT</th> {{-- S1 --}}
                        <th>Thn Tamat</th><th>Jurusan</th><th>PT</th> {{-- S2 --}}
                        <th>Thn Tamat</th><th>Jurusan</th><th>PT</th> {{-- S3 --}}
                        <th>Thn Tamat</th><th>Jurusan</th><th>PT</th> {{-- Akta IV --}}
                        
                        <th>Depan</th><th>Belakang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $g)
                    @php $pend = $g->pendidikan->first(); @endphp
                    <tr>
                        <td>{{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}</td>
                        <td>{{ $g->nama }}</td>
                        
                        {{-- Dasar --}}
                        <td>{{ $pend->thn_tamat_sd ?? '-' }}</td>
                        <td>{{ $pend->thn_tamat_smp ?? '-' }}</td>
                        <td>{{ $pend->thn_tamat_sma ?? '-' }}</td>

                        {{-- D1 --}}
                        <td>{{ $pend->thn_tamat_d1 ?? '-' }}</td><td>{{ $pend->jurusan_d1 ?? '-' }}</td><td>{{ $pend->perguruan_tinggi_d1 ?? '-' }}</td>
                        {{-- D2 --}}
                        <td>{{ $pend->thn_tamat_d2 ?? '-' }}</td><td>{{ $pend->jurusan_d2 ?? '-' }}</td><td>{{ $pend->perguruan_tinggi_d2 ?? '-' }}</td>
                        {{-- D3 --}}
                        <td>{{ $pend->thn_tamat_d3 ?? '-' }}</td><td>{{ $pend->jurusan_d3 ?? '-' }}</td><td>{{ $pend->perguruan_tinggi_d3 ?? '-' }}</td>
                        {{-- S1 --}}
                        <td>{{ $pend->thn_tamat_s1 ?? '-' }}</td><td>{{ $pend->jurusan_s1 ?? '-' }}</td><td>{{ $pend->perguruan_tinggi_s1 ?? '-' }}</td>
                        {{-- S2 --}}
                        <td>{{ $pend->thn_tamat_s2 ?? '-' }}</td><td>{{ $pend->jurusan_s2 ?? '-' }}</td><td>{{ $pend->perguruan_tinggi_s2 ?? '-' }}</td>
                        {{-- S3 --}}
                        <td>{{ $pend->thn_tamat_s3 ?? '-' }}</td><td>{{ $pend->jurusan_s3 ?? '-' }}</td><td>{{ $pend->perguruan_tinggi_s3 ?? '-' }}</td>
                        {{-- Akta --}}
                        <td>{{ $pend->thn_tamat_akta4 ?? '-' }}</td><td>{{ $pend->jurusan_akta4 ?? '-' }}</td><td>{{ $pend->perguruan_tinggi_akta4 ?? '-' }}</td>
                        
                        {{-- Gelar --}}
                        <td>{{ $pend->gelar_depan ?? '-' }}</td><td>{{ $pend->gelar_belakang ?? '-' }}</td>

                        <td style="text-align:center;">@if(in_array($g->nama, $tanpaPendidikan))<span class="bErr">✕</span>@else<span class="bOk">✓</span>@endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 px-2">
                <x-filament::pagination :paginator="$list" class="fi-pagination" />
            </div>
            @endif
        </div>
    @endif

    {{-- ======================== STEP 9 ======================== --}}
    @if($cur === 9)
        @php
            $list = $this->getGtkKeuanganList();
            $total = $this->getGtkCount();
            $tanpaRekening = $this->getGtkTanpaRekening();
        @endphp
        @if($total === 0)
            <div class="alert-err">
                <p style="font-size:.8rem;color:#b91c1c;margin:0;">Belum ada data Nominatif GTK. Harap tambahkan data GTK terlebih dahulu. <a href="{{ route('filament.'.filament()->getId().'.resources.gtk.index', ['tenant' => filament()->getTenant()]) }}" style="color:#b91c1c;font-weight:600;">Perbaiki Sekarang!</a></p>
            </div>
        @else
            <div class="stat">
                <div class="stat-num">{{ $total }}</div>
                <div><div class="stat-lbl">Rekening & NPWP GTK</div><div class="stat-sub">{{ $total - count($tanpaRekening) }} lengkap · {{ count($tanpaRekening) }} belum mengisi</div></div>
                @if(!empty($tanpaRekening))
                <div style="width:2px;background:#93c5fd;margin:0 10px;align-self:stretch;"></div>
                <div style="flex:1;">
                    <p style="font-size:.8rem;color:#dc2626;margin:0;font-weight:500;">Terdapat <strong>{{ count($tanpaRekening) }} GTK</strong> yang belum mengisi data rekening atau NPWP. Harap lengkapi data tersebut. <a href="{{ route('filament.'.filament()->getId().'.resources.rekening-npwp-gtk.index', ['tenant' => filament()->getTenant()]) }}" style="color:#dc2626;font-weight:700;">Perbaiki Sekarang!</a></p>
                </div>
                @endif
            </div>
        @endif
        <div style="flex:1;overflow:auto;">
            @if($total > 0)
            <table class="tbl">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama GTK</th>
                        <th>Bank Gaji</th>
                        <th>No. Rek. Gaji</th>
                        <th>Bank Tunjangan</th>
                        <th>No. Rek. Tunjangan</th>
                        <th>NPWP</th>
                        <th width="8%" style="text-align:center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $g)
                    <tr>
                        <td>{{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}</td>
                        <td>{{ $g->nama }}</td>
                        <td>{{ $g->nama_bank_gaji ?? '-' }}</td>
                        <td>{{ $g->no_rek_gaji ?? '-' }}</td>
                        <td>{{ $g->nama_bank_tunjangan ?? '-' }}</td>
                        <td>{{ $g->no_rek_tunjangan ?? '-' }}</td>
                        <td>{{ $g->npwp ?? '-' }}</td>
                        <td style="text-align:center;">@if(in_array($g->nama, $tanpaRekening))<span class="bErr">✕</span>@else<span class="bOk">✓</span>@endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 px-2">
                <x-filament::pagination :paginator="$list" class="fi-pagination" />
            </div>
            @endif
        </div>
    @endif

    {{-- ======================== STEP 10 ======================== --}}
    @if($cur === 10)
        @php
            $list = $this->getSebaranList();
            $total = $this->getSebaranCount();
            $belowMinJam = $this->getGtkBelowMinJam();
        @endphp
        @if($total === 0)
            <div class="alert-err">
                <p style="font-size:.8rem;color:#b91c1c;margin:0;">Belum ada data Sebaran Jam Mengajar. Harap isi data jam mengajar terlebih dahulu. <a href="{{ route('filament.'.filament()->getId().'.resources.sebaran-jam-ajar.index', ['tenant' => filament()->getTenant()]) }}" style="color:#b91c1c;font-weight:600;">Perbaiki Sekarang!</a></p>
            </div>
        @else
            <div class="stat">
                <div class="stat-num">{{ $total }}</div>
                <div><div class="stat-lbl">Total GTK yang Mengajar</div><div class="stat-sub">Total: {{ $total }} GTK</div></div>
                @if(!empty($belowMinJam))
                <div style="width:2px;background:#93c5fd;margin:0 10px;align-self:stretch;"></div>
                <div style="flex:1;">
                    <p style="font-size:.8rem;color:#dc2626;margin:0;font-weight:500;">Terdapat <strong>{{ count($belowMinJam) }} guru</strong> dengan total jam mengajar kurang dari 24 jam. Silakan periksa kembali data sebaran jam mengajar. <a href="{{ route('filament.'.filament()->getId().'.resources.sebaran-jam-ajar.index', ['tenant' => filament()->getTenant()]) }}" style="color:#dc2626;font-weight:700;">Perbaiki Sekarang!</a></p>
                </div>
                @endif
            </div>
        @endif
        <div style="flex:1;overflow:auto;">
            @if($total > 0)
            <table class="tbl">
                <thead><tr><th width="5%">No</th><th>Nama GTK</th><th width="15%">Jenis GTK</th><th width="12%">Jml Entri</th><th width="12%">Total Jam</th><th width="12%">Status</th></tr></thead>
                <tbody>
                    @foreach($list as $i => $m)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $m->nama }}</td>
                        <td><span class="bBlue">{{ $m->jenis_gtk ?? '-' }}</span></td>
                        <td>{{ $m->jumlah_entri }}</td>
                        <td>{{ number_format($m->total_jam ?? 0, 0, ',', '.') }} jam</td>
                        <td>@if(($m->total_jam ?? 0) >= 24)<span class="bOk">✓</span>@else<span class="bErr">✕</span>@endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 px-2">
                <x-filament::pagination :paginator="$list" class="fi-pagination" />
            </div>
            @endif
        </div>
    @endif

    {{-- ======================== STEP 11 ======================== --}}
    @if($cur === 11)
        @php
            $list = $this->getKehadiranList();
            $total = $this->getKehadiranCount();
            $gtkTanpaKehadiran = $this->getGtkWithoutKehadiran();
        @endphp
        @if($total === 0)
            <div class="alert-err">
                <p style="font-size:.8rem;color:#b91c1c;margin:0;">Belum ada data Kehadiran GTK. Harap isi rekap kehadiran terlebih dahulu. <a href="{{ route('filament.'.filament()->getId().'.resources.kehadiran-gtk.index', ['tenant' => filament()->getTenant()]) }}" style="color:#b91c1c;font-weight:600;">Perbaiki Sekarang!</a></p>
            </div>
        @else
            @if($allValid)
                <div class="big-ok" style="margin-bottom:1rem;">
                    <span style="font-size:1.6rem;">🎉</span>
                    <div>
                        <div class="big-ok-title">Semua langkah valid! Siap untuk disimpan.</div>
                        <div class="big-ok-sub">Klik "Selesai & Simpan" untuk merekam validasi periode {{ $this->getCurrentPeriod() }}</div>
                    </div>
                </div>
            @endif
            <div class="stat">
                <div class="stat-num">{{ $total }}</div>
                <div><div class="stat-lbl">Rekap Kehadiran GTK</div><div class="stat-sub">{{ count($gtkTanpaKehadiran) > 0 ? count($gtkTanpaKehadiran).' GTK belum tercatat' : 'Semua GTK sudah tercatat' }}</div></div>
                @if(!empty($gtkTanpaKehadiran))
                <div style="width:2px;background:#93c5fd;margin:0 10px;align-self:stretch;"></div>
                <div style="flex:1;">
                    <p style="font-size:.8rem;color:#dc2626;margin:0;font-weight:500;">Terdapat <strong>{{ count($gtkTanpaKehadiran) }} GTK</strong> yang belum memiliki rekap kehadiran. Harap lengkapi data kehadiran terlebih dahulu. <a href="{{ route('filament.'.filament()->getId().'.resources.kehadiran-gtk.index', ['tenant' => filament()->getTenant()]) }}" style="color:#dc2626;font-weight:700;">Perbaiki Sekarang!</a></p>
                </div>
                @endif
            </div>
        @endif
        <div style="flex:1;overflow:auto;">
            @if($total > 0)
            <table class="tbl">
                <thead><tr><th width="5%">No</th><th>Nama GTK</th><th width="12%">Hari Kerja</th><th width="10%">Sakit</th><th width="10%">Izin</th><th width="10%">Alpa</th><th width="10%" style="text-align:center;">Status</th></tr></thead>
                <tbody>
                    @foreach($list as $i => $k)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $k->gtk->nama ?? '-' }}</td>
                        <td>{{ $k->hari_kerja ?? 0 }}</td>
                        <td>{{ $k->sakit ?? 0 }}</td>
                        <td>{{ $k->izin ?? 0 }}</td>
                        <td>{{ $k->alfa ?? 0 }}</td>
                        <td style="text-align:center;">
                            @if($this->isGtkAttendanceComplete($k->gtk))
                                <span class="bOk">✓</span>
                            @else
                                <span class="bErr">✕</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 px-2">
                <x-filament::pagination :paginator="$list" class="fi-pagination" />
            </div>
            @endif
        </div>
    @endif

    </div>{{-- end vd-cb --}}

    {{-- Footer actions --}}
    <div class="vd-footer">
        <button wire:click="prevStep" class="btn btn-back" {{ $cur <= 1 ? 'disabled' : '' }}>← Kembali</button>
        @if($cur < $this->totalSteps)
            <button wire:click="nextStep" class="btn btn-next" {{ !$isValid ? 'disabled' : '' }}
                title="{{ !$isValid ? 'Data belum lengkap' : 'Lanjut ke langkah berikutnya' }}">
                Selanjutnya →
            </button>
        @else
            <button wire:click="submitValidasi" class="btn btn-finish" {{ !$allValid ? 'disabled' : '' }}>
                ✓ Selesai &amp; Simpan Validasi
            </button>
        @endif
    </div>

</div>{{-- end vd-card --}}
</div>{{-- end vd --}}
</x-filament-panels::page>
