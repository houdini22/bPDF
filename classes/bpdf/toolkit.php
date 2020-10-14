<?php

class BPdf_Toolkit
{
    protected $_bPdf = null;
    protected $_images = array();
    protected $_tools_factory = null;
    protected $_nb_images = 0;

    protected $_x = 0;
    protected $_y = 0;

    public function __construct($bPdf)
    {
        $this->_bPdf = $bPdf;
    }

    public function getPositionInPage($x, $y, $obj_height = 0)
    {
        $page_size = $this->_bPdf->getPageManager()->getPageSize();
        $new_y = $page_size['y'] - $y - $obj_height;
        return array('x' => $x, 'y' => $new_y);
    }

    public function drawLine($x1, $y1, $x2, $y2)
    {
        $current_page = $this->_bPdf->getPageManager()->getCurrentPage();

        $new1 = $this->getPositionInPage($x1, $y1);
        $new2 = $this->getPositionInPage($x2, $y2);

        $buffer = "";
        $buffer .= $new1['x'] . " " . $new1['y'] . " m " . $new2['x'] . " " . $new2['y'] . " l S";

        $current_page->addToContent($buffer);
    }

    public function addText($x, $y, $text, $size = 12, $word_spacing = 0)
    {
        $current_font = $this->_bPdf->getFontManager()->getCurrentFont();
        $current_page = $this->_bPdf->getPageManager()->getCurrentPage();

        $text = $this->utf8ToUtf16be($text, false);
        $text = $this->filterText($text);
        $position = $this->getPositionInPage($x, $y, $size);

        $buffer = "";
        $buffer .= "BT\n";
        $buffer .= "/{$current_font->font_label} " . $size . " Tf\n";
        $buffer .= $position['x'] . " " . $position['y'] . " Td\n";
        //$buffer .= "30 Tw\n";
        $buffer .= "(" . $text . ") Tj\n";
        $buffer .= "ET\n";

        $current_page->addToContent($buffer);
    }

    public function addTextWrap($x, $y, $width, $text, $size = 12, $justify = TRUE)
    {
        $words = explode(' ', $text);
        $line = '';
        $i = 0;

        if ($justify)
            $space_width = $this->_bPdf->getFontManager()->getStringWidth(' ', $size);

        foreach ($words as $key => $w) {

            $line .= $words[$key] . ' ';
            if (isset($words[$key + 1]))
                $text_width = $this->_bPdf->getFontManager()->getStringWidth($line . ' ' . $words[$key + 1], $size);
            else {
                $text_width = $this->_bPdf->getFontManager()->getStringWidth($line, $size);
                $last_line = TRUE;
            }

            if ($text_width > $width || isset($last_line)) {
                if ($justify) {
                    $line_width = $this->_bPdf->getFontManager()->getStringWidth($line, $size);
                    $line_without_spaces = $line_width - ($space_width * (count(explode(' ', $line)) - 1));
                    $spaces_width = $line_width - $line_without_spaces;
                    $word_spacing = ($line_width - $width) / $spaces_width;
                    $word_spacing = round($word_spacing, 2);
                }
                $this->addText($x, $y + (($size + 3) * $i), $line, $size, $justify ? $word_spacing : 0);
                $line = '';
                $i++;
            }
        }
    }

    public function cell($x, $y, $width, $height, $text, $size, $center = false, $center_h = true)
    {
        $text_width = $this->_bPdf->getFontManager()->getStringWidth($text, $size);

        if ($text_width > $width) {
            while ($text_width > $width) {
                $size = $size - 1;
                $text_width = $this->_bPdf->getFontManager()->getStringWidth($text, $size);
            }

        }

        if ($center_h) {
            $half = $size / 2;
            $halfHeight = $height / 2;
            $y = $y + (($halfHeight - $half) / 2);
        }

        if ($center) {
            $move = $width - $text_width;
            $move = $move / 2;
            $x = $x + $move;
        }

        $this->addText($x, $y, $text, $size);
    }

    public function textarea($x, $y, $width, $height, $text, $size)
    {
        $textarea_h = $y;

        $words = explode(' ', $text);
        $lines = array();

        $wordsCount = count($words);
        $line = "";

        for ($i = 0; $i <= $wordsCount; $i++) {
            $line .= $words[$i] . ' ';
            $string_width_a = $this->_bPdf->getFontManager()->getStringWidth($line . $words[$i + 1], $size);

            if ($string_width_a > $width) {
                $lines[] = $line;
                $line = '';
            }

            if ($i == $wordsCount) {
                $lines[] = $line;

                $wordsCount2 = count($lines);
                $height2 = $wordsCount2 * ($size + (($size / ($size / 2)) * 0.5));

                if ($height2 > $height) {
                    $line = '';
                    $lines = array();
                    $size = $size - 1;
                    $i = -1;
                }
            }
        }

        foreach ($lines as $line) {
            $this->Cell($x, $y, 1000, 1000, $line, $size, false, false);
            $y = $y + $size + (($size / ($size / 2)) * 0.5);
        }
    }

    public function countImages()
    {
        return $this->_nb_images;
    }

