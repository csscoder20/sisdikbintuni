<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::get('/test-pdf-mixed', function () {
    $html = '
    <html>
    <head>
        <style>
            @page { size: a4 portrait; margin: 1cm; }
            @page landscape { size: a4 landscape; margin: 1cm; }
            .portrait { page-break-after: always; }
            .landscape { page: landscape; }
        </style>
    </head>
    <body>
        <div class="portrait">This is portrait.</div>
        <div class="landscape">This should be landscape.</div>
    </body>
    </html>
    ';
    
    return Pdf::loadHTML($html)->stream('test.pdf');
});
