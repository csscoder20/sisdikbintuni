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
                    ->state(fn (Mengajar $record): string => $record->gtk?->tugasTambahan?->tugas_tambahan ?: '-'),
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
                        ->label('Tambah Jam Mengajar')
                        ->icon(Heroicon::OutlinedPlusCircle)
                        ->modalWidth(Width::FiveExtraLarge)
                        ->modalHeading(fn (Mengajar $record): string => 'Tambah Jam Mengajar: ' . ($record->gtk?->nama ?? '-'))
                        ->modalSubmitActionLabel('Simpan Jam Mengajar')
                        ->extraModalWindowAttributes(['class' => 'modal-batal-kanan'])
                        ->modalCancelAction(fn ($action) => $action->label('Batal')->color('gray'))
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
                                    Repeater::make('tugas_utama')
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
                                                        ->required()
                                                        ->columnSpan(4),
                                                    Select::make('mapel_id')
                                                        ->hiddenLabel()
                                                        ->placeholder('Pilih Mapel')
                                                        ->options(fn (): array => self::getMapelOptions())
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
                                                                $container = $component->getComponent()->getContainer();
                                                                $container->getParentComponent()->removeItem($container->getUuid());
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
                                ]),
                            Step::make('Tugas Tambahan')
                                ->schema([
                                    TextInput::make('tugas_tambahan')
                                        ->label('Nama Tugas Tambahan')
                                        ->maxLength(255),
                                    TextInput::make('jumlah_jam_tugas_tambahan')
                                        ->label('Jumlah Jam')
                                        ->numeric()
                                        ->default(0),
                                    Placeholder::make('summary_total_semua_jam')
                                        ->label('Summary (Total Seluruh Jam)')
                                        ->content(function (callable $get): int {
                                            return self::sumTeachingHours($get('tugas_utama') ?? []) + (int) ($get('jumlah_jam_tugas_tambahan') ?? 0);
                                        }),
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
                        ->icon(Heroicon::OutlinedEye),
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

    protected static function getMapelOptions(): array
    {
        $jenjang = filament()->getTenant()?->jenjang;

        return Mapel::query()
            ->when($jenjang, fn ($query) => $query->where('jenjang', $jenjang))
            ->orderBy('nama_mapel')
            ->pluck('nama_mapel', 'id')
            ->all();
    }

    protected static function sumTeachingHours(array $rows): int
    {
        return (int) collect($rows)
            ->sum(fn (array $item): int => (int) ($item['jumlah_jam'] ?? 0));
    }
}
