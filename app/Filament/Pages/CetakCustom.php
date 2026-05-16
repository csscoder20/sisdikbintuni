<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\Gtk;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class CetakCustom extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-plus';

    protected string $view = 'filament.pages.cetak-custom';

    protected static ?string $navigationLabel = 'Cetak Custom';

    protected static ?string $title = 'CETAK LAPORAN CUSTOM';

    protected static ?int $navigationSort = 2;

    protected static string | \UnitEnum | null $navigationGroup = 'Cetak';

    public static function canAccess(): bool
    {
        return auth()->check() && (auth()->user()->hasRole(['super_admin', 'admin_dinas']));
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'sekolah_ids' => session('dinas_selected_sekolah_id') ? [session('dinas_selected_sekolah_id')] : [],
            'komponen' => ['profil', 'siswa', 'gtk'],
            'bulan' => (int) date('m'),
            'tahun' => (int) date('Y'),
            'format' => 'pdf',
        ]);
    }

    public function form($form)
    {
        return $form
            ->schema([
                Tabs::make('Laporan Custom')
                    ->tabs([
                        Tab::make('Konfigurasi Umum')
                            ->icon('heroicon-o-cog')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('bulan')
                                            ->label('Bulan Laporan')
                                            ->options([
                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                            ])
                                            ->required(),

                                        Select::make('tahun')
                                            ->label('Tahun Laporan')
                                            ->options(array_combine(range(date('Y'), 2022), range(date('Y'), 2022)))
                                            ->required(),
                                    ]),
                                
                                CheckboxList::make('komponen')
                                    ->label('Pilih Komponen Data')
                                    ->options([
                                        'profil' => 'Profil Identitas Sekolah',
                                        'siswa' => 'Rekapitulasi Data Siswa',
                                        'gtk' => 'Daftar Guru & Tenaga Kependidikan',
                                        'sarpras' => 'Kondisi Sarana & Prasarana',
                                        'keuangan' => 'Ringkasan Laporan Keuangan',
                                    ])
                                    ->columns(2)
                                    ->reactive()
                                    ->required(),
                                
                                Select::make('format')
                                    ->label('Format Output Dokumen')
                                    ->options([
                                        'pdf' => 'Portable Document Format (PDF)',
                                        'excel' => 'Microsoft Excel (XLSX)',
                                    ])
                                    ->required(),
                            ]),

                        Tab::make('Filter Sekolah')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('jenjang_filter')
                                            ->label('Filter Jenjang')
                                            ->options([
                                                'sma' => 'SMA',
                                                'smk' => 'SMK',
                                            ])
                                            ->placeholder('Semua Jenjang')
                                            ->reactive(),
                                        
                                        Select::make('kecamatan_filter')
                                            ->label('Filter Kecamatan')
                                            ->options(Sekolah::whereNotNull('kecamatan')->where('kecamatan', '!=', '')->distinct()->orderBy('kecamatan')->pluck('kecamatan', 'kecamatan'))
                                            ->placeholder('Semua Kecamatan')
                                            ->reactive(),
                                    ]),

                                Select::make('sekolah_ids')
                                    ->label('Pilih Sekolah Target')
                                    ->multiple()
                                    ->options(function (Get $get) {
                                        $query = Sekolah::orderBy('nama');
                                        if ($jenjang = $get('jenjang_filter')) {
                                            $query->where('jenjang', $jenjang);
                                        }
                                        if ($kecamatan = $get('kecamatan_filter')) {
                                            $query->where('kecamatan', $kecamatan);
                                        }
                                        return $query->pluck('nama', 'id');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->hint('Anda dapat memilih lebih dari satu sekolah.')
                                    ->columnSpanFull(),
                                
                                Toggle::make('gabungkan_laporan')
                                    ->label('Gabungkan menjadi satu file laporan')
                                    ->default(true)
                                    ->visible(fn (Get $get) => count($get('sekolah_ids') ?? []) > 1),
                            ]),

                        Tab::make('Filter Siswa')
                            ->icon('heroicon-o-users')
                            ->visible(fn (Get $get) => in_array('siswa', $get('komponen') ?? []))
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        Select::make('siswa_jk')
                                            ->label('Jenis Kelamin')
                                            ->options([
                                                'L' => 'Laki-laki',
                                                'P' => 'Perempuan',
                                            ])
                                            ->placeholder('Semua'),
                                        
                                        Select::make('siswa_agama')
                                            ->label('Agama')
                                            ->options([
                                                'Islam' => 'Islam',
                                                'Kristen' => 'Kristen',
                                                'Katolik' => 'Katolik',
                                                'Hindu' => 'Hindu',
                                                'Budha' => 'Budha',
                                                'Khonghucu' => 'Khonghucu',
                                            ])
                                            ->placeholder('Semua'),

                                        Select::make('siswa_daerah_asal')
                                            ->label('Daerah Asal')
                                            ->options([
                                                'Papua' => 'Papua',
                                                'Non Papua' => 'Non Papua',
                                            ])
                                            ->placeholder('Semua'),
                                    ]),
                                
                                Grid::make(2)
                                    ->schema([
                                        Select::make('siswa_status')
                                            ->label('Status Siswa')
                                            ->options([
                                                'Aktif' => 'Aktif',
                                                'Lulus' => 'Lulus',
                                                'Pindah' => 'Pindah',
                                                'Keluar' => 'Keluar/DO',
                                            ])
                                            ->default('Aktif'),
                                        
                                        CheckboxList::make('siswa_tambahan')
                                            ->label('Kriteria Tambahan')
                                            ->options([
                                                'disabilitas' => 'Siswa Disabilitas',
                                                'beasiswa' => 'Penerima Beasiswa',
                                            ])
                                            ->columns(2),
                                    ])
                            ]),

                        Tab::make('Filter GTK')
                            ->icon('heroicon-o-user-group')
                            ->visible(fn (Get $get) => in_array('gtk', $get('komponen') ?? []))
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('gtk_status_kepegawaian')
                                            ->label('Status Kepegawaian')
                                            ->options([
                                                'PNS' => 'PNS',
                                                'CPNS' => 'CPNS',
                                                'PPPK' => 'PPPK',
                                                'Honorer Sekolah' => 'Honorer Sekolah',
                                                'GTY/PTY' => 'GTY/PTY',
                                            ])
                                            ->multiple()
                                            ->placeholder('Semua Status'),
                                        
                                        Select::make('gtk_jenis')
                                            ->label('Jenis GTK')
                                            ->options([
                                                'Guru' => 'Guru',
                                                'Tenaga Administrasi' => 'Tenaga Administrasi',
                                                'Kepala Sekolah' => 'Kepala Sekolah',
                                            ])
                                            ->multiple()
                                            ->placeholder('Semua Jenis'),
                                    ]),
                                
                                Grid::make(3)
                                    ->schema([
                                        Select::make('gtk_jk')
                                            ->label('Jenis Kelamin')
                                            ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                                            ->placeholder('Semua'),
                                        
                                        Select::make('gtk_pendidikan')
                                            ->label('Pendidikan Terakhir')
                                            ->options([
                                                'SMA' => 'SMA/Sederajat',
                                                'D3' => 'D3',
                                                'S1' => 'S1/D4',
                                                'S2' => 'S2',
                                                'S3' => 'S3',
                                            ])
                                            ->placeholder('Semua'),
                                        
                                        Select::make('gtk_daerah_asal')
                                            ->label('Daerah Asal')
                                            ->options(['Papua' => 'Papua', 'Non Papua' => 'Non Papua'])
                                            ->placeholder('Semua'),
                                    ])
                            ]),
                    ])
                    ->columnSpanFull()
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate')
                ->label('Hasilkan Dokumen')
                ->icon('heroicon-o-document-arrow-down')
                ->color('primary')
                ->action('submit'),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $sekolahCount = count($data['sekolah_ids'] ?? []);
        $komponenList = implode(', ', array_map('ucfirst', $data['komponen'] ?? []));
        $formatLabel = strtoupper($data['format']);

        Notification::make()
            ->title('Laporan Custom Sedang Diproses')
            ->body("Memproses data untuk {$sekolahCount} sekolah dengan komponen: [{$komponenList}]. Dokumen dalam format {$formatLabel} akan segera siap.")
            ->icon('heroicon-o-document-check')
            ->color('success')
            ->send();
    }
}
