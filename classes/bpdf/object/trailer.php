<?php

class BPdf_Object_Trailer extends BPdf_Object_Base
{
    protected $_bPdf = null;

    public function __construct($bpdf)
    {
        $this->_bPdf = $bpdf;
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
        $nb_objects = $this->_bPdf->getFactory()->getNbObjects();
        $nb_objects++;

        $str = "";
        $str .= "trailer\n";
        $str .= $this->_value->toString();

        return $str;
    }
}
