<?php

class BPdf_Object_Element_Boolean extends BPdf_Object_Element_Base
{
    protected $_value;

    public function __construct($value)
    {
        if (!is_bool($value)) {
            return new Exception('Value must be a boolean type');
        }
        $this->_value = $value;
    }

    public function toString($factory = null)
    {
        return $this->_value ? 'true' : 'false';
    }
}