<?php

class BPdf_Object_Element_Dictionary
{
    protected $_items = array();

    public function __construct()
    {
    }

    public function __set($name, $value)
    {
        $this->_items[$name] = $value;
    }

    public function __get($name)
    {
        if ($name == "items") {
            return $this->_items;
        } else {
            return $this->_items[$name];
        }
    }


    public function toString()
    {
        $outStr = "";
        $outStr .= "<<\n";

        foreach ($this->_items as $name => $value) {
            $nameObj = new BPdf_Object_Element_Name($name);
            $outStr .= $nameObj->toString() . " " . $value->toString() . "\n";
        }

        $outStr .= ">>\n";
        return $outStr;
    }
}
