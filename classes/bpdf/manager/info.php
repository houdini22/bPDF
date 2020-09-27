<?php

class BPdf_Manager_Info
{
    protected $_bPdf = null;
    protected $_pdf_info = null;

    public function __construct($bPdf)
    {
        $this->_bPdf = $bPdf;
        $this->_pdf_info = $bPdf->getPdfInfo();
    }

    public function setCreator($val)
    {
        $this->_pdf_info->Creator = new BPdf_Object_Element_String($val);
    }

    public function setTitle($val)
    {
        $this->_pdf_info->Title = new BPdf_Object_Element_String($val);
    }

    public function setAuthor($val)
    {
        $this->_pdf_info->Author = new BPdf_Object_Element_String($val);
    }

    public function setSubject($val)
    {
        $this->_pdf_info->Subject = new BPdf_Object_Element_String($val);
    }

    public function setProducer($val)
    {
        $this->_pdf_info->Producer = new BPdf_Object_Element_String($val);
    }

    public function addKeywords()
    {
        if (!count(func_get_args())) {
            return;
        }

        foreach (func_get_args() as $keyword) {
            $val .= $keyword . " ";
        }
        $val = substr($val, 0, strlen($val) - 1);
        $this->_pdf_info->Keywords = new BPdf_Object_Element_String($val);
    }
}
