<?php

class BPdf_Page
{
    protected $_bPdf = null;

    protected $_pdf_page = null;
    protected $_pdf_contents = null;
    protected $_pdf_resources = null;

    public function addToContent($val)
    {
        $this->_pdf_contents->addToStream($val);
    }

    public function getPdfPageResources()
    {
        return $this->_pdf_resources;
    }

    public function getPdfPageContents()
    {
        return $this->_pdf_contents;
    }

    public function __construct($bPdf)
    {
        $this->_bPdf = $bPdf;

        $this->_pdf_page = $this->_bPdf->getFactory()->newObject();
        $this->_pdf_contents = $this->_bPdf->getFactory()->newStreamObject();
        $this->_pdf_resources = $this->_bPdf->getFactory()->newObject();

        $this->_pdf_page->Resources = $this->_pdf_resources;

        $this->_pdf_page->Type = new BPdf_Object_Element_Name('Page');
        $this->_pdf_page->Parent = $this->_bPdf->getPdfPages();
        $this->_pdf_page->Contents = $this->_pdf_contents;

        $this->_pdf_resources->Procset = $this->_bPdf->getProcset();
        $this->_pdf_resources->XObject = new BPdf_Object_Element_Dictionary();
        $this->_pdf_resources->Font = new BPdf_Object_Element_Dictionary();
    }

    public function getPdfPage()
    {
        return $this->_pdf_page;
    }
}