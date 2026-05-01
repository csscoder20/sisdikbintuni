<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Filament\Actions\Imports\Importer;
use Illuminate\Support\Str;

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
                    ->preserveFilenames(),
            ])
            ->action(function (array $data) {
                $filePath = storage_path('app/private/' . $data['file']);
                
                try {
                    $this->processImport($filePath);
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Gagal mengimpor data')
                        ->danger()
                        ->body($e->getMessage())
                        ->send();
                }
            });
    }

    protected function mergeSheets(Collection $sheets): Collection
    {
        $primarySheet = $sheets->shift();
        
        // Find header row in primary sheet (could be index 0 or 1)
        $normalize = fn($s) => strtolower(preg_replace('/[^a-zA-Z0-0]/', '', (string)$s));
        $primaryHeaderIndex = 0;
        $keyIndex = false;
        
        foreach ($primarySheet->take(2) as $idx => $row) {
            $foundKey = $row->search(fn($h) => in_array($normalize($h), ['no', 'nourut', 'nik', 'nip']));
            if ($foundKey !== false) {
                $primaryHeaderIndex = $idx;
                $keyIndex = $foundKey;
                break;
            }
        }

        if ($keyIndex === false) {
            return $primarySheet;
        }

        $primaryHeaders = $primarySheet[$primaryHeaderIndex];
        $mergedData = [];
        $headerMap = $primaryHeaders->toArray();
        
        foreach ($primarySheet->slice($primaryHeaderIndex + 1) as $row) {
            $key = (string) ($row[$keyIndex] ?? '');
            if ($key !== '') {
                $mergedData[$key] = $row->toArray();
            }
        }

        foreach ($sheets as $sheet) {
            // Find header row in this sheet
            $headerIndex = 0;
            $sheetKeyIndex = false;
            foreach ($sheet->take(2) as $idx => $row) {
                $foundKey = $row->search(fn($h) => in_array($normalize($h), ['no', 'nourut', 'nik', 'nip']));
                if ($foundKey !== false) {
                    $headerIndex = $idx;
                    $sheetKeyIndex = $foundKey;
                    break;
                }
            }
            
            if ($sheetKeyIndex === false) continue;

            $headers = $sheet[$headerIndex];
            $newColumns = [];
            foreach ($headers as $idx => $header) {
                if ($idx === $sheetKeyIndex) continue;
                // If header already exists in primary sheet, we don't need to add it as a "new column" 
                // but we might want to update the data if it's different.
                // For simplicity, we only add columns that were NOT in the primary sheet.
                if (!in_array($header, $headerMap)) {
                    $newColumns[$idx] = count($headerMap);
                    $headerMap[] = $header;
                }
            }

            foreach ($sheet->slice($headerIndex + 1) as $row) {
                $key = (string) ($row[$sheetKeyIndex] ?? '');
                if (isset($mergedData[$key])) {
                    foreach ($newColumns as $oldIdx => $newIdx) {
                        $mergedData[$key][$newIdx] = $row[$oldIdx] ?? null;
                    }
                }
            }
        }

        return collect([collect($headerMap)])->concat(collect(array_values($mergedData))->map(fn($r) => collect($r)));
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

        $headerRow = $rows->shift(); // Row 1: Header
        
        $isGtkImport = str_contains($this->importerClass, 'GtkImporter');
        if ($isGtkImport && $rows->isNotEmpty()) {
            $rows->shift();
        }

        $columns = $this->importerClass::getColumns();
        
        // Try to map headers from Row 1
        $normalize = fn($s) => strtolower(preg_replace('/[^a-zA-Z0-0]/', '', (string)$s));
        
        $columnMap = [];
        $matchCount = 0;
        foreach ($columns as $column) {
            $label = $column->getLabel() ?? Str::title($column->getName());
            $normalizedLabel = $normalize($label);
            $normalizedName = $normalize($column->getName());
            
            $index = $headerRow->search(fn($h) => 
                $normalize($h) === $normalizedLabel || 
                $normalize($h) === $normalizedName
            );
            if ($index !== false) {
                $columnMap[$column->getName()] = $index;
                $matchCount++;
            }
        }

        // If very few matches were found, the first row might be a grouping header (Row 1: Group, Row 2: Header)
        // Check Row 2 if it exists and gives better matches
        if ($matchCount < 3 && $rows->isNotEmpty()) {
            $secondRow = $rows->shift();
            $secondRowMap = [];
            $secondMatchCount = 0;
            foreach ($columns as $column) {
                $label = $column->getLabel() ?? Str::title($column->getName());
                $normalizedLabel = $normalize($label);
                $normalizedName = $normalize($column->getName());

                $index = $secondRow->search(fn($h) => 
                    $normalize($h) === $normalizedLabel || 
                    $normalize($h) === $normalizedName
                );
                if ($index !== false) {
                    $secondRowMap[$column->getName()] = $index;
                    $secondMatchCount++;
                }
            }

            if ($secondMatchCount > $matchCount) {
                $headerRow = $secondRow;
                $columnMap = $secondRowMap;
                $matchCount = $secondMatchCount;
            } else {
                // If second row didn't help, put it back or keep skipping (it might be a sub-header)
                // But generally $rows already has the data now.
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

                // Instantiate importer
                $importer = new $this->importerClass($importMock, $importerColumnMap, []);
                
                // Process the row using __invoke
                // We pass the raw row data, and __invoke handles remapping via columnMap
                $importer($row->toArray());
                
                $successCount++;
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
