<?php

namespace App\Jobs;

use App\Filament\Actions\ExcelImportAction;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Log;

class ImportExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;
    use \Illuminate\Queue\SerializesModels;

    protected string $filePath;
    protected string $importerClass;
    protected array $options;
    protected ?int $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath, string $importerClass, array $options = [], ?int $userId = null)
    {
        $this->filePath = $filePath;
        $this->importerClass = $importerClass;
        $this->options = $options;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Replicate the logic from ExcelImportAction::processImport()
        $sheets = Excel::toCollection(new class implements \Maatwebsite\Excel\Concerns\ToCollection {
            public function collection(Collection $rows) {}
        }, $this->filePath);

        if ($sheets->isEmpty()) {
            $this->notifyFailure('Berkas Excel kosong atau tidak terbaca.');
            return;
        }

        // Merge sheets if needed
        if ($sheets->count() > 1) {
            $rows = $this->mergeSheets($sheets);
        } else {
            $rows = $sheets->first();
        }

        if (!$rows || $rows->isEmpty()) {
            $this->notifyFailure('Tidak ada data yang terbaca dari berkas Excel.');
            return;
        }

        $headerRow = $rows->shift(); // first row is header
        // Detect instruction row and skip
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

        $columns = call_user_func([$this->importerClass, 'getColumns']);
        $normalize = fn($s) => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string) $s));
        $columnMap = [];
        $matchCount = 0;
        foreach ($columns as $column) {
            $label = $column->getLabel() ?? Str::title($column->getName());
            $normalizedLabel = $normalize($label);
            $normalizedName = $normalize($column->getName());
            $normalizedGuesses = collect($column->getGuesses())->map($normalize);
            $index = $headerRow->search(fn($h) => $normalize($h) === $normalizedLabel || $normalize($h) === $normalizedName || $normalizedGuesses->contains($normalize($h)));
            if ($index !== false) {
                $columnMap[$column->getName()] = $index;
                $matchCount++;
            }
        }

        // Bad match handling (same as original)
        $totalColumns = count($columns);
        $isBadMatch = $totalColumns > 0 && ($matchCount < min(2, $totalColumns) || $matchCount < ($totalColumns / 3));
        if ($isBadMatch && $rows->isNotEmpty()) {
            $secondRow = $rows->first();
            $secondRowMap = [];
            $secondMatchCount = 0;
            foreach ($columns as $column) {
                $label = $column->getLabel() ?? Str::title($column->getName());
                $normalizedLabel = $normalize($label);
                $normalizedName = $normalize($column->getName());
                $normalizedGuesses = collect($column->getGuesses())->map($normalize);
                $index = $secondRow->search(fn($h) => $normalize($h) === $normalizedLabel || $normalize($h) === $normalizedName || $normalizedGuesses->contains($normalize($h)));
                if ($index !== false) {
                    $secondRowMap[$column->getName()] = $index;
                    $secondMatchCount++;
                }
            }
            if ($secondMatchCount > $matchCount) {
                $headerRow = $rows->shift();
                $columnMap = $secondRowMap;
                $matchCount = $secondMatchCount;
            }
        }

        $successCount = 0;
        $errorCount = 0;
        $failureDetails = [];
        foreach ($rows as $rowIndex => $row) {
            if ($row->filter()->isEmpty()) continue;
            try {
                $rowData = [];
                foreach ($columnMap as $name => $idx) {
                    $rowData[$name] = $row[$idx] ?? null;
                }
                // Prepare mock import model
                $importMock = new \Filament\Actions\Imports\Models\Import();
                if ($this->userId) {
                    $user = \App\Models\User::find($this->userId);
                    if ($user) $importMock->setRelation('user', $user);
                }
                $importer = new $this->importerClass($importMock, $columnMap, $this->options);
                $importer($row->toArray());
                if ($importer->getRecord()?->exists) {
                    $successCount++;
                }
            } catch (ValidationException $e) {
                $errorCount++;
                $errors = collect($e->errors())->flatten()->toArray();
                $errorMsg = implode(', ', $errors);
                $failureDetails[] = "Baris " . ($rowIndex + 2) . ": " . $errorMsg;
                Log::error('Validation error at row ' . ($rowIndex + 2) . ': ' . $errorMsg);
            } catch (Exception $e) {
                $errorCount++;
                $msg = $e->getMessage();
                $failureDetails[] = "Baris " . ($rowIndex + 2) . ": " . $msg;
                Log::error('Import error at row ' . ($rowIndex + 2) . ': ' . $msg);
            }
        }

        // Build notification body
        $body = "Berhasil mengimpor {$successCount} baris.";
        if ($errorCount > 0) {
            $body .= " <br><span class='text-danger'>Gagal mengimpor {$errorCount} baris.</span>";
            $body .= "<div class='mt-2 text-xs text-gray-600 dark:text-gray-400 font-mono space-y-1'>";
            foreach (array_slice($failureDetails, 0, 5) as $detail) {
                $body .= "<div>• {$detail}</div>";
            }
            if (count($failureDetails) > 5) {
                $body .= "<div>...dan " . (count($failureDetails) - 5) . " kesalahan lainnya.</div>";
            }
            $body .= "</div>";
        }

        $notification = Notification::make()
            ->title('Impor Selesai')
            ->success()
            ->persistent($errorCount > 0)
            ->body(new HtmlString($body));
        if ($this->userId) {
            $notification->toUser($this->userId);
        } else {
            $notification->send();
        }
    }

    protected function mergeSheets(Collection $sheets): Collection
    {
        // Borrowed from ExcelImportAction::mergeSheets (unchanged)
        $primarySheet = $sheets->shift();
        $normalize = fn($s) => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string) $s));
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
            $key = trim((string) ($row[$keyIndex] ?? ''));
            if ($key !== '' && is_numeric($key) && (int) $key > 0) {
                $mergedData[$key] = $row->toArray();
            }
        }
        foreach ($sheets as $sheet) {
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
                $alreadyExists = collect($headerMap)->contains(fn($h) => $normalize($h) === $normalize($header));
                if (! $alreadyExists) {
                    $newColumns[$idx] = count($headerMap);
                    $headerMap[] = $header;
                }
            }
            foreach ($sheet->slice($headerIndex + 1) as $row) {
                $key = trim((string) ($row[$sheetKeyIndex] ?? ''));
                if (isset($mergedData[$key])) {
                    foreach ($newColumns as $oldIdx => $newIdx) {
                        $mergedData[$key][$newIdx] = $row[$oldIdx] ?? null;
                    }
                }
            }
        }
        return collect([collect($headerMap)])->concat(collect(array_values($mergedData))->map(fn($r) => collect($r)));
    }

    protected function notifyFailure(string $message): void
    {
        $notification = Notification::make()
            ->title('Impor Gagal')
            ->danger()
            ->body($message);
        if ($this->userId) {
            $notification->toUser($this->userId);
        } else {
            $notification->send();
        }
    }
}
