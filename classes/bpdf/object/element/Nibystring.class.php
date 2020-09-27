<?php

class NibystringElement extends BaseElement
{
    protected $value;

    public function __construct($value)
    {
        $this->_value = (string)$value;
    }

    public function toString()
    {
        //$value = self::escape($this->_value);
        //$value = '/' . $value;
        return $this->_value;
    }
}
