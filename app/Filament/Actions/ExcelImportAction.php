<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Filament\Actions\Imports\Importer;
use Illuminate\Support\Str;
use App\Support\ValidationPeriod;

class ExcelImportAction extends Action
{
    protected string $importerClass;

    public static function getDefaultName(): ?string
    {
        return 'import';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Impor Data (Excel)')
            ->modalHeading('Impor Data dari Excel')
            ->modalDescription('Unggah berkas Excel (.xlsx) yang sudah diisi sesuai format.')
            ->modalSubmitActionLabel('Mulai Impor')
            ->form([
                FileUpload::make('file')
                    ->label('Berkas Excel')
                    ->required()
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                    ->disk('local')
                    ->directory('imports')
                    ->visibility('private')
                    ->preserveFilenames()
                    ->live(),
            ])
            ->modalSubmitAction(fn ($action) => $action->extraAttributes([
                'x-data' => '{
                    get hasFile() {
                        let state = $wire.mountedActionsData?.[0]?.file || $wire.mountedTableActionsData?.[0]?.file || null;
                        if (!state) return false;
                        if (typeof state === \'string\') return state.length > 0;
                        if (typeof state === \'object\') return Object.keys(state).length > 0;
                        return false;
                    }
                }',
                'x-show' => 'hasFile',
                'x-transition' => '',
            ]))
            ->action(function (array $data) {
                if (ValidationPeriod::isLockedForOperatorPanel()) {
                    Notification::make()
                        ->title('Periode validasi sedang ditutup.')
                        ->body(ValidationPeriod::lockMessage())
                        ->danger()
                        ->send();

                    return;
                }

                $filePath = storage_path('app/private/' . $data['file']);
                // Dispatch the import job to the queue for asynchronous processing
                ImportExcelJob::dispatch($filePath, $this->importerClass);
            });
    }

    protected function mergeSheets(Collection $sheets): Collection
    {
        $primarySheet = $sheets->shift();

        $normalize = fn($s) => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string) $s));

        // Cari header row di sheet utama (bisa baris 0 atau 1)
        $primaryHeaderIndex = 0;
        $keyIndex           = false;

        foreach ($primarySheet->take(2) as $idx => $row) {
            $foundKey = $row->search(fn($h) => in_array($normalize($h), ['no', 'nourut', 'nik', 'nip']));
            if ($foundKey !== false) {
                $primaryHeaderIndex = $idx;
                $keyIndex           = $foundKey;
                break;
            }
        }

        if ($keyIndex === false) {
            return $primarySheet;
        }

        $primaryHeaders = $primarySheet[$primaryHeaderIndex];
        $mergedData     = [];
        $headerMap      = $primaryHeaders->toArray();

        // Kumpulkan data dari sheet utama, key = nilai kolom kunci (NO / NIK / NIP)
        // Hanya ambil baris yang key-nya adalah angka positif (skip baris instruksi)
        foreach ($primarySheet->slice($primaryHeaderIndex + 1) as $row) {
            $key = trim((string) ($row[$keyIndex] ?? ''));
            if ($key !== '' && is_numeric($key) && (int)$key > 0) {
                $mergedData[$key] = $row->toArray();
            }
        }

        foreach ($sheets as $sheet) {
            // Cari header row & kolom kunci di sheet tambahan
            $headerIndex  = 0;
            $sheetKeyIndex = false;

            foreach ($sheet->take(2) as $idx => $row) {
                $foundKey = $row->search(fn($h) => in_array($normalize($h), ['no', 'nourut', 'nik', 'nip']));
                if ($foundKey !== false) {
                    $headerIndex   = $idx;
                    $sheetKeyIndex = $foundKey;
                    break;
                }
            }

            if ($sheetKeyIndex === false) continue;

            $headers    = $sheet[$headerIndex];
            $newColumns = [];
            foreach ($headers as $idx => $header) {
                if ($idx === $sheetKeyIndex) continue;
                // Lewati kolom yang sudah ada di sheet utama (mis. NAMA GTK di sheet PENDIDIKAN)
                $alreadyExists = collect($headerMap)->contains(
                    fn($h) => $normalize($h) === $normalize($header)
                );
                if (!$alreadyExists) {
                    $newColumns[$idx] = count($headerMap);
                    $headerMap[]      = $header;
                }
            }

            foreach ($sheet->slice($headerIndex + 1) as $row) {
                $key = trim((string) ($row[$sheetKeyIndex] ?? ''));
                // Coba cocokkan langsung, lalu coba cocokkan sebagai angka
                // (sheet PENDIDIKAN/REKENING pakai NO integer, sheet IDENTITAS key-nya juga NO)
                if (isset($mergedData[$key])) {
                    foreach ($newColumns as $oldIdx => $newIdx) {
                        $mergedData[$key][$newIdx] = $row[$oldIdx] ?? null;
                    }
                }
            }
        }

        return collect([collect($headerMap)])
            ->concat(collect(array_values($mergedData))->map(fn($r) => collect($r)));
    }

    public function importer(string $importerClass): static
    {
        $this->importerClass = $importerClass;

        return $this;
    }

    public function getImporter(): string
    {
        return $this->importerClass;
    }

    protected function processImport(string $filePath): void
    {
        $sheets = Excel::toCollection(new class implements \Maatwebsite\Excel\Concerns\ToCollection {
            public function collection(Collection $rows) {}
        }, $filePath);

        if ($sheets->isEmpty()) {
            throw new \Exception('Berkas Excel kosong atau tidak terbaca.');
        }

        // If multiple sheets are present, merge them by NIK/NIP
        if ($sheets->count() > 1) {
            $rows = $this->mergeSheets($sheets);
        } else {
            $rows = $sheets->first();
        }

        if (!$rows || $rows->isEmpty()) {
            throw new \Exception('Tidak ada data yang terbaca dari berkas Excel.');
        }

        $headerRow = $rows->shift(); // Baris header pertama

        // Skip baris instruksi (baris ke-2) jika ada.
        // Deteksi otomatis: Cek apakah baris ke-2 mengandung kata kunci instruksi
        if ($rows->isNotEmpty()) {
            $secondRow = $rows->first();
            $isInstruction = $secondRow->contains(function ($value) {
                $str = strtolower((string) $value);
                return str_contains($str, 'diisi dengan') || str_contains($str, 'wajib diisi') || str_contains($str, 'contoh:');
            });

            if ($isInstruction) {
                $rows->shift();
            }
        }

        $columns = $this->importerClass::getColumns();
        
        // Try to map headers from Row 1
        $normalize = fn($s) => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string)$s));
        
        $columnMap = [];
        $matchCount = 0;
        foreach ($columns as $column) {
            $label = $column->getLabel() ?? Str::title($column->getName());
            $normalizedLabel = $normalize($label);
            $normalizedName = $normalize($column->getName());
            $normalizedGuesses = collect($column->getGuesses())->map($normalize);
            
            $index = $headerRow->search(fn($h) => 
                $normalize($h) === $normalizedLabel || 
                $normalize($h) === $normalizedName ||
                $normalizedGuesses->contains($normalize($h))
            );
            if ($index !== false) {
                $columnMap[$column->getName()] = $index;
                $matchCount++;
            }
        }

        // If very few matches were found, the first row might be a grouping header (Row 1: Group, Row 2: Header)
        // Check Row 2 if it exists and gives better matches
        $totalColumns = count($columns);
        // Anggap match buruk jika kurang dari sepertiga kolom yang terdeteksi, atau matchCount < 2 (untuk form kecil)
        $isBadMatch = $totalColumns > 0 && ($matchCount < min(2, $totalColumns) || $matchCount < ($totalColumns / 3));

        if ($isBadMatch && $rows->isNotEmpty()) {
            $secondRow = $rows->first(); // Gunakan first() bukan shift() agar data tidak hilang
            $secondRowMap = [];
            $secondMatchCount = 0;
            foreach ($columns as $column) {
                $label = $column->getLabel() ?? Str::title($column->getName());
                $normalizedLabel = $normalize($label);
                $normalizedName = $normalize($column->getName());
                $normalizedGuesses = collect($column->getGuesses())->map($normalize);

                $index = $secondRow->search(fn($h) => 
                    $normalize($h) === $normalizedLabel || 
                    $normalize($h) === $normalizedName ||
                    $normalizedGuesses->contains($normalize($h))
                );
                if ($index !== false) {
                    $secondRowMap[$column->getName()] = $index;
                    $secondMatchCount++;
                }
            }

            if ($secondMatchCount > $matchCount) {
                $headerRow = $rows->shift(); // Baru di-shift jika benar-benar header yang lebih baik
                $columnMap = $secondRowMap;
                $matchCount = $secondMatchCount;
            } else {
                // Jika baris kedua tidak membantu, biarkan saja (jangan di-shift)
            }
        }

        $successCount = 0;
        $errorCount = 0;
        $failureDetails = [];

        foreach ($rows as $rowIndex => $row) {
            if ($row->filter()->isEmpty()) continue; // Skip empty rows

            try {
                $rowData = [];
                foreach ($columnMap as $name => $index) {
                    $rowData[$name] = $row[$index] ?? null;
                }

                // Prepare column map for the Importer
                $importerColumnMap = [];
                foreach ($columnMap as $name => $index) {
                    $importerColumnMap[$name] = $index;
                }

                if ($rowIndex === 0) {
                    \Log::debug("Column Map: " . json_encode($importerColumnMap));
                    \Log::debug("First Row Data: " . json_encode($row->toArray()));
                }

                $importMock = new \Filament\Actions\Imports\Models\Import();
                if (auth()->check()) {
                    $importMock->setRelation('user', auth()->user());
                }

                $options = [];
                if (session()->has('dinas_selected_sekolah_id')) {
                    $options['dinas_selected_sekolah_id'] = session('dinas_selected_sekolah_id');
                }

                // Instantiate importer
                $importer = new $this->importerClass($importMock, $importerColumnMap, $options);
                
                // Process the row using __invoke
                // We pass the raw row data, and __invoke handles remapping via columnMap
                $importer($row->toArray());

                if ($importer->getRecord()?->exists) {
                    $successCount++;
                }
            } catch (\Illuminate\Validation\ValidationException $e) {
                $errorCount++;
                $errors = collect($e->errors())->flatten()->toArray();
                $errorMsg = implode(', ', $errors);
                
                $rowOffset = str_contains($this->importerClass, 'GtkImporter') ? 3 : 2;
                
                if (count($failureDetails) < 10) {
                    $failureDetails[] = "Baris " . ($rowIndex + $rowOffset) . ": " . $errorMsg;
                }
                
                \Log::error("Validation error at row " . ($rowIndex + $rowOffset) . ": " . $errorMsg);
            } catch (\Exception $e) {
                $errorCount++;
                $msg = $e->getMessage();
                $rowOffset = str_contains($this->importerClass, 'GtkImporter') ? 3 : 2;
                if (count($failureDetails) < 10) {
                    $failureDetails[] = "Baris " . ($rowIndex + $rowOffset) . ": " . $msg;
                }
                \Log::error("Import error at row " . ($rowIndex + $rowOffset) . ": " . $msg);
            }
        }


        $body = "Berhasil mengimpor {$successCount} baris.";
        
        if ($errorCount > 0) {
            $body .= " <br><span class='text-danger'>Gagal mengimpor {$errorCount} baris.</span>";
            $body .= "<div class='mt-2 text-xs text-gray-600 dark:text-gray-400 font-mono space-y-1'>";
            foreach (array_slice($failureDetails, 0, 5) as $detail) {
                $body .= "<div>• {$detail}</div>";
            }
            if (count($failureDetails) > 5) {
                $body .= "<div>...dan " . ($errorCount - 5) . " kesalahan lainnya.</div>";
            }
            $body .= "</div>";
        }

        Notification::make()
            ->title('Impor Selesai')
            ->success()
            ->persistent($errorCount > 0)
            ->body(new \Illuminate\Support\HtmlString($body))
            ->send();
    }
}
