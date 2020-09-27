<?php

class BPdf_Object_Stream extends BPdf_Object_Base
{
    protected $_pdf_dictionary = null;        // Dictionary
    protected $_pdf_stream = null;

    public function __set($name, $value)
    {
        if ($name == "stream") {
            $this->_pdf_stream->addToStream($value);
        } else {
            $this->_pdf_dictionary->$name = $value;
        }
    }

    public function addToStream($value)
    {
        $this->_pdf_stream->addToStream($value . "\n");
    }

    public function __construct($val, $obj_num)
    {
        $this->_obj_num = $obj_num;
        $this->_pdf_stream = new BPdf_Object_Element_Stream($val);
        $this->_pdf_dictionary = new BPdf_Object_Element_Dictionary();
        $this->_pdf_dictionary->Length = new BPdf_Object_Element_Numeric(0);
    }

    public function dump()
    {
        $this->_pdf_dictionary->Length = new BPdf_Object_Element_Numeric($this->_pdf_stream->getStreamLenght());

        $str = "";
        $str .= $this->_obj_num . " 0 obj\n";
        $str .= "<<\n";

        foreach ($this->_pdf_dictionary->items as $name => $var) {
            $nameObj = new BPdf_Object_Element_Name($name);
            $str .= $nameObj->toString() . " " . $var->toString() . "\n";
        }

        $str .= ">>\n";
        if ($this->_pdf_stream->getStreamLenght() != 0) {
            $str .= $this->_pdf_stream->toString() . "\n";
        }
        $str .= "endobj\n\n";

        return $str;
    }
}
