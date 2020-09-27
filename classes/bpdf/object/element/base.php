<?php

abstract class BPdf_Object_Element_Base
{
    protected $_value = null;

    abstract function toString();
}
