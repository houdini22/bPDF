<?php

class BPdf_Object_Element_Array extends BPdf_Object_Element_Base
{
    public $_items = array();

    public function __set($name, $value)
    {
        $this->_items[] = $value;
    }

    public function __construct($value = null)
    {
        if (!is_array($value)) {
            return;
        }

        foreach ($value as $k => $elem) {
            if (!$elem instanceof BPdf_Object_Element_Base) {
                throw new Exception('Element in array must be base element');
            }
            $this->_items[] = $elem;
        }
    }

    public function toString($factory = null)
    {
        $buffer = "";
        $buffer .= "[";

        foreach ($this->_items as $k => $el) {
            $buffer .= $el->toString() . " ";
        }

        if (count($this->_items)) {
            $buffer = substr($buffer, 0, strlen($buffer) - 1);
        }

        $buffer .= "]";
        return $buffer;
    }

    public function getType()
    {
        return BaseElement::TYPE_ARRAY;
    }
}
