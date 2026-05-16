<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$action = \App\Filament\Actions\ExcelImportAction::make('test')
    ->form([\Filament\Forms\Components\FileUpload::make('file')->live()])
    ->modalSubmitAction(fn ($action) => $action->hidden(function (\Filament\Actions\Action $parentAction) { 
        return empty($parentAction->getForm('form')->getComponent('file')->getState()); 
    }));
dump($action->getModalSubmitAction()->isHidden());
