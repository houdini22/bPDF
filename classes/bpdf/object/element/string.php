<?php

class BPdf_Object_Element_String extends BPdf_Object_Element_Base
{
    protected $_value;

    public function __construct($value)
    {
        settype($value, 'string');
        if (strpos($value, "\x00") !== false) {
            throw new Exception('Null character is not allowed');
        }
        $this->_value = (string)$value;
    }

    public function toString()
    {
        $value = self::escape($this->_value);
        $value = '(' . $value . ')';
        return $value;
    }

    public static function escape($inStr)
    {
        $outStr = '';
        $lastNL = 0;

        for ($count = 0; $count < strlen($inStr); $count++) {
            if (strlen($outStr) - $lastNL > 128) {
                $outStr .= "\\\n";
                $lastNL = strlen($outStr);
            }

            $nextCode = ord($inStr[$count]);
            switch ($nextCode) {
                case 10:
                    $outStr .= '\\n';
                    break;

                case 13:
                    $outStr .= '\\r';
                    break;

                case 9:
                    $outStr .= '\\t';
                    break;

                case 8:
                    $outStr .= '\\b';
                    break;

                case 12:
                    $outStr .= '\\f';
                    break;

                case 40:
                    $outStr .= '\\(';
                    break;

                case 41:
                    $outStr .= '\\)';
                    break;

                case 92:
                    $outStr .= '\\\\';
                    break;

                default:
                    $outStr .= $inStr[$count];
                    break;
            }
        }

        return $outStr;
    }
}