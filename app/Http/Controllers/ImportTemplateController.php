<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Style\Style;
use Illuminate\Support\Str;

class ImportTemplateController extends Controller
{
    public function download(string $importerName)
    {
        $importerClass = "App\\Filament\\Imports\\" . Str::studly($importerName) . "Importer";

        if (!class_exists($importerClass)) {
            abort(404, "Importer not found: {$importerClass}");
        }

        $fileName = Str::kebab($importerName) . '-template.xlsx';

        return new StreamedResponse(function () use ($importerClass) {
            $writer = new Writer();
            $writer->openToFile('php://output');

            // Set style for Text format
            $textStyle = (new Style())->setFormat('@');

            // Get columns from importer
            $columns = $importerClass::getColumns();
            
            // Header Row
            $headerCells = [];
            foreach ($columns as $column) {
                $headerCells[] = Cell::fromValue($column->getLabel() ?? Str::title($column->getName()), $textStyle);
            }
            $writer->addRow(new Row($headerCells));

            // Example Row (if columns have examples)
            $exampleCells = [];
            foreach ($columns as $column) {
                $examples = $column->getExamples();
                $val = $examples[0] ?? '';
                
                // Force string type and apply text style
                $exampleCells[] = Cell::fromValue((string) $val, $textStyle);
            }
            if (collect($exampleCells)->filter(fn($c) => !empty($c->getValue()))->isNotEmpty()) {
                $writer->addRow(new Row($exampleCells));
            }

            $writer->close();
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
