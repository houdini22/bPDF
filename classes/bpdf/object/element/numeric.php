<?php

class BPdf_Object_Element_Numeric extends BPdf_Object_Element_Base
{
    protected $_value;

    public function __construct($value)
    {
        if (!is_numeric($value)) {
            return new Exception('value must be a number');
        }

        $this->_value = $value;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function toString($factory = null)
    {
        if (is_integer($this->_value)) {
            return (string)$this->_value;
        }

        $prec = 0;
        $v = $this->_value;
        while (abs(floor($v) - $v) > 1e-10) {
            $prec++;
            $v *= 10;
        }

        return sprintf("%.{$prec}F", $this->_value);
    }
}