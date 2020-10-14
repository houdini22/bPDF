<?php

$text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam luctus eros et blandit consequat. Integer consequat libero lacus, eget iaculis lectus vehicula in. Morbi ut urna dignissim, maximus ex sodales, volutpat nulla. Aenean tempor tellus et dictum cursus. Suspendisse finibus non nulla in maximus. Vestibulum molestie pulvinar fermentum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Etiam sed sem lectus. Praesent vel maximus ligula. Vivamus accumsan feugiat eros vitae congue. Fusce fringilla, sem nec elementum volutpat, velit arcu malesuada leo, hendrerit venenatis eros quam et nibh. Suspendisse ultrices molestie neque, sit amet cursus nulla tristique nec. Maecenas aliquam fringilla urna. Pellentesque malesuada eros sed tempor tincidunt. Donec mattis ac ante scelerisque tempor. Sed tincidunt gravida ipsum, sit amet iaculis lorem molestie eget. ';

include "./classes/bpdf.php";

BPdf::registerAutoload();

$pdf = new Bpdf();
$pdf->getFontManager()->addFont('Helvetica');

$toolkit = new BPdf_Toolkit($pdf);
$toolkit->addText(50, 50, 'bPDF library.', 37);
$toolkit->drawLine(50, 90, 266, 90);
$toolkit->addTextWrap(50, 150, 510, $text);
$toolkit->addImageJpg(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'ezoteriusz.jpg', 200, 400, 210, 400);

$parser = new BPdf_Parser($pdf);
$parser->saveToFile(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test.pdf');

