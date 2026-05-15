<?php

namespace App\Filament\Resources\Gtks\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\ImportAction;
use App\Filament\Imports\GtkImporter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\CheckboxList;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;


class GtksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->striped()
            ->defaultSort('id', 'asc')
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama GTK')
                    ->searchable(),
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable(),
                TextColumn::make('nokarpeg')
                    ->label('No Karpeg')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nuptk')
                    ->label('NUPTK')
                    ->searchable(),
                TextColumn::make('jenis_gtk')
                    ->label('Jenis GTK')
                    ->searchable(),
                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->searchable(),
                TextColumn::make('status_kepegawaian')
                    ->label('Status Kepegawaian')
                    ->searchable(),
                TextColumn::make('pangkat_gol_terakhir')
                    ->label('Pangkat Gol Terakhir')
                    ->searchable(),
                TextColumn::make('tmt_pns')
                    ->label('TMT PNS')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pendidikan_terakhir')
                    ->label('Pendidikan Terakhir')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('daerah_asal')
                    ->label('Daerah Asal')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('agama')
                    ->label('Agama')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('desa')
                    ->label('Desa')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kecamatan')
                    ->label('Kecamatan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kabupaten')
                    ->label('Kabupaten')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('provinsi')
                    ->label('Provinsi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tmt_pangkat_gol_terakhir')
                    ->label('TMT Pangkat Gol')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nama_bank_gaji')
                    ->label('Bank Gaji')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('no_rek_gaji')
                    ->label('No. Rekening Gaji')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nama_bank_tunjangan')
                    ->label('Bank Tunjangan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('no_rek_tunjangan')
                    ->label('No. Rekening Tunjangan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('npwp')
                    ->label('NPWP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status_kepegawaian')
                    ->label('Status Kepegawaian')
                    ->options([
                        'PNS' => 'PNS',
                        'CPNS' => 'CPNS',
                        'PPPK' => 'PPPK',
                        'GTY/PTY' => 'GTY/PTY',
                        'Kontrak' => 'Kontrak',
                        'Honorer Sekolah' => 'Honorer Sekolah',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                    ViewAction::make()
                        ->modalWidth(\Filament\Support\Enums\Width::FiveExtraLarge)
                        ->icon(Heroicon::OutlinedEye),
                    EditAction::make()
                        ->modalFooterActions([])
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
                    BulkAction::make('exportPdf')
                        ->label('Export PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->modalHeading('Export Nominatif GTK ke PDF')
                        ->modalDescription('Pilih kolom yang ingin Anda sertakan dalam file PDF.')
                        ->modalSubmitActionLabel('Export Sekarang')
                        ->form([
                            CheckboxList::make('columns')
                                ->label('Pilih Kolom')
                                ->options([
                                    'nama' => 'Nama GTK',
                                    'nik' => 'NIK',
                                    'nip' => 'NIP',
                                    'nuptk' => 'NUPTK',
                                    'nokarpeg' => 'No Karpeg',
                                    'jenis_gtk' => 'Jenis GTK',
                                    'jenis_kelamin' => 'Jenis Kelamin',
                                    'status_kepegawaian' => 'Status Kepegawaian',
                                    'pangkat_gol_terakhir' => 'Pangkat Gol Terakhir',
                                    'tmt_pns' => 'TMT PNS',
                                    'tempat_lahir' => 'Tempat Lahir',
                                    'tanggal_lahir' => 'Tanggal Lahir',
                                    'pendidikan_terakhir' => 'Pendidikan Terakhir',
                                    'daerah_asal' => 'Daerah Asal',
                                    'agama' => 'Agama',
                                    'alamat' => 'Alamat',
                                    'desa' => 'Desa',
                                    'kecamatan' => 'Kecamatan',
                                    'kabupaten' => 'Kabupaten',
                                    'provinsi' => 'Provinsi',
                                    'tmt_pangkat_gol_terakhir' => 'TMT Pangkat Gol',
                                    'nama_bank_gaji' => 'Bank Gaji',
                                    'no_rek_gaji' => 'No. Rekening Gaji',
                                    'nama_bank_tunjangan' => 'Bank Tunjangan',
                                    'no_rek_tunjangan' => 'No. Rekening Tunjangan',
                                    'npwp' => 'NPWP',
                                ])
                                ->columns(3)
                                ->default(['nama', 'nip', 'nuptk'])
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $allColumns = [
                                'nama' => 'Nama GTK',
                                'nik' => 'NIK',
                                'nip' => 'NIP',
                                'nuptk' => 'NUPTK',
                                'nokarpeg' => 'No Karpeg',
                                'jenis_gtk' => 'Jenis GTK',
                                'jenis_kelamin' => 'Jenis Kelamin',
                                'status_kepegawaian' => 'Status Kepegawaian',
                                'pangkat_gol_terakhir' => 'Pangkat Gol Terakhir',
                                'tmt_pns' => 'TMT PNS',
                                'tempat_lahir' => 'Tempat Lahir',
                                'tanggal_lahir' => 'Tanggal Lahir',
                                'pendidikan_terakhir' => 'Pendidikan Terakhir',
                                'daerah_asal' => 'Daerah Asal',
                                'agama' => 'Agama',
                                'alamat' => 'Alamat',
                                'desa' => 'Desa',
                                'kecamatan' => 'Kecamatan',
                                'kabupaten' => 'Kabupaten',
                                'provinsi' => 'Provinsi',
                                'tmt_pangkat_gol_terakhir' => 'Pangkat Gol Terakhir',
                                'nama_bank_gaji' => 'Bank Gaji',
                                'no_rek_gaji' => 'No. Rekening Gaji',
                                'nama_bank_tunjangan' => 'Bank Tunjangan',
                                'no_rek_tunjangan' => 'No. Rekening Tunjangan',
                                'npwp' => 'NPWP',
                            ];

                            $selectedColumns = array_intersect_key($allColumns, array_flip($data['columns']));

                            $pdf = Pdf::loadView('pdf.gtk-nominatif', [
                                'records' => $records,
                                'columns' => $selectedColumns,
                                'sekolah' => filament()->getTenant(),
                            ])->setPaper('a4', count($data['columns']) > 5 ? 'landscape' : 'portrait');

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                'nominatif-gtk-' . now()->format('Y-m-d-His') . '.pdf'
                            );
                        }),
                    DeleteBulkAction::make(),
                ]),
            ])
            ;
    }
}
