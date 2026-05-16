<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

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

$dompdf->loadHtml($html);
$dompdf->setPaper('a4', 'portrait');
$dompdf->render();

file_put_contents('test_mixed.pdf', $dompdf->output());
echo "PDF generated: test_mixed.pdf\n";
