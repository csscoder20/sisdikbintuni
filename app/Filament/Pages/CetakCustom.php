<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Facades\Filament;
use App\Models\Sekolah;

class CetakCustom extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedDocumentPlus;

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

    public static function shouldRegisterNavigation(): bool
    {
        if (Filament::getCurrentPanel()?->getId() === 'dinas') {
            return !empty(session('dinas_selected_sekolah_id'));
        }
        return false;
    }

    public function mount(): void
    {
        $this->form->fill([
            'sekolah_id' => session('dinas_selected_sekolah_id'),
            'komponen' => ['profil', 'siswa', 'gtk'],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Konfigurasi Laporan Gabungan')
                    ->description('Pilih data yang ingin Anda gabungkan ke dalam satu dokumen PDF.')
                    ->schema([
                        Select::make('sekolah_id')
                            ->label('Target Sekolah')
                            ->options(Sekolah::orderBy('nama')->pluck('nama', 'id'))
                            ->searchable()
                            ->required()
                            ->disabled() // Locked to current context but visible
                            ->dehydrated(true),
                        
                        CheckboxList::make('komponen')
                            ->label('Komponen Data')
                            ->options([
                                'profil' => 'Profil Identitas Sekolah',
                                'siswa' => 'Rekapitulasi Data Siswa',
                                'gtk' => 'Daftar Guru & Tenaga Kependidikan',
                                'sarpras' => 'Kondisi Sarana & Prasarana',
                                'keuangan' => 'Ringkasan Laporan Keuangan',
                            ])
                            ->columns(2)
                            ->required(),
                        
                        Select::make('format')
                            ->label('Format Dokumen')
                            ->options([
                                'pdf' => 'Portable Document Format (PDF)',
                                'excel' => 'Microsoft Excel (XLSX)',
                            ])
                            ->default('pdf')
                            ->required(),
                    ])
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
        // Logic for generation would go here
        $this->notification()
            ->title('Laporan sedang diproses')
            ->body('Fitur ekspor gabungan akan segera tersedia.')
            ->success()
            ->send();
    }
}
