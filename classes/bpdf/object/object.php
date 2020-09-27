<?php

class BPdf_Object_Object extends BPdf_Object_Base
{
    public function __construct($obj_num)
    {
        $this->_obj_num = $obj_num;
        $this->_value = new BPdf_Object_Element_Dictionary();
    }

    public function __set($name, $value)
    {
        $this->_value->$name = $value;
    }

    public function __get($value)
    {
        return $this->_value->$value;
    }

    public function dump()
    {
        $str = "";
        $str .= $this->_obj_num . " 0 obj\n";
        $str .= $this->_value->toString();
        $str .= "endobj\n\n";

        return $str;
    }
}