    public function addImageJpg($path, $x, $y, $w = 0, $h = 0)
    {
        if (!file_exists($path)) {
            throw new Exception("File doesn't exists.");
        }

        $image = new BPdf_Resource_Image_Jpeg($this->_bPdf, $this->countImages(), $path);

        $current_page = $this->_bPdf->getPageManager()->getCurrentPage();
        $this->_nb_images++;

        $page_size = $this->_bPdf->getPageManager()->getPageSize();

        if ($w == 0 && $h == 0) {
            if ($image->getWidth() > $page_size['x'] && $image->getHeight() > $page_size['y']) {
                $w = $page_size['x'];
                $ratio = $page_size['x'] / $image->getWidth();
                $h = $ratio * $image->getHeight();
            } else {
                $w = $image->getWidth();
                $h = $image->getHeight();
            }
        }

        $position_in_page = $this->getPositionInPage($x, $y, $h);

        $x = $position_in_page['x'];
        $y = $position_in_page['y'];

        $buffer = "";
        $buffer .= "q\n";
        $buffer .= $w . " 0 0 " . $h . " " . $x . " " . $y . " cm\n";
        $buffer .= "/" . $image->getImageLabel() . " Do\n";
        $buffer .= "Q\n";

        $pdf_image_name = $image->getImageLabel();

        $current_page->getPdfPageResources()->XObject->$pdf_image_name = $image->getResource();
        $current_page->addToContent($buffer);
    }

    protected function utf8ToUtf16be(&$txt, $bom = true)
    {
        $l = strlen($txt);
        $out = $bom ? "\xFE\xFF" : '';
        for ($i = 0; $i < $l; ++$i) {
            if ($txt{$i} == "\n")
                continue;
            $c = ord($txt{$i});
            // ASCII
            if ($c < 0x80) {
                $out .= "\x00" . $txt{$i};
            } // Lost continuation byte
            else if ($c < 0xC0) {
                $out .= "\xFF\xFD";
                continue;
            } // Multibyte sequence leading byte
            else {
                if ($c < 0xE0) {
                    $s = 2;
                } else if ($c < 0xF0) {
                    $s = 3;
                } else if ($c < 0xF8) {
                    $s = 4;
                } // 5/6 byte sequences not possible for Unicode.
                else {
                    $out .= "\xFF\xFD";
                    while (ord($txt{$i + 1}) >= 0x80 && ord($txt{$i + 1}) < 0xC0) {
                        ++$i;
                    }
                    continue;
                }

                $q = array($c);
                // Fetch rest of sequence
                while (isset($txt{$i + 1}) && ord($txt{$i + 1}) >= 0x80 && ord($txt{$i + 1}) < 0xC0) {
                    ++$i;
                    $q[] = ord($txt{$i});
                }

                // Check length
                if (count($q) != $s) {
                    $out .= "\xFF\xFD";
                    continue;
                }

                switch ($s) {
                    case 2:
                        $cp = (($q[0] ^ 0xC0) << 6) | ($q[1] ^ 0x80);
                        // Overlong sequence
                        if ($cp < 0x80) {
                            $out .= "\xFF\xFD";
                        } else {
                            $out .= chr($cp >> 8);
                            $out .= chr($cp & 0xFF);
                        }
                        break;

                    case 3:
                        $cp = (($q[0] ^ 0xE0) << 12) | (($q[1] ^ 0x80) << 6) | ($q[2] ^ 0x80);
                        // Overlong sequence
                        if ($cp < 0x800) {
                            $out .= "\xFF\xFD";
                        } // Check for UTF-8 encoded surrogates (caused by a bad UTF-8 encoder)
                        else if ($c > 0xD800 && $c < 0xDFFF) {
                            $out .= "\xFF\xFD";
                        } else {
                            $out .= chr($cp >> 8);
                            $out .= chr($cp & 0xFF);
                        }
                        break;

                    case 4:
                        $cp = (($q[0] ^ 0xF0) << 18) | (($q[1] ^ 0x80) << 12) | (($q[2] ^ 0x80) << 6) | ($q[3] ^ 0x80);
                        // Overlong sequence
                        if ($cp < 0x10000) {
                            $out .= "\xFF\xFD";
                        } // Outside of the Unicode range
                        else if ($cp >= 0x10FFFF) {
                            $out .= "\xFF\xFD";
                        } else {
                            // Use surrogates
                            $cp -= 0x10000;
                            $s1 = 0xD800 | ($cp >> 10);
                            $s2 = 0xDC00 | ($cp & 0x3FF);

                            $out .= chr($s1 >> 8);
                            $out .= chr($s1 & 0xFF);
                            $out .= chr($s2 >> 8);
                            $out .= chr($s2 & 0xFF);
                        }
                        break;
                }
            }
        }
        return $out;
    }

    protected function filterText($text)
    {
        $text = str_replace('\\', '\\\\', $text);
        $text = str_replace('(', '\(', $text);
        $text = str_replace(')', '\)', $text);
        $text = str_replace('&lt;', '<', $text);
        $text = str_replace('&gt;', '>', $text);
        $text = str_replace('&#039;', '\'', $text);
        $text = str_replace('&quot;', '"', $text);
        $text = str_replace('&amp;', '&', $text);
        $text = str_replace("/n", '', $text);
        $text = str_replace("/r", '', $text);
        return $text;
    }
}
