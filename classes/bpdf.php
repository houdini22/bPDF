<?php

class BPdf
{
    protected $_factory = null;
    protected $_parser = null;

    protected $_info_manager = null;
    protected $_page_manager = null;
    protected $_viewer_preferences_manager = null;
    protected $_font_manager = null;

    protected $_toolkit = null;

    protected $_pdf_pages = null;
    protected $_pdf_catalog = null;
    protected $_pdf_outlines = null;
    protected $_pdf_procset = null;
    protected $_pdf_trailer = null;
    protected $_pdf_info = null;

    public $path = null;

    public function __construct($x = 595, $y = 841)
    {
        $this->path = dirname(__FILE__);

        $this->_factory = new BPdf_Object_Factory();
        $this->_parser = new BPdf_Parser($this);

        // generate empty document

        $this->_pdf_catalog = $this->getFactory()->newObject();
        $this->_pdf_outlines = $this->getFactory()->newObject();
        $this->_pdf_pages = $this->getFactory()->newObject();
        $this->_pdf_trailer = $this->getFactory()->newTrailer($this);
        $this->_pdf_info = $this->getFactory()->newObject();
        $this->_pdf_procset = $this->getFactory()->newProcsetObject();

        $this->_pdf_catalog->Type = new BPdf_Object_Element_Name('Catalog');
        $this->_pdf_catalog->Outlines = $this->_pdf_outlines;
        $this->_pdf_catalog->Pages = $this->_pdf_pages;

        $this->_pdf_trailer->Info = $this->_pdf_info;

        $this->_pdf_outlines->Type = new BPdf_Object_Element_Name('Outlines');
        $this->_pdf_outlines->Count = new BPdf_Object_Element_Numeric(0);

        $this->_pdf_procset->items = new BPdf_Object_Element_Name("PDF");
        $this->_pdf_procset->items = new BPdf_Object_Element_Name("Text");
        $this->_pdf_procset->items = new BPdf_Object_Element_Name("ImageB");
        $this->_pdf_procset->items = new BPdf_Object_Element_Name("ImageI");
        $this->_pdf_procset->items = new BPdf_Object_Element_Name("ImageC");

        $this->_info_manager = new BPdf_Manager_Info($this);
        $this->_page_manager = new BPdf_Manager_Page($this);
        $this->_viewer_preferences_manager = new BPdf_Manager_ViewerPreferences($this);
        $this->_font_manager = new BPdf_Manager_Font($this);
        $this->_toolkit = new BPdf_Toolkit($this);

        $this->_info_manager->setCreator('banit.pl BPdf library');

        $this->_page_manager->setPageSize($x, $y);
        $this->_page_manager->addPage();
    }

    public function getProcset()
    {
        return $this->_pdf_procset;
    }

    public function getFactory()
    {
        return $this->_factory;
    }

    public function getInfoManager()
    {
        return $this->_info_manager;
    }

    public function getPageManager()
    {
        return $this->_page_manager;
    }

    public function getToolkit()
    {
        return $this->_toolkit;
    }

    public function getViewerPreferencesManager()
    {
        return $this->_viewer_preferences_manager;
    }

    public function getFontManager()
    {
        return $this->_font_manager;
    }

    public function getParser()
    {
        return $this->_parser;
    }

    public function getPdfTrailer()
    {
        return $this->_pdf_trailer;
    }

    public function getPdfCatalog()
    {
        return $this->_pdf_catalog;
    }

    public function getPdfInfo()
    {
        return $this->_pdf_info;
    }

    public function getPdfPages()
    {
        return $this->_pdf_pages;
    }

    public static function registerAutoload() {
        spl_autoload_register(function ($class) {
            include dirname(__FILE__) . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, strtolower($class)) . '.php';
        });
    }
}