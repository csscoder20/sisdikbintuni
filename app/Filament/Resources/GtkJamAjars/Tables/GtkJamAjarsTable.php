<?php

namespace App\Filament\Resources\GtkJamAjars\Tables;

use App\Models\GtkTugasTambahan;
use App\Models\Mapel;
use App\Models\Mengajar;
use App\Models\Rombel;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Actions;
use Illuminate\Support\Facades\DB;
use Filament\Support\Enums\Alignment;

class GtkJamAjarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->columns([
                TextColumn::make('gtk.nama')
                    ->label('GTK')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_jam_mengajar')
                    ->label('Jumlah Jam Mengajar')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jumlah_tugas_tambahan')
                    ->label('Jumlah Jam Tugas Tambahan')
                    ->state(fn (Mengajar $record): int => (int) ($record->gtk?->tugasTambahan?->jumlah_jam ?? 0)),
                TextColumn::make('total_jam')
                    ->label('Total Jam')
                    ->state(fn (Mengajar $record): int => (int) ($record->total_jam_mengajar ?? 0) + (int) ($record->gtk?->tugasTambahan?->jumlah_jam ?? 0))
                    ->numeric()
                    ->alignCenter(),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->state(function (Mengajar $record): string {
                        $total = (int) ($record->total_jam_mengajar ?? 0) + (int) ($record->gtk?->tugasTambahan?->jumlah_jam ?? 0);
                        return $total >= 24 ? 'Terpenuhi' : 'Belum Terpenuhi';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Terpenuhi' => 'success',
                        'Belum Terpenuhi' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('tambahJamMengajar')
                        ->label('Kelola Jam Mengajar')
                        ->icon(Heroicon::OutlinedPlusCircle)
                        ->modalWidth(Width::FiveExtraLarge)
                        ->modalHeading(fn (Mengajar $record): string => 'Tambah Jam Mengajar: ' . ($record->gtk?->nama ?? '-'))
                        ->modalSubmitActionLabel('Simpan Jam Mengajar')
                        ->modalCancelAction(false)
                        ->skippableSteps()
                        ->fillForm(fn (Mengajar $record): array => [
                            'tugas_utama' => $record->teachingEntries()
                                ->orderBy('rombel_id')
                                ->orderBy('mapel_id')
                                ->get()
                                ->map(fn (Mengajar $entry): array => [
                                    'rombel_id' => $entry->rombel_id,
                                    'mapel_id' => $entry->mapel_id,
                                    'jumlah_jam' => $entry->jumlah_jam,
                                ])
                                ->values()
                                ->all(),
                            'tugas_tambahan' => $record->gtk?->tugasTambahan?->tugas_tambahan,
                            'jumlah_jam_tugas_tambahan' => $record->gtk?->tugasTambahan?->jumlah_jam,
                        ])
                        ->steps([
                            Step::make('Tugas Utama')
                                ->schema([
                                    Grid::make(12)
                                        ->schema([
                                            Placeholder::make('head_rombel')
                                                ->hiddenLabel()
                                                ->content(new \Illuminate\Support\HtmlString('<span class="font-medium text-sm text-gray-700 dark:text-gray-300">Nama Rombel</span>'))
                                                ->columnSpan(4),
                                            Placeholder::make('head_mapel')
                                                ->hiddenLabel()
                                                ->content(new \Illuminate\Support\HtmlString('<span class="font-medium text-sm text-gray-700 dark:text-gray-300">Mata Pelajaran</span>'))
                                                ->columnSpan(5),
                                            Placeholder::make('head_jjp')
                                                ->hiddenLabel()
                                                ->content(new \Illuminate\Support\HtmlString('<span class="font-medium text-sm text-gray-700 dark:text-gray-300 block text-center">JJP</span>'))
                                                ->columnSpan(2),
                                        ])
                                        ->extraAttributes(['class' => 'hidden md:grid px-4']),
                                    Repeater::make('tugas_utama')
                                        ->key('tugas_utama')
                                        ->live()
                                    ->hiddenLabel()
                                    ->addActionLabel('Tambah Tugas Utama')
                                    ->addActionAlignment(Alignment::Start)
                                    ->addAction(fn (Action $action): Action => $action
                                        ->label('Tambah Tugas Utama')
                                        ->icon(Heroicon::OutlinedPlusCircle)
                                        ->button()
                                        ->color('success')
                                    )
                                    ->defaultItems(0)
                                    ->reorderable(false)
                                        ->schema([
                                            Grid::make(12)
                                                ->schema([
                                                    Select::make('rombel_id')
                                                        ->hiddenLabel()
                                                        ->placeholder('Pilih Kelas')
                                                        ->options(fn (): array => self::getRombelOptions())
                                                        ->searchable()
                                                        ->live()
                                                        ->afterStateUpdated(function (callable $set): void {
                                                            $set('mapel_id', null);
                                                            $set('jumlah_jam', null);
                                                        })
                                                        ->required()
                                                        ->columnSpan(4),
                                                    Select::make('mapel_id')
                                                        ->hiddenLabel()
                                                        ->placeholder('Pilih Mapel')
                                                        ->options(function ($component, callable $get): array {
                                                                $rombelId       = $get('rombel_id');
                                                                $currentMapelId = $get('mapel_id');

                                                                // Naik ke Repeater lewat pohon komponen
                                                                try {
                                                                    $container  = $component->getContainer();
                                                                    $grid       = $container->getParentComponent();
                                                                    $itemSchema = $grid->getContainer();
                                                                    $repeater   = $itemSchema->getParentComponent();
                                                                    $allItems   = $repeater->getRawState() ?? [];
                                                                } catch (\Throwable) {
                                                                    $allItems = [];
                                                                }

                                                                // Kumpulkan mapel yang sudah dipakai di rombel yang sama (kecuali baris ini sendiri)
                                                                $usedMapelIds = collect($allItems)
                                                                    ->filter(fn ($item) =>
                                                                        filled($item['rombel_id'] ?? null) &&
                                                                        filled($item['mapel_id'] ?? null) &&
                                                                        (string) ($item['rombel_id']) === (string) $rombelId
                                                                    )
                                                                    ->pluck('mapel_id')
                                                                    ->filter()
                                                                    ->reject(fn ($id) => (string) $id === (string) $currentMapelId)
                                                                    ->values()
                                                                    ->all();

                                                                return self::getMapelOptions($rombelId, $usedMapelIds);
                                                            })
                                                        ->searchable()
                                                        ->live()
                                                        ->required()
                                                        ->afterStateHydrated(function ($state, callable $set): void {
                                                            if (blank($state)) {
                                                                return;
                                                            }

                                                            $set('jumlah_jam', Mapel::query()->find($state)?->jjp);
                                                        })
                                                        ->afterStateUpdated(function ($state, callable $set): void {
                                                            $set('jumlah_jam', Mapel::query()->find($state)?->jjp);
                                                        })
                                                        ->columnSpan(5),
                                                    TextInput::make('jumlah_jam')
                                                        ->hiddenLabel()
                                                        ->placeholder('Jam')
                                                        ->numeric()
                                                        ->readOnly()
                                                        ->required()
                                                        ->extraInputAttributes(['class' => 'text-center'])
                                                        ->columnSpan(2),
                                                    Actions::make([
                                                        Action::make('remove')
                                                            ->label('')
                                                            ->tooltip('Hapus baris')
                                                            ->icon('heroicon-m-trash')
                                                            ->color('danger')
                                                            ->button()
                                                            ->size('sm')
                                                            ->action(function ($component) {
                                                                $container = $component->getContainer();
                                                                $grid = $container->getParentComponent();
                                                                $itemSchema = $grid->getContainer();
                                                                $repeater = $itemSchema->getParentComponent();
                                                                $key = $itemSchema->getKey(isAbsolute: false);

                                                                $items = $repeater->getRawState();
                                                                unset($items[$key]);

                                                                $repeater->rawState($items);
                                                                $repeater->callAfterStateUpdated();
                                                                $repeater->partiallyRender();
                                                            })
                                                    ])
                                                    ->verticalAlignment('center')
                                                    ->alignCenter()
                                                    ->columnSpan(1),
                                                ])
                                                ->columns(12),
                                        ])
                                        ->columns(1)
                                        ->extraAttributes(['class' => 'table-style-repeater'])
                                        ->deleteAction(fn (Action $action) => $action->hidden())
                                        ->columnSpanFull(),
                                    Placeholder::make('summary_total_jam_utama')
                                        ->hiddenLabel()
                                        ->content(function (callable $get, Mengajar $record): \Illuminate\Support\HtmlString {
                                            $total = self::sumTeachingHours($get('tugas_utama') ?? []);
                                            $nama = $record->gtk?->nama ?? '-';
                                            return new \Illuminate\Support\HtmlString("Jumlah Jam Mengajar dari {$nama} adalah sebanyak <strong>{$total}</strong> jam.");
                                        }),
                                ]),
                            Step::make('Tugas Tambahan')
                                ->schema([
                                    TextInput::make('tugas_tambahan')
                                        ->label('Nama Tugas Tambahan')
                                        ->maxLength(255),
                                    TextInput::make('jumlah_jam_tugas_tambahan')
                                        ->label('Jumlah Jam')
                                        ->numeric()
                                        ->live()
                                        ->default(0),
                                    Placeholder::make('summary_total_jam_tambahan')
                                        ->hiddenLabel()
                                        ->content(function (callable $get, Mengajar $record): \Illuminate\Support\HtmlString {
                                            $totalUtama = self::sumTeachingHours($get('tugas_utama') ?? []);
                                            $totalTambahan = (int) ($get('jumlah_jam_tugas_tambahan') ?? 0);
                                            $total = $totalUtama + $totalTambahan;
                                            $nama = $record->gtk?->nama ?? '-';
                                            return new \Illuminate\Support\HtmlString("Total Seluruh Jam Mengajar + Tugas Tambahan dari {$nama} adalah sebanyak <strong>{$total}</strong> jam.");
                                        })
                                        ->columnSpanFull(),
                                ])
                                ->columns(2),
                        ])
                        ->action(function (Mengajar $record, array $data): void {
                            DB::transaction(function () use ($record, $data): void {
                                Mengajar::withTrashed()
                                    ->where('gtk_id', $record->gtk_id)
                                    ->where('id', '!=', $record->id)
                                    ->forceDelete();

                                foreach (collect($data['tugas_utama'] ?? [])->filter(fn (array $item): bool => filled($item['rombel_id'] ?? null) && filled($item['mapel_id'] ?? null)) as $item) {
                                    Mengajar::create([
                                        'gtk_id' => $record->gtk_id,
                                        'rombel_id' => $item['rombel_id'],
                                        'mapel_id' => $item['mapel_id'],
                                        'jumlah_jam' => $item['jumlah_jam'] ?? null,
                                        'semester' => null,
                                        'tahun_ajaran' => null,
                                        'laporan_id' => null,
                                    ]);
                                }

                                $tugasTambahan = trim((string) ($data['tugas_tambahan'] ?? ''));
                                $jumlahJamTugasTambahan = $data['jumlah_jam_tugas_tambahan'] ?? null;

                                if ($tugasTambahan === '' && blank($jumlahJamTugasTambahan)) {
                                    GtkTugasTambahan::withTrashed()
                                        ->where('gtk_id', $record->gtk_id)
                                        ->forceDelete();

                                    return;
                                }

                                $tugasTambahanRecord = GtkTugasTambahan::withTrashed()->firstOrNew([
                                    'gtk_id' => $record->gtk_id,
                                ]);

                                if ($tugasTambahanRecord->exists && method_exists($tugasTambahanRecord, 'trashed') && $tugasTambahanRecord->trashed()) {
                                    $tugasTambahanRecord->restore();
                                }

                                $tugasTambahanRecord->fill([
                                    'tugas_tambahan' => $tugasTambahan !== '' ? $tugasTambahan : null,
                                    'jumlah_jam' => filled($jumlahJamTugasTambahan) ? (int) $jumlahJamTugasTambahan : null,
                                ]);
                                $tugasTambahanRecord->save();
                            });

                            Notification::make()
                                ->title('Jam mengajar berhasil disimpan')
                                ->success()
                                ->send();
                        }),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                    ViewAction::make()
                        ->modalWidth(Width::FiveExtraLarge)
                        ->icon(Heroicon::OutlinedEye)
                        ->form([
                            Placeholder::make('nama_gtk')
                                ->hiddenLabel()
                                ->content(fn (Mengajar $record): \Illuminate\Support\HtmlString => new \Illuminate\Support\HtmlString('Nama GTK : <strong>' . e($record->gtk?->nama ?? '-') . '</strong>')),
                            Placeholder::make('pivot_table')
                                ->hiddenLabel()
                                ->content(function (Mengajar $record): \Illuminate\Support\HtmlString {
                                    $gtk = $record->gtk;
                                    if (! $gtk) return new \Illuminate\Support\HtmlString('Data GTK tidak ditemukan.');

                                    $entries = $record->teachingEntries()
                                        ->with('mapel', 'rombel')
                                        ->get();

                                    $usedRombelIds = $entries->pluck('rombel_id')->unique();

                                    $rombols = Rombel::query()
                                        ->whereIn('id', $usedRombelIds)
                                        ->orderBy('tingkat')
                                        ->orderBy('nama')
                                        ->get();

                                    $tugasTambahan = $gtk->tugasTambahan;

                                    // Group entries by Mapel
                                    $mapels = $entries->groupBy('mapel_id');

                                    $styleTable = 'width: 100%; border-collapse: collapse; border: 1px solid #ccc; font-size: 0.875rem;';
                                    $styleTh = 'border: 1px solid #ccc; padding: 8px; background-color: #f9fafb; font-weight: bold; text-align: center;';
                                    $styleTd = 'border: 1px solid #ccc; padding: 8px;';
                                    $styleTdCenter = 'border: 1px solid #ccc; padding: 8px; text-align: center;';

                                    $html = '<div style="overflow-x: auto; margin-top: 0;">';
                                    $html .= '<table style="' . $styleTable . '">';
                                    
                                    // Header
                                    $html .= '<thead>';
                                    $html .= '<tr>';
                                    $html .= '<th rowspan="2" style="' . $styleTh . ' width: 50px;">NO</th>';
                                    $html .= '<th rowspan="2" style="' . $styleTh . ' text-align: left;">Nama Mapel</th>';
                                    $html .= '<th colspan="' . $rombols->count() . '" style="' . $styleTh . '">Kelas</th>';
                                    $html .= '<th rowspan="2" style="' . $styleTh . ' width: 80px;">Total</th>';
                                    $html .= '</tr><tr>';
                                    foreach ($rombols as $rombel) {
                                        $rombelName = str_replace('Kelas ', '', $rombel->nama);
                                        $html .= '<th style="' . $styleTh . ' min-width: 100px;">' . e($rombelName) . '</th>';
                                    }
                                    $html .= '</tr></thead>';

                                    $html .= '<tbody>';
                                    
                                    $grandTotal = 0;
                                    $no = 1;

                                    // Mapel Rows
                                    foreach ($mapels as $mapelId => $mapelEntries) {
                                        $mapelName = $mapelEntries->first()?->mapel?->nama_mapel ?? '-';
                                        if ($tingkat = $mapelEntries->first()?->mapel?->tingkat) {
                                            $mapelName .= " (Kls {$tingkat})";
                                        }

                                        $html .= '<tr>';
                                        $html .= '<td style="' . $styleTdCenter . '">' . $no++ . '</td>';
                                        $html .= '<td style="' . $styleTd . '">' . e($mapelName) . '</td>';
                                        
                                        $rowTotal = 0;
                                        foreach ($rombols as $rombel) {
                                            $entry = $mapelEntries->firstWhere('rombel_id', $rombel->id);
                                            $val = $entry ? (int)$entry->jumlah_jam : 0;
                                            $html .= '<td style="' . $styleTdCenter . '">' . ($val ?: '') . '</td>';
                                            $rowTotal += $val;
                                        }
                                        $html .= '<td style="' . $styleTdCenter . ' font-weight: bold; background-color: #f9fafb;">' . $rowTotal . '</td>';
                                        $grandTotal += $rowTotal;
                                        $html .= '</tr>';
                                    }

                                    // Tugas Tambahan Row
                                    if ($tugasTambahan && filled($tugasTambahan->tugas_tambahan)) {
                                        $html .= '<tr>';
                                        $html .= '<td style="' . $styleTdCenter . '">' . $no++ . '</td>';
                                        $html .= '<td style="' . $styleTd . '">' . e($tugasTambahan->tugas_tambahan) . ' (tugas tambahan)</td>';
                                        
                                        $valTambahan = (int)($tugasTambahan->jumlah_jam ?? 0);
                                        foreach ($rombols as $rombel) {
                                            $html .= '<td style="' . $styleTdCenter . '"></td>';
                                        }
                                        $html .= '<td style="' . $styleTdCenter . ' font-weight: bold; background-color: #f9fafb;">' . $valTambahan . '</td>';
                                        $grandTotal += $valTambahan;
                                        $html .= '</tr>';
                                    }

                                    // Grand Total Footer
                                    $html .= '<tr style="font-weight: bold; background-color: #f3f4f6; color: #2563eb;">';
                                    $html .= '<td colspan="' . ($rombols->count() + 2) . '" style="' . $styleTd . ' text-align: center;">Grand Total</td>';
                                    $html .= '<td style="' . $styleTdCenter . '">' . $grandTotal . '</td>';
                                    $html .= '</tr>';

                                    $html .= '</tbody></table></div>';

                                    return new \Illuminate\Support\HtmlString($html);
                                }),
                        ]),
                    EditAction::make()
                        ->icon(Heroicon::OutlinedPencilSquare),
                    DeleteAction::make()
                        ->icon(Heroicon::OutlinedTrash),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('primary'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function getRombelOptions(): array
    {
        return Rombel::query()
            ->where('sekolah_id', filament()->getTenant()?->id)
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->pluck('nama', 'id')
            ->all();
    }

    protected static function getMapelOptions($rombelId = null, array $excludeMapelIds = []): array
    {
        $jenjang = filament()->getTenant()?->jenjang;
        $tingkat = null;

        if ($rombelId) {
            $tingkat = Rombel::query()->find($rombelId)?->tingkat;
        }

        return Mapel::query()
            ->when($jenjang, fn ($query) => $query->where('jenjang', $jenjang))
            ->when($tingkat, fn ($query) => $query->where('tingkat', $tingkat))
            ->when(filled($excludeMapelIds), fn ($query) => $query->whereNotIn('id', $excludeMapelIds))
            ->orderBy('nama_mapel')
            ->orderBy('tingkat')
            ->get()
            ->mapWithKeys(function (Mapel $mapel) {
                $label = $mapel->nama_mapel;
                if ($mapel->tingkat) {
                    $label .= " (Kls {$mapel->tingkat})";
                }
                return [$mapel->id => $label];
            })
            ->all();
    }

    protected static function sumTeachingHours(array $rows): int
    {
        return (int) collect($rows)
            ->sum(fn (array $item): int => (int) ($item['jumlah_jam'] ?? 0));
    }
}
