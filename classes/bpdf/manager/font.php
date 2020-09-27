<?php

class BPdf_Manager_Font
{
    protected $_bPdf = null;
    protected $_fonts = array();
    protected $_current_font = null;
    protected $_nb_fonts = 0;
    protected $_font_size = 12;

    public function __construct($bPdf)
    {
        $this->_bPdf = $bPdf;
    }

    public function setFontSize($size)
    {
        $this->_font_size = $size;
    }

    public function addFont($name)
    {
        $this->_nb_fonts++;

        $path = $this->_bPdf->path . '/../fonts';

        $font = new BPdf_Resource_Font($this->_bPdf, $this->_nb_fonts, $name, $path);
        $this->_fonts[$name] = $font;

        $current_page = $this->_bPdf->getPageManager()->getCurrentPage();
        $current_page->getPdfPageResources()->Font->F1 = $font->getPdfFont();

        $this->_current_font = $font;
    }


    public function getCurrentFont()
    {
        return $this->_current_font;

    }

    public function getStringWidth($text, $font_size = 12)
    {
        $codepoints = $this->utf8_to_codepoints($text);
        $cw = $this->_bPdf->getFontManager()->getCurrentFont()->getCw();
        $w = 0;

        foreach ($codepoints as $cp) {
            if (isset($cw[$cp])) {
                $w += $cw[$cp];
            }
        }

        $w = $w * $font_size;

        return $w / 1000;
    }

    public function utf8_to_codepoints($txt)
    {
        $l = strlen($txt);
        $out = array();
        for ($i = 0; $i < $l; ++$i) {
            $c = ord($txt{$i});
            // ASCII
            if ($c < 0x80) {
                $out[] = ord($txt{$i});
            } // Lost continuation byte
            else if ($c < 0xC0) {
                $out[] = 0xFFFD;
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
                    $out[] = 0xFFFD;
                    while (ord($txt{$i + 1}) >= 0x80 && ord($txt{$i + 1}) < 0xC0) {
                        ++$i;
                    }
                    continue;
                }

                $q = array($c);
                // Fetch rest of sequence
                while (ord($txt{$i + 1}) >= 0x80 && ord($txt{$i + 1}) < 0xC0) {
                    ++$i;
                    $q[] = ord($txt{$i});
                }

                // Check length
                if (count($q) != $s) {
                    $out[] = 0xFFFD;
                    continue;
                }

                switch ($s) {
                    case 2:
                        $cp = (($q[0] ^ 0xC0) << 6) | ($q[1] ^ 0x80);
                        // Overlong sequence
                        if ($cp < 0x80) {
                            $out[] = 0xFFFD;
                        } else {
                            $out[] = $cp;
                        }
                        break;

                    case 3:
                        $cp = (($q[0] ^ 0xE0) << 12) | (($q[1] ^ 0x80) << 6) | ($q[2] ^ 0x80);
                        // Overlong sequence
                        if ($cp < 0x800) {
                            $out[] = 0xFFFD;
                        } // Check for UTF-8 encoded surrogates (caused by a bad UTF-8 encoder)
                        else if ($c > 0xD800 && $c < 0xDFFF) {
                            $out[] = 0xFFFD;
                        } else {
                            $out[] = $cp;
                        }
                        break;

                    case 4:
                        $cp = (($q[0] ^ 0xF0) << 18) | (($q[1] ^ 0x80) << 12) | (($q[2] ^ 0x80) << 6) | ($q[3] ^ 0x80);
                        // Overlong sequence
                        if ($cp < 0x10000) {
                            $out[] = 0xFFFD;
                        } // Outside of the Unicode range
                        else if ($cp >= 0x10FFFF) {
                            $out[] = 0xFFFD;
                        } else {
                            $out[] = $cp;
                        }
                        break;
                }
            }
        }
        return $out;
    }

    public function getFontSize()
    {
        return $this->_font_size;
    }

    public function setFont($name)
    {

    }
}
