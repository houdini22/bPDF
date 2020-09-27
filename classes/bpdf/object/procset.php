<?php

class BPdf_Object_Procset extends BPdf_Object_Base
{
    public function __construct($obj_num)
    {
        $this->_obj_num = $obj_num;
        $this->_value = new BPdf_Object_Element_Array();
    }

    public function __set($name, $value)
    {
        $this->_value->items = $value;
    }

    public function dump()
    {
        $str = "";
        $str .= $this->_obj_num . " 0 obj\n";
        $str .= "<<\n";

        $str .= $this->_value->toString() . "\n";

        $str .= ">>\n";
        $str .= "endobj\n\n";

        return $str;
    }
}
