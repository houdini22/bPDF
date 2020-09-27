<?php

class BPdf_Manager_Page
{
    protected $_bPdf = null;
    protected $_pages = array();
    protected $_current_page = null;

    protected $_page_size_x = 595;
    protected $_page_size_y = 841;

    protected $_pdf_pages = null;

    public function __construct($bPdf)
    {
        $this->_bPdf = $bPdf;

        $this->_pdf_pages = $bPdf->getPdfPages();
        $this->_pdf_pages->Type = new BPdf_Object_Element_Name('Pages');
        $this->_pdf_pages->Count = new BPdf_Object_Element_Numeric(0);
        $this->_pdf_pages->Kids = new BPdf_Object_Element_Array();
        $this->_pdf_pages->MediaBox = new BPdf_Object_Element_Array();
        $this->_pdf_pages->MediaBox->items = new BPdf_Object_Element_Numeric(0);
        $this->_pdf_pages->MediaBox->items = new BPdf_Object_Element_Numeric(0);
        $this->_pdf_pages->MediaBox->items = new BPdf_Object_Element_Numeric($this->_page_size_x);
        $this->_pdf_pages->MediaBox->items = new BPdf_Object_Element_Numeric($this->_page_size_y);
    }

    public function addPage()
    {
        $page = new BPdf_Page($this->_bPdf);
        $this->_pages[] = $page;
        $this->_current_page = $page;
        $this->_pdf_pages->Kids->items = $page->getPdfPage();
        $this->_pdf_pages->Count = new BPdf_Object_Element_Numeric($this->countPages());
        return $page;
    }

    public function getPages()
    {
        return $this->_pages;
    }

    public function countPages()
    {
        return count($this->_pages);
    }

    public function getCurrentPage()
    {
        return $this->_current_page;
    }

    public function getPageSize()
    {
        $x = $this->_page_size_x;
        $y = $this->_page_size_y;

        $size['x'] = $x;
        $size['y'] = $y;

        return $size;
    }

    public function setPageSize($x, $y)
    {
        $this->_page_size_x = $x;
        $this->_page_size_y = $y;
    }
}