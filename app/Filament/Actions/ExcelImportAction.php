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
        $rows = Excel::toCollection(new class implements \Maatwebsite\Excel\Concerns\ToCollection {
            public function collection(Collection $rows) {}
        }, $filePath)->first();

        if (!$rows || $rows->isEmpty()) {
            throw new \Exception('Berkas Excel kosong atau tidak terbaca.');
        }

        $headerRow = $rows->shift(); // Initial assumption: Row 1 is header
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
                
                if (count($failureDetails) < 10) {
                    $failureDetails[] = "Baris " . ($rowIndex + 2) . ": " . $errorMsg;
                }
                
                \Log::error("Validation error at row " . ($rowIndex + 2) . ": " . $errorMsg);
            } catch (\Exception $e) {
                $errorCount++;
                $msg = $e->getMessage();
                if (count($failureDetails) < 10) {
                    $failureDetails[] = "Baris " . ($rowIndex + 2) . ": " . $msg;
                }
                \Log::error("Import error at row " . ($rowIndex + 2) . ": " . $msg);
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
