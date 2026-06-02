<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\Gtk;
use App\Models\GtkPendidikan;
use App\Models\LaporanGedung;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DynamicExport;
use Spatie\Browsershot\Browsershot;
use App\Filament\Traits\HasBrowsershot;
use Carbon\Carbon;

class CetakCustom extends Page implements HasForms
{
    use InteractsWithForms;
    use HasBrowsershot;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-plus';

    protected string $view = 'filament.pages.cetak-custom';

    protected static ?string $navigationLabel = 'Cetak Custom';

    protected static ?string $title = 'Cetak Laporan Custom';

    protected static ?int $navigationSort = 2;

    protected static string | \UnitEnum | null $navigationGroup = 'Cetak';

    public function getBreadcrumbs(): array
    {
        return [
            filament()->getUrl() => 'Dashboard',
            '#' => 'Cetak',
            '' => 'Cetak Laporan Custom',
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->check() && (auth()->user()->hasRole(['super_admin', 'admin_dinas', 'pengawas']));
    }

    public ?array $data = [];

    private function withAllOption(array|\Closure $optionsSource): array|\Closure
    {
        if (is_callable($optionsSource)) {
            return function () use ($optionsSource) {
                $options = $optionsSource();
                return ['all' => 'Pilih Semua (Semua)'] + $options;
            };
        }
        return ['all' => 'Pilih Semua (Semua)'] + $optionsSource;
    }

    private function handleFilterChange(string $filterField, string $targetField, string $columnKey, array|\Closure $optionsSource): \Closure
    {
        return function ($state, $get, $set) use ($filterField, $targetField, $columnKey, $optionsSource) {
            $state = $state ?? [];

            // Resolve options if it's a closure
            $options = is_callable($optionsSource) ? $optionsSource() : $optionsSource;

            // Check if 'all' was selected
            if (in_array('all', $state)) {
                // Get all other keys (excluding 'all')
                $allKeys = array_diff(array_keys($options), ['all']);
                $set($filterField, $allKeys);
                $state = $allKeys;
            }

            // Auto-select column in CheckboxList
            if (!empty($state)) {
                $current = $get($targetField) ?? [];
                if (!in_array($columnKey, $current)) {
                    $current[] = $columnKey;
                    $set($targetField, $current);
                }
            }
        };
    }

    public function mount(): void
    {
        $this->form->fill([
            'sekolah_columns' => ['nama', 'npsn', 'nss'],
            'siswa_columns' => ['nama', 'nisn'],
            'gtk_columns' => ['nama', 'nik', 'nip'],
            'sarpras_columns' => ['sekolah_nama', 'nama_ruang'],
        ]);
    }

    public function resetOtherTabFilters(string $activeTab)
    {
        $activeTab = strtoupper(trim($activeTab));

        if ($activeTab === 'LAPORAN SEKOLAH') {
            $this->form->fill([
                'sekolah_columns' => ['nama', 'npsn', 'nss'],
                'siswa_columns' => ['nama', 'nisn'],
                'gtk_columns' => ['nama', 'nik', 'nip'],
                'sarpras_columns' => ['sekolah_nama', 'nama_ruang'],
                'sekolah_jenjang' => [],
                'sekolah_status_sekolah' => [],
                'sekolah_tahun_berdiri' => [],
                'sekolah_status_tanah' => [],
                'sekolah_akreditasi' => [],
                'sekolah_kecamatan' => [],
            ]);
        } elseif ($activeTab === 'LAPORAN SISWA') {
            $this->form->fill([
                'sekolah_columns' => ['nama', 'npsn', 'nss'],
                'siswa_columns' => ['nama', 'nisn'],
                'gtk_columns' => ['nama', 'nik', 'nip'],
                'sarpras_columns' => ['sekolah_nama', 'nama_ruang'],
                'siswa_sekolah' => [],
                'siswa_jk' => [],
                'siswa_agama' => [],
                'siswa_umur' => [],
                'siswa_daerah_asal' => [],
                'siswa_distrik' => [],
                'siswa_desa' => [],
                'siswa_disabilitas' => [],
            ]);
        } elseif ($activeTab === 'LAPORAN GTK') {
            $this->form->fill([
                'sekolah_columns' => ['nama', 'npsn', 'nss'],
                'siswa_columns' => ['nama', 'nisn'],
                'gtk_columns' => ['nama', 'nik', 'nip'],
                'sarpras_columns' => ['sekolah_nama', 'nama_ruang'],
                'gtk_sekolah' => [],
                'gtk_jenis' => [],
                'gtk_jk' => [],
                'gtk_agama' => [],
                'gtk_daerah_asal' => [],
                'gtk_distrik' => [],
                'gtk_desa' => [],
                'gtk_status_kepegawaian' => [],
                'gtk_jurusan_d3' => [],
                'gtk_jurusan_s1' => [],
                'gtk_jurusan_s2' => [],
                'gtk_umur' => [],
            ]);
        } elseif ($activeTab === 'LAPORAN SARPRAS') {
            $this->form->fill([
                'sekolah_columns' => ['nama', 'npsn', 'nss'],
                'siswa_columns' => ['nama', 'nisn'],
                'gtk_columns' => ['nama', 'nik', 'nip'],
                'sarpras_columns' => ['sekolah_nama', 'nama_ruang'],
                'sarpras_sekolah' => [],
                'sarpras_status_kepemilikan' => [],
            ]);
        }
    }

    public static function getSekolahColumns(): array
    {
        return [
            'nama' => 'Nama Sekolah',
            'npsn' => 'NPSN',
            'nss' => 'NSS',
            'npwp' => 'NPWP',
            'jenjang' => 'Jenjang',
            'status_sekolah' => 'Status',
            'akreditasi' => 'Akreditasi',
            'status_tanah' => 'Status Tanah',
            'luas_tanah' => 'Luas Tanah (m²)',
            'tahun_berdiri' => 'Tahun Berdiri',
            'nomor_sk_pendirian' => 'No SK Pendirian',
            'tanggal_sk_pendirian' => 'Tgl SK Pendirian',
            'nama_yayasan' => 'Nama Yayasan',
            'alamat_yayasan' => 'Alamat Yayasan',
            'nomor_sk_yayasan' => 'No SK Yayasan',
            'tanggal_sk_yayasan' => 'Tgl SK Yayasan',
            'email' => 'Email',
            'alamat' => 'Alamat',
            'desa' => 'Desa/Kelurahan',
            'kecamatan' => 'Distrik (Kecamatan)',
            'kabupaten' => 'Kabupaten',
            'provinsi' => 'Provinsi',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'nomor_rekening_bop' => 'No Rekening BOP',
            'nama_bank_bop' => 'Nama Bank BOP',
            'nomor_rekening_bosp' => 'No Rekening BOSP',
            'nama_bank_bosp' => 'Nama Bank BOSP',
        ];
    }

    public static function getSiswaColumns(): array
    {
        return [
            'nama' => 'Nama Siswa',
            'sekolah_nama' => 'Nama Sekolah',
            'nisn' => 'NISN',
            'nik' => 'NIK',
            'nokk' => 'Nomor KK',
            'nobpjs' => 'Nomor BPJS',
            'jenis_kelamin' => 'JK',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'agama' => 'Agama',
            'daerah_asal' => 'Daerah Asal',
            'alamat' => 'Alamat Domisili',
            'desa' => 'Desa/Kelurahan',
            'kecamatan' => 'Distrik (Kecamatan)',
            'kabupaten' => 'Kabupaten',
            'provinsi' => 'Provinsi',
            'nama_ayah' => 'Nama Ayah',
            'nama_ibu' => 'Nama Ibu',
            'nama_wali' => 'Nama Wali',
            'nohp_ortuwali' => 'No. HP Orang Tua/Wali',
            'disabilitas' => 'Kondisi Disabilitas',
            'beasiswa' => 'Jenis Beasiswa',
            'status' => 'Status Siswa',
        ];
    }

    public static function getGtkColumns(): array
    {
        return [
            'nama' => 'Nama GTK',
            'sekolah_nama' => 'Nama Sekolah',
            'nik' => 'NIK',
            'nip' => 'NIP',
            'nuptk' => 'NUPTK',
            'nokarpeg' => 'No Karpeg',
            'jenis_gtk' => 'Jenis GTK',
            'jenis_kelamin' => 'JK',
            'status_kepegawaian' => 'Status Kepegawaian',
            'pangkat_gol_terakhir' => 'Pangkat/Gol',
            'tmt_pns' => 'TMT PNS',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'daerah_asal' => 'Daerah Asal',
            'agama' => 'Agama',
            'alamat' => 'Alamat',
            'desa' => 'Desa',
            'kecamatan' => 'Distrik (Kecamatan)',
            'kabupaten' => 'Kabupaten',
            'provinsi' => 'Provinsi',
            'tmt_pangkat_gol_terakhir' => 'TMT Pangkat Gol',
            'nama_bank_gaji' => 'Bank Gaji',
            'no_rek_gaji' => 'No Rekening Gaji',
            'nama_bank_tunjangan' => 'Bank Tunjangan',
            'no_rek_tunjangan' => 'No Rekening Tunjangan',
            'npwp' => 'NPWP',
            'jurusan_d3' => 'Jurusan D3',
            'jurusan_s1' => 'Jurusan S1',
            'jurusan_s2' => 'Jurusan S2',
        ];
    }

    public static function getSarprasColumns(): array
    {
        return [
            'sekolah_nama' => 'Nama Sekolah',
            'nama_ruang' => 'Nama Ruang / Gedung',
            'jumlah_total' => 'Jumlah Total',
            'jumlah_baik' => 'Jumlah Kondisi Baik',
            'jumlah_rusak' => 'Jumlah Kondisi Rusak',
            'status_kepemilikan' => 'Status Kepemilikan',
        ];
    }

    public function form($form)
    {
        return $form
            ->schema([
                Tabs::make('Laporan Custom')
                    ->tabs([
                        // TAB 1: LAPORAN SEKOLAH
                        Tab::make('LAPORAN SEKOLAH')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                Section::make('Filter Sekolah')
                                    ->description('Saring data sekolah berdasarkan kriteria berikut (semua filter bersifat multiple select)')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('sekolah_jenjang')
                                                    ->label('Jenjang Sekolah')
                                                    ->multiple()
                                                    ->options($this->withAllOption(['sma' => 'SMA', 'smk' => 'SMK']))
                                                    ->placeholder('Semua Jenjang')
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange('sekolah_jenjang', 'sekolah_columns', 'jenjang', ['sma' => 'SMA', 'smk' => 'SMK'])),

                                                Select::make('sekolah_status_sekolah')
                                                    ->label('Status Sekolah')
                                                    ->multiple()
                                                    ->options($this->withAllOption(['negeri' => 'Negeri', 'swasta' => 'Swasta']))
                                                    ->placeholder('Semua Status')
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange('sekolah_status_sekolah', 'sekolah_columns', 'status_sekolah', ['negeri' => 'Negeri', 'swasta' => 'Swasta'])),

                                                Select::make('sekolah_tahun_berdiri')
                                                    ->label('Tahun Berdiri')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => Sekolah::whereNotNull('tahun_berdiri')->distinct()->orderBy('tahun_berdiri', 'desc')->pluck('tahun_berdiri', 'tahun_berdiri')->toArray())
                                                    )
                                                    ->placeholder('Semua Tahun')
                                                    ->searchable()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'sekolah_tahun_berdiri',
                                                        'sekolah_columns',
                                                        'tahun_berdiri',
                                                        fn() => Sekolah::whereNotNull('tahun_berdiri')->distinct()->orderBy('tahun_berdiri', 'desc')->pluck('tahun_berdiri', 'tahun_berdiri')->toArray()
                                                    )),
                                            ]),

                                        Grid::make(3)
                                            ->schema([
                                                Select::make('sekolah_status_tanah')
                                                    ->label('Status Tanah')
                                                    ->multiple()
                                                    ->options($this->withAllOption(['shm' => 'Milik Sendiri (SHM)', 'hgb' => 'Hak Guna Bangunan (HGB)', 'ulayat' => 'Tanah Ulayat']))
                                                    ->placeholder('Semua Status Tanah')
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange('sekolah_status_tanah', 'sekolah_columns', 'status_tanah', ['shm' => 'Milik Sendiri (SHM)', 'hgb' => 'Hak Guna Bangunan (HGB)', 'ulayat' => 'Tanah Ulayat'])),

                                                Select::make('sekolah_akreditasi')
                                                    ->label('Akreditasi')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => Sekolah::whereNotNull('akreditasi')->where('akreditasi', '!=', '')->distinct()->orderBy('akreditasi')->pluck('akreditasi', 'akreditasi')->toArray())
                                                    )
                                                    ->placeholder('Semua Akreditasi')
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'sekolah_akreditasi',
                                                        'sekolah_columns',
                                                        'akreditasi',
                                                        fn() => Sekolah::whereNotNull('akreditasi')->where('akreditasi', '!=', '')->distinct()->orderBy('akreditasi')->pluck('akreditasi', 'akreditasi')->toArray()
                                                    )),

                                                Select::make('sekolah_kecamatan')
                                                    ->label('Distrik (Kecamatan)')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => Sekolah::whereNotNull('kecamatan')->where('kecamatan', '!=', '')->distinct()->orderBy('kecamatan')->pluck('kecamatan', 'kecamatan')->toArray())
                                                    )
                                                    ->placeholder('Semua Distrik')
                                                    ->searchable()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'sekolah_kecamatan',
                                                        'sekolah_columns',
                                                        'kecamatan',
                                                        fn() => Sekolah::whereNotNull('kecamatan')->where('kecamatan', '!=', '')->distinct()->orderBy('kecamatan')->pluck('kecamatan', 'kecamatan')->toArray()
                                                    )),
                                            ]),
                                    ]),

                                Section::make('Pilihan Kolom')
                                    ->description('Pilih kolom yang ingin ditampilkan dalam laporan')
                                    ->schema([
                                        CheckboxList::make('sekolah_columns')
                                            ->label('Kolom Laporan Sekolah')
                                            ->options(static::getSekolahColumns())
                                            ->default(['nama', 'npsn', 'nss'])
                                            ->bulkToggleable()
                                            ->columns([
                                                'default' => 2,
                                                'sm' => 3,
                                                'md' => 4,
                                                'lg' => 6,
                                            ])
                                            ->columnSpanFull(),
                                    ]),

                                Actions::make([
                                    Action::make('preview_sekolah')
                                        ->label('Preview')
                                        // ->icon('heroicon-o-eye')
                                        ->color('info')
                                        ->modalHeading('Preview Laporan Sekolah')
                                        ->modalWidth('7xl')
                                        ->modalContent(function () {
                                            $html = $this->getSekolahPreviewHtml();
                                            if (!$html) return new \Illuminate\Support\HtmlString('<div class="p-4 text-center text-gray-500">Tidak ada data yang sesuai filter.</div>');
                                            return new \Illuminate\Support\HtmlString('<div style="width: 100%; height: 70vh; border: 1px solid #d1d5db; border-radius: 0.375rem; overflow: hidden; background-color: #ffffff;"><iframe style="width: 100%; height: 100%; border: none;" srcdoc="' . htmlspecialchars($html . '<style>table{width:max-content!important;min-width:100%!important;white-space:nowrap!important;}</style>', ENT_QUOTES, 'UTF-8') . '"></iframe></div>');
                                        })
                                        ->modalSubmitAction(false)
                                        ->modalCancelActionLabel('Tutup'),
                                    Action::make('download_sekolah_excel')
                                        ->label('Unduh Excel')
                                        // ->icon('heroicon-o-arrow-down-tray')
                                        ->color('success')
                                        ->action('downloadSekolahExcel'),
                                    Action::make('download_sekolah_pdf')
                                        ->label('Unduh PDF')
                                        // ->icon('heroicon-o-document-text')
                                        ->color('danger')
                                        ->action('downloadSekolahPdf'),
                                ])->alignment('end'),
                            ]),

                        // TAB 2: LAPORAN SISWA
                        Tab::make('LAPORAN SISWA')
                            ->icon('heroicon-o-users')
                            ->schema([
                                Section::make('Filter Siswa')
                                    ->description('Saring data siswa berdasarkan kriteria berikut (semua filter bersifat multiple select)')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('siswa_sekolah')
                                                    ->label('Sekolah')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => Sekolah::orderBy('nama')->pluck('nama', 'id')->toArray())
                                                    )
                                                    ->placeholder('Semua Sekolah')
                                                    ->searchable()
                                                    ->preload()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'siswa_sekolah',
                                                        'siswa_columns',
                                                        'sekolah_nama',
                                                        fn() => Sekolah::orderBy('nama')->pluck('nama', 'id')->toArray()
                                                    )),

                                                Select::make('siswa_jk')
                                                    ->label('Jenis Kelamin')
                                                    ->multiple()
                                                    ->options($this->withAllOption(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan']))
                                                    ->placeholder('Semua Jenis Kelamin')
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange('siswa_jk', 'siswa_columns', 'jenis_kelamin', ['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan'])),

                                                Select::make('siswa_agama')
                                                    ->label('Agama')
                                                    ->multiple()
                                                    ->options($this->withAllOption(['Islam' => 'Islam', 'Kristen' => 'Kristen', 'Katolik' => 'Katolik', 'Hindu' => 'Hindu', 'Buddha' => 'Buddha', 'Konghucu' => 'Konghucu']))
                                                    ->placeholder('Semua Agama')
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange('siswa_agama', 'siswa_columns', 'agama', ['Islam' => 'Islam', 'Kristen' => 'Kristen', 'Katolik' => 'Katolik', 'Hindu' => 'Hindu', 'Buddha' => 'Buddha', 'Konghucu' => 'Konghucu'])),
                                            ]),

                                        Grid::make(3)
                                            ->schema([
                                                Select::make('siswa_umur')
                                                    ->label('Umur Siswa')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => Siswa::whereNotNull('tanggal_lahir')->pluck('tanggal_lahir')->map(fn($d) => Carbon::parse($d)->age)->unique()->sort()->values()->mapWithKeys(fn($a) => [$a => $a . ' Tahun'])->toArray())
                                                    )
                                                    ->placeholder('Semua Umur')
                                                    ->searchable()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'siswa_umur',
                                                        'siswa_columns',
                                                        'tanggal_lahir',
                                                        fn() => Siswa::whereNotNull('tanggal_lahir')->pluck('tanggal_lahir')->map(fn($d) => Carbon::parse($d)->age)->unique()->sort()->values()->mapWithKeys(fn($a) => [$a => $a . ' Tahun'])->toArray()
                                                    )),

                                                Select::make('siswa_daerah_asal')
                                                    ->label('Daerah Asal')
                                                    ->multiple()
                                                    ->options($this->withAllOption(['Papua' => 'Papua', 'Non Papua' => 'Non Papua']))
                                                    ->placeholder('Semua Daerah Asal')
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange('siswa_daerah_asal', 'siswa_columns', 'daerah_asal', ['Papua' => 'Papua', 'Non Papua' => 'Non Papua'])),

                                                Select::make('siswa_disabilitas')
                                                    ->label('Disabilitas')
                                                    ->multiple()
                                                    ->options($this->withAllOption(['tidak' => 'Non Disabilitas', 'tuna_netra' => 'Tuna Netra', 'tuna_rungu' => 'Tuna Rungu', 'tuna_wicara' => 'Tuna Wicara', 'tuna_daksa' => 'Tuna Daksa', 'tuna_grahita' => 'Tuna Grahita', 'tuna_lainnya' => 'Tuna Lainnya']))
                                                    ->placeholder('Semua Kondisi')
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange('siswa_disabilitas', 'siswa_columns', 'disabilitas', ['tidak' => 'Non Disabilitas', 'tuna_netra' => 'Tuna Netra', 'tuna_rungu' => 'Tuna Rungu', 'tuna_wicara' => 'Tuna Wicara', 'tuna_daksa' => 'Tuna Daksa', 'tuna_grahita' => 'Tuna Grahita', 'tuna_lainnya' => 'Tuna Lainnya'])),
                                            ]),

                                        Grid::make(2)
                                            ->schema([
                                                Select::make('siswa_distrik')
                                                    ->label('Alamat Domisili - Distrik (Kecamatan)')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => Siswa::whereNotNull('kecamatan')->where('kecamatan', '!=', '')->distinct()->orderBy('kecamatan')->pluck('kecamatan', 'kecamatan')->toArray())
                                                    )
                                                    ->placeholder('Semua Distrik')
                                                    ->searchable()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'siswa_distrik',
                                                        'siswa_columns',
                                                        'kecamatan',
                                                        fn() => Siswa::whereNotNull('kecamatan')->where('kecamatan', '!=', '')->distinct()->orderBy('kecamatan')->pluck('kecamatan', 'kecamatan')->toArray()
                                                    )),

                                                Select::make('siswa_desa')
                                                    ->label('Alamat Domisili - Desa / Kelurahan')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => Siswa::whereNotNull('desa')->where('desa', '!=', '')->distinct()->orderBy('desa')->pluck('desa', 'desa')->toArray())
                                                    )
                                                    ->placeholder('Semua Desa')
                                                    ->searchable()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'siswa_desa',
                                                        'siswa_columns',
                                                        'desa',
                                                        fn() => Siswa::whereNotNull('desa')->where('desa', '!=', '')->distinct()->orderBy('desa')->pluck('desa', 'desa')->toArray()
                                                    )),
                                            ]),
                                    ]),

                                Section::make('Pilihan Kolom')
                                    ->description('Pilih kolom yang ingin ditampilkan dalam laporan')
                                    ->schema([
                                        CheckboxList::make('siswa_columns')
                                            ->label('Kolom Laporan Siswa')
                                            ->options(static::getSiswaColumns())
                                            ->default(['nama', 'nisn'])
                                            ->bulkToggleable()
                                            ->columns([
                                                'default' => 2,
                                                'sm' => 3,
                                                'md' => 4,
                                                'lg' => 6,
                                            ])
                                            ->columnSpanFull(),
                                    ]),

                                Actions::make([
                                    Action::make('preview_siswa')
                                        ->label('Preview')
                                        // ->icon('heroicon-o-eye')
                                        ->color('info')
                                        ->modalHeading('Preview Laporan Siswa')
                                        ->modalWidth('7xl')
                                        ->modalContent(function () {
                                            $html = $this->getSiswaPreviewHtml();
                                            if (!$html) return new \Illuminate\Support\HtmlString('<div class="p-4 text-center text-gray-500">Tidak ada data yang sesuai filter.</div>');
                                            return new \Illuminate\Support\HtmlString('<div style="width: 100%; height: 70vh; border: 1px solid #d1d5db; border-radius: 0.375rem; overflow: hidden; background-color: #ffffff;"><iframe style="width: 100%; height: 100%; border: none;" srcdoc="' . htmlspecialchars($html . '<style>table{width:max-content!important;min-width:100%!important;white-space:nowrap!important;}</style>', ENT_QUOTES, 'UTF-8') . '"></iframe></div>');
                                        })
                                        ->modalSubmitAction(false)
                                        ->modalCancelActionLabel('Tutup'),
                                    Action::make('download_siswa_excel')
                                        ->label('Unduh Excel')
                                        // ->icon('heroicon-o-arrow-down-tray')
                                        ->color('success')
                                        ->action('downloadSiswaExcel'),
                                    Action::make('download_siswa_pdf')
                                        ->label('Unduh PDF')
                                        // ->icon('heroicon-o-document-text')
                                        ->color('danger')
                                        ->action('downloadSiswaPdf'),
                                ])->alignment('end'),
                            ]),

                        // TAB 3: LAPORAN GTK
                        Tab::make('LAPORAN GTK')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                Section::make('Filter GTK')
                                    ->description('Saring data GTK berdasarkan kriteria berikut (semua filter bersifat multiple select)')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('gtk_sekolah')
                                                    ->label('Sekolah')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => Sekolah::orderBy('nama')->pluck('nama', 'id')->toArray())
                                                    )
                                                    ->placeholder('Semua Sekolah')
                                                    ->searchable()
                                                    ->preload()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'gtk_sekolah',
                                                        'gtk_columns',
                                                        'sekolah_nama',
                                                        fn() => Sekolah::orderBy('nama')->pluck('nama', 'id')->toArray()
                                                    )),

                                                Select::make('gtk_jenis')
                                                    ->label('Jenis GTK')
                                                    ->multiple()
                                                    ->options($this->withAllOption(['Kepala Sekolah' => 'Kepala Sekolah', 'Guru' => 'Guru', 'Tenaga Administrasi' => 'Tenaga Administrasi']))
                                                    ->placeholder('Semua Jenis GTK')
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange('gtk_jenis', 'gtk_columns', 'jenis_gtk', ['Kepala Sekolah' => 'Kepala Sekolah', 'Guru' => 'Guru', 'Tenaga Administrasi' => 'Tenaga Administrasi'])),

                                                Select::make('gtk_jk')
                                                    ->label('Jenis Kelamin')
                                                    ->multiple()
                                                    ->options($this->withAllOption(['L' => 'Laki-laki', 'P' => 'Perempuan']))
                                                    ->placeholder('Semua Jenis Kelamin')
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange('gtk_jk', 'gtk_columns', 'jenis_kelamin', ['L' => 'Laki-laki', 'P' => 'Perempuan'])),
                                            ]),

                                        Grid::make(3)
                                            ->schema([
                                                Select::make('gtk_agama')
                                                    ->label('Agama')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => Gtk::whereNotNull('agama')->where('agama', '!=', '')->distinct()->orderBy('agama')->pluck('agama', 'agama')->toArray())
                                                    )
                                                    ->placeholder('Semua Agama')
                                                    ->searchable()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'gtk_agama',
                                                        'gtk_columns',
                                                        'agama',
                                                        fn() => Gtk::whereNotNull('agama')->where('agama', '!=', '')->distinct()->orderBy('agama')->pluck('agama', 'agama')->toArray()
                                                    )),

                                                Select::make('gtk_umur')
                                                    ->label('Umur GTK')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => Gtk::whereNotNull('tanggal_lahir')->pluck('tanggal_lahir')->map(fn($d) => Carbon::parse($d)->age)->unique()->sort()->values()->mapWithKeys(fn($a) => [$a => $a . ' Tahun'])->toArray())
                                                    )
                                                    ->placeholder('Semua Umur')
                                                    ->searchable()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'gtk_umur',
                                                        'gtk_columns',
                                                        'tanggal_lahir',
                                                        fn() => Gtk::whereNotNull('tanggal_lahir')->pluck('tanggal_lahir')->map(fn($d) => Carbon::parse($d)->age)->unique()->sort()->values()->mapWithKeys(fn($a) => [$a => $a . ' Tahun'])->toArray()
                                                    )),

                                                Select::make('gtk_daerah_asal')
                                                    ->label('Daerah Asal')
                                                    ->multiple()
                                                    ->options($this->withAllOption(['Papua' => 'Papua', 'Non Papua' => 'Non Papua']))
                                                    ->placeholder('Semua Daerah Asal')
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange('gtk_daerah_asal', 'gtk_columns', 'daerah_asal', ['Papua' => 'Papua', 'Non Papua' => 'Non Papua'])),
                                            ]),

                                        Grid::make(3)
                                            ->schema([
                                                Select::make('gtk_status_kepegawaian')
                                                    ->label('Status Kepegawaian')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => Gtk::whereNotNull('status_kepegawaian')->distinct()->pluck('status_kepegawaian', 'status_kepegawaian')->toArray())
                                                    )
                                                    ->placeholder('Semua Status')
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'gtk_status_kepegawaian',
                                                        'gtk_columns',
                                                        'status_kepegawaian',
                                                        fn() => Gtk::whereNotNull('status_kepegawaian')->distinct()->pluck('status_kepegawaian', 'status_kepegawaian')->toArray()
                                                    )),

                                                Select::make('gtk_distrik')
                                                    ->label('Alamat Domisili - Distrik (Kecamatan)')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => Gtk::whereNotNull('kecamatan')->where('kecamatan', '!=', '')->distinct()->orderBy('kecamatan')->pluck('kecamatan', 'kecamatan')->toArray())
                                                    )
                                                    ->placeholder('Semua Distrik')
                                                    ->searchable()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'gtk_distrik',
                                                        'gtk_columns',
                                                        'kecamatan',
                                                        fn() => Gtk::whereNotNull('kecamatan')->where('kecamatan', '!=', '')->distinct()->orderBy('kecamatan')->pluck('kecamatan', 'kecamatan')->toArray()
                                                    )),

                                                Select::make('gtk_desa')
                                                    ->label('Alamat Domisili - Desa / Kelurahan')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => Gtk::whereNotNull('desa')->where('desa', '!=', '')->distinct()->orderBy('desa')->pluck('desa', 'desa')->toArray())
                                                    )
                                                    ->placeholder('Semua Desa')
                                                    ->searchable()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'gtk_desa',
                                                        'gtk_columns',
                                                        'desa',
                                                        fn() => Gtk::whereNotNull('desa')->where('desa', '!=', '')->distinct()->orderBy('desa')->pluck('desa', 'desa')->toArray()
                                                    )),
                                            ]),

                                        Grid::make(3)
                                            ->schema([
                                                Select::make('gtk_jurusan_d3')
                                                    ->label('Jurusan D3')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => GtkPendidikan::whereNotNull('jurusan_d3')->where('jurusan_d3', '!=', '')->distinct()->orderBy('jurusan_d3')->pluck('jurusan_d3', 'jurusan_d3')->toArray())
                                                    )
                                                    ->placeholder('Semua Jurusan')
                                                    ->searchable()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'gtk_jurusan_d3',
                                                        'gtk_columns',
                                                        'jurusan_d3',
                                                        fn() => GtkPendidikan::whereNotNull('jurusan_d3')->where('jurusan_d3', '!=', '')->distinct()->orderBy('jurusan_d3')->pluck('jurusan_d3', 'jurusan_d3')->toArray()
                                                    )),

                                                Select::make('gtk_jurusan_s1')
                                                    ->label('Jurusan S1')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => GtkPendidikan::whereNotNull('jurusan_s1')->where('jurusan_s1', '!=', '')->distinct()->orderBy('jurusan_s1')->pluck('jurusan_s1', 'jurusan_s1')->toArray())
                                                    )
                                                    ->placeholder('Semua Jurusan')
                                                    ->searchable()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'gtk_jurusan_s1',
                                                        'gtk_columns',
                                                        'jurusan_s1',
                                                        fn() => GtkPendidikan::whereNotNull('jurusan_s1')->where('jurusan_s1', '!=', '')->distinct()->orderBy('jurusan_s1')->pluck('jurusan_s1', 'jurusan_s1')->toArray()
                                                    )),

                                                Select::make('gtk_jurusan_s2')
                                                    ->label('Jurusan S2')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => GtkPendidikan::whereNotNull('jurusan_s2')->where('jurusan_s2', '!=', '')->distinct()->orderBy('jurusan_s2')->pluck('jurusan_s2', 'jurusan_s2')->toArray())
                                                    )
                                                    ->placeholder('Semua Jurusan')
                                                    ->searchable()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'gtk_jurusan_s2',
                                                        'gtk_columns',
                                                        'jurusan_s2',
                                                        fn() => GtkPendidikan::whereNotNull('jurusan_s2')->where('jurusan_s2', '!=', '')->distinct()->orderBy('jurusan_s2')->pluck('jurusan_s2', 'jurusan_s2')->toArray()
                                                    )),
                                            ]),
                                    ]),

                                Section::make('Pilihan Kolom')
                                    ->description('Pilih kolom yang ingin ditampilkan dalam laporan')
                                    ->schema([
                                        CheckboxList::make('gtk_columns')
                                            ->label('Kolom Laporan GTK')
                                            ->options(static::getGtkColumns())
                                            ->default(['nama', 'nik', 'nip'])
                                            ->bulkToggleable()
                                            ->columns([
                                                'default' => 2,
                                                'sm' => 3,
                                                'md' => 4,
                                                'lg' => 6,
                                            ])
                                            ->columnSpanFull(),
                                    ]),

                                Actions::make([
                                    Action::make('preview_gtk')
                                        ->label('Preview')
                                        // ->icon('heroicon-o-eye')
                                        ->color('info')
                                        ->modalHeading('Preview Laporan GTK')
                                        ->modalWidth('7xl')
                                        ->modalContent(function () {
                                            $html = $this->getGtkPreviewHtml();
                                            if (!$html) return new \Illuminate\Support\HtmlString('<div class="p-4 text-center text-gray-500">Tidak ada data yang sesuai filter.</div>');
                                            return new \Illuminate\Support\HtmlString('<div style="width: 100%; height: 70vh; border: 1px solid #d1d5db; border-radius: 0.375rem; overflow: hidden; background-color: #ffffff;"><iframe style="width: 100%; height: 100%; border: none;" srcdoc="' . htmlspecialchars($html . '<style>table{width:max-content!important;min-width:100%!important;white-space:nowrap!important;}</style>', ENT_QUOTES, 'UTF-8') . '"></iframe></div>');
                                        })
                                        ->modalSubmitAction(false)
                                        ->modalCancelActionLabel('Tutup'),
                                    Action::make('download_gtk_excel')
                                        ->label('Unduh Excel')
                                        // ->icon('heroicon-o-arrow-down-tray')
                                        ->color('success')
                                        ->action('downloadGtkExcel'),
                                    Action::make('download_gtk_pdf')
                                        ->label('Unduh PDF')
                                        // ->icon('heroicon-o-document-text')
                                        ->color('danger')
                                        ->action('downloadGtkPdf'),
                                ])->alignment('end'),
                            ]),

                        // TAB 4: LAPORAN SARPRAS
                        Tab::make('LAPORAN SARPRAS')
                            ->icon('heroicon-o-home-modern')
                            ->schema([
                                Section::make('Filter Sarana & Prasarana')
                                    ->description('Saring data sarpras berdasarkan kriteria berikut (semua filter bersifat multiple select)')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('sarpras_sekolah')
                                                    ->label('Sekolah')
                                                    ->multiple()
                                                    ->options(
                                                        $this->withAllOption(fn() => Sekolah::orderBy('nama')->pluck('nama', 'id')->toArray())
                                                    )
                                                    ->placeholder('Semua Sekolah')
                                                    ->searchable()
                                                    ->preload()
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange(
                                                        'sarpras_sekolah',
                                                        'sarpras_columns',
                                                        'sekolah_nama',
                                                        fn() => Sekolah::orderBy('nama')->pluck('nama', 'id')->toArray()
                                                    )),

                                                Select::make('sarpras_status_kepemilikan')
                                                    ->label('Status Kepemilikan')
                                                    ->multiple()
                                                    ->options($this->withAllOption(['milik' => 'Milik Sendiri', 'pinjam' => 'Sewa/Pinjam']))
                                                    ->placeholder('Semua Status')
                                                    ->live()
                                                    ->afterStateUpdated($this->handleFilterChange('sarpras_status_kepemilikan', 'sarpras_columns', 'status_kepemilikan', ['milik' => 'Milik Sendiri', 'pinjam' => 'Sewa/Pinjam'])),
                                            ]),
                                    ]),

                                Section::make('Pilihan Kolom')
                                    ->description('Pilih kolom yang ingin ditampilkan dalam laporan')
                                    ->schema([
                                        CheckboxList::make('sarpras_columns')
                                            ->label('Kolom Laporan Sarpras')
                                            ->options(static::getSarprasColumns())
                                            ->default(['sekolah_nama', 'nama_ruang'])
                                            ->bulkToggleable()
                                            ->columns([
                                                'default' => 2,
                                                'sm' => 3,
                                                'md' => 4,
                                                'lg' => 6,
                                            ])
                                            ->columnSpanFull(),
                                    ]),

                                Actions::make([
                                    Action::make('preview_sarpras')
                                        ->label('Preview')
                                        // ->icon('heroicon-o-eye')
                                        ->color('info')
                                        ->modalHeading('Preview Laporan Sarpras')
                                        ->modalWidth('7xl')
                                        ->modalContent(function () {
                                            $html = $this->getSarprasPreviewHtml();
                                            if (!$html) return new \Illuminate\Support\HtmlString('<div class="p-4 text-center text-gray-500">Tidak ada data yang sesuai filter.</div>');
                                            return new \Illuminate\Support\HtmlString('<div style="width: 100%; height: 70vh; border: 1px solid #d1d5db; border-radius: 0.375rem; overflow: hidden; background-color: #ffffff;"><iframe style="width: 100%; height: 100%; border: none;" srcdoc="' . htmlspecialchars($html . '<style>table{width:max-content!important;min-width:100%!important;white-space:nowrap!important;}</style>', ENT_QUOTES, 'UTF-8') . '"></iframe></div>');
                                        })
                                        ->modalSubmitAction(false)
                                        ->modalCancelActionLabel('Tutup'),
                                    Action::make('download_sarpras_excel')
                                        ->label('Unduh Excel')
                                        // ->icon('heroicon-o-arrow-down-tray')
                                        ->color('success')
                                        ->action('downloadSarprasExcel'),
                                    Action::make('download_sarpras_pdf')
                                        ->label('Unduh PDF')
                                        // ->icon('heroicon-o-document-text')
                                        ->color('danger')
                                        ->action('downloadSarprasPdf'),
                                ])->alignment('end'),
                            ]),
                    ])
                    ->columnSpanFull()
            ])
            ->statePath('data');
    }

    // --- DOWNLOAD METHOD IMPLEMENTATIONS ---

    public function downloadSekolahExcel()
    {
        $state = $this->form->getState();
        $query = Sekolah::query();

        if (!empty($state['sekolah_jenjang'])) {
            $query->whereIn('jenjang', $state['sekolah_jenjang']);
        }
        if (!empty($state['sekolah_status_sekolah'])) {
            $query->whereIn('status_sekolah', $state['sekolah_status_sekolah']);
        }
        if (!empty($state['sekolah_tahun_berdiri'])) {
            $query->whereIn('tahun_berdiri', $state['sekolah_tahun_berdiri']);
        }
        if (!empty($state['sekolah_status_tanah'])) {
            $query->whereIn('status_tanah', $state['sekolah_status_tanah']);
        }
        if (!empty($state['sekolah_akreditasi'])) {
            $query->whereIn('akreditasi', $state['sekolah_akreditasi']);
        }
        if (!empty($state['sekolah_kecamatan'])) {
            $query->whereIn('kecamatan', $state['sekolah_kecamatan']);
        }

        $records = $query->orderBy('nama')->get();

        if ($records->isEmpty()) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body('Tidak ada data sekolah yang sesuai dengan kriteria filter.')
                ->danger()
                ->send();
            return;
        }

        $selectedColumns = array_intersect_key(static::getSekolahColumns(), array_flip($state['sekolah_columns'] ?? []));

        $filename = 'Laporan Bulanan Sekolah Sekabupaten Teluk Bintuni - ' . now()->translatedFormat('F Y');

        return Excel::download(
            new DynamicExport($records, $selectedColumns, null, 'LAPORAN BULANAN SEKOLAH SEKABUPATEN TELUK BINTUNI'),
            $filename . '.xlsx'
        );
    }

    public function getSekolahPreviewHtml(): ?string
    {
        $state = $this->form->getState();
        $query = Sekolah::query();

        if (!empty($state['sekolah_jenjang'])) {
            $query->whereIn('jenjang', $state['sekolah_jenjang']);
        }
        if (!empty($state['sekolah_status_sekolah'])) {
            $query->whereIn('status_sekolah', $state['sekolah_status_sekolah']);
        }
        if (!empty($state['sekolah_tahun_berdiri'])) {
            $query->whereIn('tahun_berdiri', $state['sekolah_tahun_berdiri']);
        }
        if (!empty($state['sekolah_status_tanah'])) {
            $query->whereIn('status_tanah', $state['sekolah_status_tanah']);
        }
        if (!empty($state['sekolah_akreditasi'])) {
            $query->whereIn('akreditasi', $state['sekolah_akreditasi']);
        }
        if (!empty($state['sekolah_kecamatan'])) {
            $query->whereIn('kecamatan', $state['sekolah_kecamatan']);
        }

        $records = $query->orderBy('nama')->get();

        if ($records->isEmpty()) {
            return null;
        }

        $selectedColumns = array_intersect_key(static::getSekolahColumns(), array_flip($state['sekolah_columns'] ?? []));
        $columnCount = count($selectedColumns);

        $fontSize = $this->calculateFontSize($columnCount);

        return view('pdf.dataset-custom', [
            'title' => 'LAPORAN BULANAN SEKOLAH SEKABUPATEN TELUK BINTUNI',
            'subTitle' => 'Periode ' . now()->translatedFormat('F Y'),
            'records' => $records,
            'columns' => $selectedColumns,
            'fontSize' => $fontSize,
            'sekolah' => null,
            'isPreview' => true,
        ])->render();
    }

    public function downloadSekolahPdf()
    {
        $html = $this->getSekolahPreviewHtml();

        if (!$html) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body('Tidak ada data sekolah yang sesuai dengan kriteria filter.')
                ->danger()
                ->send();
            return;
        }

        $state = $this->form->getState();
        $selectedColumns = array_intersect_key(static::getSekolahColumns(), array_flip($state['sekolah_columns'] ?? []));
        $columnCount = count($selectedColumns);

        $browsershot = $this->configureBrowsershot($html, $columnCount);

        $pdfContent = $browsershot->pdf();
        $filename = 'Laporan Bulanan Sekolah Sekabupaten Teluk Bintuni - ' . now()->translatedFormat('F Y');

        return response()->streamDownload(
            fn() => print($pdfContent),
            $filename . '.pdf'
        );
    }

    public function downloadSiswaExcel()
    {
        $state = $this->form->getState();
        $query = Siswa::query()->with('sekolah');

        if (!empty($state['siswa_sekolah'])) {
            $query->whereIn('sekolah_id', $state['siswa_sekolah']);
        }
        if (!empty($state['siswa_jk'])) {
            $query->whereIn('jenis_kelamin', $state['siswa_jk']);
        }
        if (!empty($state['siswa_agama'])) {
            $query->whereIn('agama', $state['siswa_agama']);
        }
        if (!empty($state['siswa_daerah_asal'])) {
            $query->whereIn('daerah_asal', $state['siswa_daerah_asal']);
        }
        if (!empty($state['siswa_distrik'])) {
            $query->whereIn('kecamatan', $state['siswa_distrik']);
        }
        if (!empty($state['siswa_desa'])) {
            $query->whereIn('desa', $state['siswa_desa']);
        }
        if (!empty($state['siswa_disabilitas'])) {
            $query->whereIn('disabilitas', $state['siswa_disabilitas']);
        }

        $records = $query->orderBy('nama')->get();

        // Post-filter age since age is calculated dynamically from tanggal_lahir
        if (!empty($state['siswa_umur'])) {
            $records = $records->filter(function ($item) use ($state) {
                if (empty($item->tanggal_lahir)) return false;
                $age = Carbon::parse($item->tanggal_lahir)->age;
                return in_array($age, $state['siswa_umur']);
            })->values();
        }

        if ($records->isEmpty()) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body('Tidak ada data siswa yang sesuai dengan kriteria filter.')
                ->danger()
                ->send();
            return;
        }

        // Map relation attributes
        $records->each(function ($item) {
            $item->sekolah_nama = $item->sekolah?->nama ?? '-';
        });

        $selectedColumns = array_intersect_key(static::getSiswaColumns(), array_flip($state['siswa_columns'] ?? []));

        $filename = 'Laporan Bulanan Sekolah Untuk Siswa - ' . now()->translatedFormat('F Y');

        return Excel::download(
            new DynamicExport($records, $selectedColumns, null, 'LAPORAN BULANAN SEKOLAH UNTUK SISWA'),
            $filename . '.xlsx'
        );
    }

    public function getSiswaPreviewHtml(): ?string
    {
        $state = $this->form->getState();
        $query = Siswa::query()->with('sekolah');

        if (!empty($state['siswa_sekolah'])) {
            $query->whereIn('sekolah_id', $state['siswa_sekolah']);
        }
        if (!empty($state['siswa_jk'])) {
            $query->whereIn('jenis_kelamin', $state['siswa_jk']);
        }
        if (!empty($state['siswa_agama'])) {
            $query->whereIn('agama', $state['siswa_agama']);
        }
        if (!empty($state['siswa_daerah_asal'])) {
            $query->whereIn('daerah_asal', $state['siswa_daerah_asal']);
        }
        if (!empty($state['siswa_distrik'])) {
            $query->whereIn('kecamatan', $state['siswa_distrik']);
        }
        if (!empty($state['siswa_desa'])) {
            $query->whereIn('desa', $state['siswa_desa']);
        }
        if (!empty($state['siswa_disabilitas'])) {
            $query->whereIn('disabilitas', $state['siswa_disabilitas']);
        }

        $records = $query->orderBy('nama')->get();

        // Post-filter age since age is calculated dynamically from tanggal_lahir
        if (!empty($state['siswa_umur'])) {
            $records = $records->filter(function ($item) use ($state) {
                if (empty($item->tanggal_lahir)) return false;
                $age = Carbon::parse($item->tanggal_lahir)->age;
                return in_array($age, $state['siswa_umur']);
            })->values();
        }

        if ($records->isEmpty()) {
            return null;
        }

        // Map relation attributes
        $records->each(function ($item) {
            $item->sekolah_nama = $item->sekolah?->nama ?? '-';
        });

        $selectedColumns = array_intersect_key(static::getSiswaColumns(), array_flip($state['siswa_columns'] ?? []));
        $columnCount = count($selectedColumns);

        $fontSize = $this->calculateFontSize($columnCount);

        $sekolah = null;
        if (!empty($state['siswa_sekolah']) && count($state['siswa_sekolah']) === 1) {
            $sekolah = Sekolah::find($state['siswa_sekolah'][0]);
        }

        return view('pdf.dataset-custom', [
            'title' => 'LAPORAN BULANAN SEKOLAH UNTUK SISWA',
            'subTitle' => 'Periode ' . now()->translatedFormat('F Y'),
            'records' => $records,
            'columns' => $selectedColumns,
            'fontSize' => $fontSize,
            'sekolah' => $sekolah,
        ])->render();
    }

    public function downloadSiswaPdf()
    {
        $html = $this->getSiswaPreviewHtml();

        if (!$html) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body('Tidak ada data yang sesuai dengan kriteria filter.')
                ->danger()
                ->send();
            return;
        }

        $state = $this->form->getState();
        $selectedColumns = array_intersect_key(static::getSiswaColumns(), array_flip($state['siswa_columns'] ?? []));
        $columnCount = count($selectedColumns);


        $browsershot = $this->configureBrowsershot($html, $columnCount);

        $pdfContent = $browsershot->pdf();
        $filename = 'Laporan Bulanan Sekolah Untuk Siswa - ' . now()->translatedFormat('F Y');

        return response()->streamDownload(
            fn() => print($pdfContent),
            $filename . '.pdf'
        );
    }

    public function downloadGtkExcel()
    {
        $state = $this->form->getState();
        $query = Gtk::query()->with(['sekolah', 'pendidikan']);

        if (!empty($state['gtk_sekolah'])) {
            $query->whereIn('sekolah_id', $state['gtk_sekolah']);
        }
        if (!empty($state['gtk_jenis'])) {
            $query->whereIn('jenis_gtk', $state['gtk_jenis']);
        }
        if (!empty($state['gtk_jk'])) {
            $query->whereIn('jenis_kelamin', $state['gtk_jk']);
        }
        if (!empty($state['gtk_agama'])) {
            $query->whereIn('agama', $state['gtk_agama']);
        }
        if (!empty($state['gtk_daerah_asal'])) {
            $query->whereIn('daerah_asal', $state['gtk_daerah_asal']);
        }
        if (!empty($state['gtk_distrik'])) {
            $query->whereIn('kecamatan', $state['gtk_distrik']);
        }
        if (!empty($state['gtk_desa'])) {
            $query->whereIn('desa', $state['gtk_desa']);
        }
        if (!empty($state['gtk_status_kepegawaian'])) {
            $query->whereIn('status_kepegawaian', $state['gtk_status_kepegawaian']);
        }

        // Jurusan D3, S1, S2 (GTK Pendidikan relations)
        if (!empty($state['gtk_jurusan_d3']) || !empty($state['gtk_jurusan_s1']) || !empty($state['gtk_jurusan_s2'])) {
            $query->whereHas('pendidikan', function ($q) use ($state) {
                if (!empty($state['gtk_jurusan_d3'])) {
                    $q->whereIn('jurusan_d3', $state['gtk_jurusan_d3']);
                }
                if (!empty($state['gtk_jurusan_s1'])) {
                    $q->whereIn('jurusan_s1', $state['gtk_jurusan_s1']);
                }
                if (!empty($state['gtk_jurusan_s2'])) {
                    $q->whereIn('jurusan_s2', $state['gtk_jurusan_s2']);
                }
            });
        }

        $records = $query->orderBy('nama')->get();

        // Post-filter age
        if (!empty($state['gtk_umur'])) {
            $records = $records->filter(function ($item) use ($state) {
                if (empty($item->tanggal_lahir)) return false;
                $age = Carbon::parse($item->tanggal_lahir)->age;
                return in_array($age, $state['gtk_umur']);
            })->values();
        }

        if ($records->isEmpty()) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body('Tidak ada data GTK yang sesuai dengan kriteria filter.')
                ->danger()
                ->send();
            return;
        }

        // Map relation attributes
        $records->each(function ($item) {
            $item->sekolah_nama = $item->sekolah?->nama ?? '-';
            $pend = $item->pendidikan->first();
            $item->nama = self::formatGtkName($item);
            $item->jurusan_d3 = $pend?->jurusan_d3 ?? '-';
            $item->jurusan_s1 = $pend?->jurusan_s1 ?? '-';
            $item->jurusan_s2 = $pend?->jurusan_s2 ?? '-';
        });

        $selectedColumns = array_intersect_key(static::getGtkColumns(), array_flip($state['gtk_columns'] ?? []));

        $filename = 'Laporan Bulanan Sekolah Untuk GTK - ' . now()->translatedFormat('F Y');

        return Excel::download(
            new DynamicExport($records, $selectedColumns, null, 'LAPORAN BULANAN SEKOLAH UNTUK GTK'),
            $filename . '.xlsx'
        );
    }

    public function getGtkPreviewHtml(): ?string
    {
        $state = $this->form->getState();
        $query = Gtk::query()->with(['sekolah', 'pendidikan']);

        if (!empty($state['gtk_sekolah'])) {
            $query->whereIn('sekolah_id', $state['gtk_sekolah']);
        }
        if (!empty($state['gtk_jenis'])) {
            $query->whereIn('jenis_gtk', $state['gtk_jenis']);
        }
        if (!empty($state['gtk_jk'])) {
            $query->whereIn('jenis_kelamin', $state['gtk_jk']);
        }
        if (!empty($state['gtk_agama'])) {
            $query->whereIn('agama', $state['gtk_agama']);
        }
        if (!empty($state['gtk_daerah_asal'])) {
            $query->whereIn('daerah_asal', $state['gtk_daerah_asal']);
        }
        if (!empty($state['gtk_distrik'])) {
            $query->whereIn('kecamatan', $state['gtk_distrik']);
        }
        if (!empty($state['gtk_desa'])) {
            $query->whereIn('desa', $state['gtk_desa']);
        }
        if (!empty($state['gtk_status_kepegawaian'])) {
            $query->whereIn('status_kepegawaian', $state['gtk_status_kepegawaian']);
        }

        // Jurusan D3, S1, S2 (GTK Pendidikan relations)
        if (!empty($state['gtk_jurusan_d3']) || !empty($state['gtk_jurusan_s1']) || !empty($state['gtk_jurusan_s2'])) {
            $query->whereHas('pendidikan', function ($q) use ($state) {
                if (!empty($state['gtk_jurusan_d3'])) {
                    $q->whereIn('jurusan_d3', $state['gtk_jurusan_d3']);
                }
                if (!empty($state['gtk_jurusan_s1'])) {
                    $q->whereIn('jurusan_s1', $state['gtk_jurusan_s1']);
                }
                if (!empty($state['gtk_jurusan_s2'])) {
                    $q->whereIn('jurusan_s2', $state['gtk_jurusan_s2']);
                }
            });
        }

        $records = $query->orderBy('nama')->get();

        // Post-filter age
        if (!empty($state['gtk_umur'])) {
            $records = $records->filter(function ($item) use ($state) {
                if (empty($item->tanggal_lahir)) return false;
                $age = Carbon::parse($item->tanggal_lahir)->age;
                return in_array($age, $state['gtk_umur']);
            })->values();
        }

        if ($records->isEmpty()) {
            return null;
        }

        // Map relation attributes
        $records->each(function ($item) {
            $item->sekolah_nama = $item->sekolah?->nama ?? '-';
            $pend = $item->pendidikan->first();
            $item->nama = self::formatGtkName($item);
            $item->jurusan_d3 = $pend?->jurusan_d3 ?? '-';
            $item->jurusan_s1 = $pend?->jurusan_s1 ?? '-';
            $item->jurusan_s2 = $pend?->jurusan_s2 ?? '-';
        });

        $selectedColumns = array_intersect_key(static::getGtkColumns(), array_flip($state['gtk_columns'] ?? []));
        $columnCount = count($selectedColumns);

        $fontSize = $this->calculateFontSize($columnCount);

        $sekolah = null;
        if (!empty($state['gtk_sekolah']) && count($state['gtk_sekolah']) === 1) {
            $sekolah = Sekolah::find($state['gtk_sekolah'][0]);
        }

        return view('pdf.dataset-custom', [
            'title' => 'LAPORAN BULANAN SEKOLAH UNTUK GTK',
            'subTitle' => 'Periode ' . now()->translatedFormat('F Y'),
            'records' => $records,
            'columns' => $selectedColumns,
            'fontSize' => $fontSize,
            'sekolah' => $sekolah,
        ])->render();
    }

    public function downloadGtkPdf()
    {
        $html = $this->getGtkPreviewHtml();

        if (!$html) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body('Tidak ada data yang sesuai dengan kriteria filter.')
                ->danger()
                ->send();
            return;
        }

        $state = $this->form->getState();
        $selectedColumns = array_intersect_key(static::getGtkColumns(), array_flip($state['gtk_columns'] ?? []));
        $columnCount = count($selectedColumns);


        $browsershot = $this->configureBrowsershot($html, $columnCount);

        $pdfContent = $browsershot->pdf();
        $filename = 'Laporan Bulanan Sekolah Untuk GTK - ' . now()->translatedFormat('F Y');

        return response()->streamDownload(
            fn() => print($pdfContent),
            $filename . '.pdf'
        );
    }

    public function downloadSarprasExcel()
    {
        $state = $this->form->getState();
        $query = LaporanGedung::query()->with('laporan.sekolah');

        if (!empty($state['sarpras_sekolah'])) {
            $query->whereHas('laporan', function ($q) use ($state) {
                $q->whereIn('sekolah_id', $state['sarpras_sekolah']);
            });
        }
        if (!empty($state['sarpras_status_kepemilikan'])) {
            $query->whereIn('status_kepemilikan', $state['sarpras_status_kepemilikan']);
        }

        $records = $query->get();

        if ($records->isEmpty()) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body('Tidak ada data sarpras yang sesuai dengan kriteria filter.')
                ->danger()
                ->send();
            return;
        }

        // Map relation attributes
        $records->each(function ($item) {
            $item->sekolah_nama = $item->laporan?->sekolah?->nama ?? '-';
            if ($item->status_kepemilikan === 'milik') {
                $item->status_kepemilikan = 'Milik Sendiri';
            } elseif ($item->status_kepemilikan === 'pinjam') {
                $item->status_kepemilikan = 'Sewa/Pinjam';
            }
        });

        $selectedColumns = array_intersect_key(static::getSarprasColumns(), array_flip($state['sarpras_columns'] ?? []));

        $filename = 'Laporan Bulanan Sekolah Untuk Sarpras - ' . now()->translatedFormat('F Y');

        return Excel::download(
            new DynamicExport($records, $selectedColumns, null, 'LAPORAN BULANAN SEKOLAH UNTUK SARPRAS'),
            $filename . '.xlsx'
        );
    }

    public function getSarprasPreviewHtml(): ?string
    {
        $state = $this->form->getState();
        $query = LaporanGedung::query()->with('laporan.sekolah');

        if (!empty($state['sarpras_sekolah'])) {
            $query->whereHas('laporan', function ($q) use ($state) {
                $q->whereIn('sekolah_id', $state['sarpras_sekolah']);
            });
        }
        if (!empty($state['sarpras_status_kepemilikan'])) {
            $query->whereIn('status_kepemilikan', $state['sarpras_status_kepemilikan']);
        }

        $records = $query->get();

        if ($records->isEmpty()) {
            return null;
        }

        // Map relation attributes
        $records->each(function ($item) {
            $item->sekolah_nama = $item->laporan?->sekolah?->nama ?? '-';
            if ($item->status_kepemilikan === 'milik') {
                $item->status_kepemilikan = 'Milik Sendiri';
            } elseif ($item->status_kepemilikan === 'pinjam') {
                $item->status_kepemilikan = 'Sewa/Pinjam';
            }
        });

        $selectedColumns = array_intersect_key(static::getSarprasColumns(), array_flip($state['sarpras_columns'] ?? []));
        $columnCount = count($selectedColumns);

        $fontSize = $this->calculateFontSize($columnCount);

        $sekolah = null;
        if (!empty($state['sarpras_sekolah']) && count($state['sarpras_sekolah']) === 1) {
            $sekolah = Sekolah::find($state['sarpras_sekolah'][0]);
        }

        return view('pdf.dataset-custom', [
            'title' => 'LAPORAN BULANAN SEKOLAH UNTUK SARPRAS',
            'subTitle' => 'Periode ' . now()->translatedFormat('F Y'),
            'records' => $records,
            'columns' => $selectedColumns,
            'fontSize' => $fontSize,
            'sekolah' => $sekolah,
        ])->render();
    }

    public function downloadSarprasPdf()
    {
        $html = $this->getSarprasPreviewHtml();

        if (!$html) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body('Tidak ada data yang sesuai dengan kriteria filter.')
                ->danger()
                ->send();
            return;
        }

        $state = $this->form->getState();
        $selectedColumns = array_intersect_key(static::getSarprasColumns(), array_flip($state['sarpras_columns'] ?? []));
        $columnCount = count($selectedColumns);


        $browsershot = $this->configureBrowsershot($html, $columnCount);

        $pdfContent = $browsershot->pdf();
        $filename = 'Laporan Bulanan Sekolah Untuk Sarpras - ' . now()->translatedFormat('F Y');

        return response()->streamDownload(
            fn() => print($pdfContent),
            $filename . '.pdf'
        );
    }

    protected function calculateFontSize(int $columnCount): string
    {
        if ($columnCount <= 5) return '9.5pt';
        if ($columnCount <= 8) return '8pt';
        if ($columnCount <= 12) return '7pt';
        if ($columnCount <= 16) return '6pt';
        return '5pt';
    }

    protected static function formatGtkName($gtk): string
    {
        $nama = trim((string) ($gtk?->nama ?? ''));
        $pendidikan = $gtk?->pendidikan->first();
        $gelarDepan = trim((string) ($pendidikan?->gelar_depan ?? ''));
        $gelarBelakang = trim((string) ($pendidikan?->gelar_belakang ?? ''));

        return trim(($gelarDepan ? $gelarDepan . ' ' : '') . $nama . ($gelarBelakang ? ', ' . $gelarBelakang : ''));
    }

    protected function configureBrowsershot(string $html, int $columnCount): Browsershot
    {
        $browsershot = $this->makeBrowsershot($html)
            ->waitUntilNetworkIdle();

        if ($columnCount > 7) {
            $browsershot->landscape();
        }

        return $browsershot;
    }
}
