<?php

abstract class BPdf_Object_Base
{
    protected $_obj_num = null;
    protected $_value = null;

    public function toString()
    {
        return $this->_obj_num . " 0 R";
    }

    abstract function dump();
}
