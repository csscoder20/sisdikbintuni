<?php

namespace App\Filament\Traits;

use Filament\Actions\Action;
use Filament\Actions\ImportAction;
use Illuminate\Support\Str;

trait HasImportTemplate
{
    public function downloadExampleAction(): Action
    {
        return Action::make('downloadExample')
            ->action(function () {
                $importerClass = null;

                // Try to find the importer from header actions
                foreach ($this->getCachedHeaderActions() as $action) {
                    if ($action instanceof ImportAction || $action instanceof \App\Filament\Actions\ExcelImportAction) {
                        $importerClass = $action->getImporter();
                        break;
                    }
                }

                if (! $importerClass) {
                    return;
                }

                $importerName = Str::of(class_basename($importerClass))
                    ->replaceLast('Importer', '')
                    ->kebab()
                    ->toString();

                return redirect()->route('import-template.download', ['importer' => $importerName]);
            });
    }
}
