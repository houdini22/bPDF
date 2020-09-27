<?php

class BPdf_Object_Element_Name extends BPdf_Object_Element_Base
{
    protected $value;

    public function __construct($value)
    {
        $this->_value = (string)$value;
    }

    public function toString()
    {
        $value = self::escape($this->_value);
        $value = '/' . $value;
        return $value;
    }

    public static function escape($inStr)
    {
        $outStr = '';

        for ($count = 0; $count < strlen($inStr); $count++) {
            $nextCode = ord($inStr[$count]);

            switch ($inStr[$count]) {
                case '(':
                case ')':
                case '<':
                case '>':
                case '[':
                case ']':
                case '{':
                case '}':
                case '/':
                case '%':
                case '\\':
                case '#':
                    $outStr .= sprintf('#%02X', $nextCode);
                    break;

                default:
                    if ($nextCode >= 33 && $nextCode <= 126) {
                        $outStr .= $inStr[$count];
                    } else {
                        $outStr .= sprintf('#%02X', $nextCode);
                    }
            }

        }

        return $outStr;
    }
}
