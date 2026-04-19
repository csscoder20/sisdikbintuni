<?php

namespace App\Filament\Imports;

use App\Models\Gtk;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class GtkImporter extends Importer
{
    protected static ?string $model = Gtk::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('Siti Maimunah, S.Pd'),
            ImportColumn::make('nik')
                ->rules(['string', 'max:255'])
                ->example('3201010101010005'),
            ImportColumn::make('nip')
                ->rules(['string', 'max:255'])
                ->example('198501012010012001'),
            ImportColumn::make('nokarpeg')
                ->rules(['string', 'max:255'])
                ->example('K012345'),
            ImportColumn::make('nuptk')
                ->rules(['string', 'max:255'])
                ->example('1234567890123456'),
            ImportColumn::make('jenis_kelamin')
                ->rules(['string', 'max:255'])
                ->example('Perempuan')
                ->castStateUsing(function (string $state): ?string {
                    $state = strtolower($state);
                    if ($state === 'l' || str_contains($state, 'laki')) return 'Laki-laki';
                    if ($state === 'p' || str_contains($state, 'perempuan')) return 'Perempuan';
                    return null;
                }),
            ImportColumn::make('tempat_lahir')
                ->rules(['string', 'max:255'])
                ->example('Sorong'),
            ImportColumn::make('tanggal_lahir')
                ->rules(['date'])
                ->example('15/05/1985')
                ->castStateUsing(function (string $state): ?string {
                    if (blank($state)) return null;
                    try {
                        if (preg_match('/^\d{1,2}[\/\-]\d{1,2}[\/\-]\d{4}$/', $state)) {
                            $separator = str_contains($state, '/') ? '/' : '-';
                            return \Illuminate\Support\Carbon::createFromFormat("d{$separator}m{$separator}Y", $state)->format('Y-m-d');
                        }
                        return \Illuminate\Support\Carbon::parse($state)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return $state;
                    }
                }),
            ImportColumn::make('alamat')
                ->rules(['string'])
                ->example('Jl. Pendidikan No. 45'),
            ImportColumn::make('desa')
                ->rules(['string', 'max:255'])
                ->example('Bintuni Barat'),
            ImportColumn::make('kecamatan')
                ->rules(['string', 'max:255'])
                ->example('Bintuni'),
            ImportColumn::make('kabupaten')
                ->rules(['string', 'max:255'])
                ->example('Teluk Bintuni'),
            ImportColumn::make('provinsi')
                ->rules(['string', 'max:255'])
                ->example('Papua Barat'),
            ImportColumn::make('agama')
                ->rules(['string', 'max:255'])
                ->example('Islam'),
            ImportColumn::make('pendidikan_terakhir')
                ->rules(['string', 'max:255'])
                ->example('S1 Pendidikan Bahasa'),
            ImportColumn::make('daerah_asal')
                ->rules(['string', 'max:255'])
                ->example('Papua')
                ->castStateUsing(fn(string $state): string => 
                    stripos($state, 'papua') !== false && stripos($state, 'non') === false ? 'Papua' : 'Non Papua'
                ),
            ImportColumn::make('jenis_gtk')
                ->rules(['string', 'max:255'])
                ->example('Guru'),
            ImportColumn::make('status_kepegawaian')
                ->rules(['string', 'max:255'])
                ->example('PNS'),
            ImportColumn::make('tmt_pns')
                ->rules(['date'])
                ->example('01/01/2010')
                ->castStateUsing(function (string $state): ?string {
                    if (blank($state)) return null;
                    try {
                        if (preg_match('/^\d{1,2}[\/\-]\d{1,2}[\/\-]\d{4}$/', $state)) {
                            $separator = str_contains($state, '/') ? '/' : '-';
                            return \Illuminate\Support\Carbon::createFromFormat("d{$separator}m{$separator}Y", $state)->format('Y-m-d');
                        }
                        return \Illuminate\Support\Carbon::parse($state)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return $state;
                    }
                }),
            ImportColumn::make('pangkat_gol_terakhir')
                ->rules(['string', 'max:255'])
                ->example('III/b'),
            ImportColumn::make('tmt_pangkat_gol_terakhir')
                ->rules(['date'])
                ->example('01/01/2022')
                ->castStateUsing(function (string $state): ?string {
                    if (blank($state)) return null;
                    try {
                        if (preg_match('/^\d{1,2}[\/\-]\d{1,2}[\/\-]\d{4}$/', $state)) {
                            $separator = str_contains($state, '/') ? '/' : '-';
                            return \Illuminate\Support\Carbon::createFromFormat("d{$separator}m{$separator}Y", $state)->format('Y-m-d');
                        }
                        return \Illuminate\Support\Carbon::parse($state)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return $state;
                    }
                }),
        ];
    }

    public function resolveRecord(): Gtk
    {
        $sekolahId = filament()->getTenant()?->id ?? $this->import->user->sekolah?->id;

        if (! $sekolahId) {
            throw new \Exception('Gagal mendeteksi data Sekolah. Pastikan Anda melakukan import di dalam panel sekolah yang benar.');
        }

        return Gtk::firstOrNew([
            'sekolah_id' => $sekolahId,
            'nik' => $this->data['nik'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Impor GTK selesai. ' . Number::format($import->successful_rows) . ' baris berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' baris gagal diimpor.';
        }

        return $body;
    }
}
