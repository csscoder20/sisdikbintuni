<?php

namespace App\Filament\Pages;

use App\Models\Laporan;
use App\Models\Sekolah;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class Revalidasi extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Revalidasi';
    protected static ?string $title = 'Revalidasi Data';
    protected static ?int $navigationSort = 11;
    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPath;
    protected string $view = 'filament.pages.revalidasi';

    public ?int $schoolId = null;
    public ?int $laporanId = null;
    public array $sections = [];
    public array $data = [];

    public static function canAccess(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'dinas'
            && auth()->check()
            && auth()->user()->hasRole('super_admin');
    }

    public function mount(): void
    {
        $this->schoolId = session('dinas_selected_sekolah_id');
        $this->form->fill(['school_id' => $this->schoolId]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Grid::make(2)
                    ->schema([
                Select::make('school_id')
                    ->label('Sekolah')
                    ->placeholder('Pilih sekolah')
                    ->options(fn (): array => collect($this->getSchools())
                        ->mapWithKeys(fn (Sekolah $school): array => [
                            $school->id => "{$school->nama} ({$school->npsn})",
                        ])
                        ->all())
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set): void {
                        $this->schoolId = $state ? (int) $state : null;
                        $this->laporanId = null;
                        $set('laporan_id', null);
                    }),
                Select::make('laporan_id')
                    ->label('Periode Laporan')
                    ->placeholder('Pilih periode laporan')
                    ->options(fn (): array => collect($this->getPeriods())
                        ->mapWithKeys(fn (Laporan $laporan): array => [
                            $laporan->id => \Carbon\Carbon::createFromDate($laporan->tahun, $laporan->bulan, 1)->translatedFormat('F Y')
                                . ' - ' . strtoupper($laporan->status ?? 'draft'),
                        ])
                        ->all())
                    ->searchable()
                    ->preload()
                    ->live()
                    ->disabled(fn (): bool => !$this->schoolId)
                    ->afterStateUpdated(function ($state): void {
                        $this->laporanId = $state ? (int) $state : null;
                    }),
                    ]),
            ]);
    }

    public function updatedSchoolId(): void
    {
        $this->laporanId = null;
    }

    public function getSchools(): iterable
    {
        return Sekolah::query()
            ->orderBy('nama')
            ->get(['id', 'nama', 'npsn', 'jenjang']);
    }

    public function getPeriods(): iterable
    {
        if (!$this->schoolId) {
            return collect();
        }

        return Laporan::query()
            ->where('sekolah_id', $this->schoolId)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get(['id', 'bulan', 'tahun', 'status', 'tanggal_submit']);
    }

    public function getSelectedLaporan(): ?Laporan
    {
        if (!$this->laporanId || !$this->schoolId) {
            return null;
        }

        return Laporan::where('sekolah_id', $this->schoolId)->find($this->laporanId);
    }

    public function isReadOnly(): bool
    {
        return auth()->user()?->hasRole('pengawas') ?? true;
    }

    public function openValidation(): void
    {
        if ($this->isReadOnly()) {
            Notification::make()
                ->title('Akses hanya untuk melihat')
                ->body('Pengawas tidak dapat menghapus atau membuka validasi.')
                ->warning()
                ->send();
            return;
        }

        $laporan = $this->getSelectedLaporan();
        if (!$laporan) {
            Notification::make()
                ->title('Periode belum dipilih')
                ->body('Pilih sekolah dan periode laporan terlebih dahulu.')
                ->warning()
                ->send();
            return;
        }

        $selectedSections = array_values(array_intersect(array_keys($this->sectionMap()), $this->sections));
        if (!$selectedSections) {
            Notification::make()
                ->title('Bagian belum dipilih')
                ->body('Pilih minimal satu bagian data yang akan direvalidasi.')
                ->warning()
                ->send();
            return;
        }

        $columns = collect($selectedSections)
            ->flatMap(fn(string $section) => $this->sectionMap()[$section])
            ->unique()
            ->values()
            ->all();

        $updates = array_fill_keys($columns, false);
        $updates['status'] = 'draft';
        $updates['tanggal_submit'] = null;
        $updates['verified_at'] = null;
        $updates['verified_by'] = null;
        $laporan->update($updates);

        $this->sections = [];

        Notification::make()
            ->title('Validasi berhasil dibuka kembali')
            ->body('Operator dapat memperbarui dan memvalidasi ulang bagian yang dipilih pada periode tersebut.')
            ->success()
            ->send();
    }

    public function sectionMap(): array
    {
        return [
            'identitas_sekolah' => ['is_identitas_sekolah_valid'],
            'kondisi_sarpras' => ['is_kondisi_sarpras_valid'],
            'mapel' => ['is_mapel_valid'],
            'rombel' => ['is_siswa_rombel_valid'],
            'keuangan' => ['is_keuangan_valid'],
            'nominatif_siswa' => [
                'is_nominatif_siswa_valid',
                'is_kondisi_siswa_valid',
                'is_siswa_rombel_valid',
                'is_siswa_umur_valid',
                'is_siswa_agama_valid',
                'is_siswa_daerah_valid',
                'is_siswa_disabilitas_valid',
                'is_siswa_beasiswa_valid',
            ],
            'nominatif_gtk' => [
                'is_nominatif_gtk_valid',
                'is_kondisi_gtk_valid',
                'is_gtk_agama_valid',
                'is_gtk_daerah_valid',
                'is_gtk_status_valid',
                'is_gtk_umur_valid',
                'is_gtk_pendidikan_valid',
            ],
            'riwayat_pendidikan_gtk' => ['is_gtk_pendidikan_valid'],
            'rekening_npwp_gtk' => ['is_rekening_npwp_valid'],
            'sebaran_jam' => ['is_sebaran_jam_valid'],
            'rekap_kehadiran' => ['is_rekap_kehadiran_valid'],
            'kelulusan' => ['is_kelulusan_valid'],
        ];
    }

    public function sectionLabels(): array
    {
        return [
            'identitas_sekolah' => 'Profil Sekolah',
            'kondisi_sarpras' => 'Sarana & Prasarana',
            'mapel' => 'Mata Pelajaran',
            'rombel' => 'Rombel',
            'keuangan' => 'Keuangan',
            'nominatif_siswa' => 'Nominatif Siswa',
            'nominatif_gtk' => 'Nominatif GTK',
            'riwayat_pendidikan_gtk' => 'Riwayat Pendidikan GTK',
            'rekening_npwp_gtk' => 'Rekening & NPWP GTK',
            'sebaran_jam' => 'Sebaran Jam Mengajar',
            'rekap_kehadiran' => 'Kehadiran GTK',
            'kelulusan' => 'Data Kelulusan',
        ];
    }

    public function getHeading(): string|Htmlable
    {
        return 'Revalidasi Laporan';
    }
}
