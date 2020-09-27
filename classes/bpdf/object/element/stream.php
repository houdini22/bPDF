<?php

class BPdf_Object_Element_Stream extends BPdf_Object_Element_Base
{
    protected $_value;

    public function __construct($value = null)
    {
        $this->_value = $value;
    }

    public function getStreamLenght()
    {
        return strlen($this->_value);
    }

    public function addToStream($val)
    {
        $this->_value = $this->_value . $val;
    }

    public function toString()
    {
        $value = "stream\n" . $this->_value . "\nendstream";
        return $value;
    }
}
