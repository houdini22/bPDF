<?php

include "./classes/bpdf.php";

BPdf::registerAutoload();

$pdf = new Bpdf();
$pdf->getFontManager()->addFont('Helvetica');

$toolkit = new BPdf_Toolkit($pdf);
$toolkit->addText(0, 0, 'bPDF library.', 37);

$parser = new BPdf_Parser($pdf);
$parser->saveToFile(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test.pdf');

