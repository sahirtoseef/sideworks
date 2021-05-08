<?php
require_once 'autoload.inc.php'; 
 
// Reference the Dompdf namespace 
use Dompdf\Dompdf; 
 use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Courier');
$dompdf = new Dompdf($options);
// Load HTML content
$content = file_get_contents('https://beta.prasanthinilayam.in/print/?type=booking&id=$F$BGQBKAxN6i2g$E');


// Load content from html file 
$dompdf->loadHtml($content, 'UTF-8'); 
 
// (Optional) Setup the paper size and orientation 
$dompdf->setPaper('A4', 'portrait'); 
 
// Render the HTML as PDF 
$dompdf->render(); 
 
$dompdf->set_base_path('/css/');
// Output the generated PDF (1 = download and 0 = preview) 
$dompdf->stream("codexworld", array("Attachment" => 0));