<?php

class BPdf_Manager_ViewerPreferences
{
    protected $_bPdf = null;
    protected $_pdf_catalog = null;
    protected $_viewer_preferences_dictionary = null;

    public function __construct($bPdf)
    {
        $this->_bPdf = $bPdf;
        $this->_pdf_catalog = $bPdf->getPdfCatalog();
        $this->_viewer_preferences_dictionary = new BPdf_Object_Element_Dictionary();
        $this->_pdf_catalog->ViewerPreferences = $this->_viewer_preferences_dictionary;
    }

    public function setHideToolbar($val)
    {
        $this->_viewer_preferences_dictionary->HideToolbar = new BPdf_Object_Element_Boolean($val);
    }

    public function setHideMenubar($val)
    {
        $this->_viewer_preferences_dictionary->HideMenubar = new BPdf_Object_Element_Boolean($val);
    }

    public function setHideWindowUI($val)
    {
        $this->_viewer_preferences_dictionary->HideWindowUI = new BPdf_Object_Element_Boolean($val);
    }

    public function setDisplayDocTitle($val)
    {
        $this->_viewer_preferences_dictionary->DisplayDocTitle = new BPdf_Object_Element_Boolean($val);
    }

    public function setFitWindow($val)
    {
        $this->_viewer_preferences_dictionary->FitWindow = new BPdf_Object_Element_Boolean($val);
    }

    public function setCenterWindow($val)
    {
        $this->_viewer_preferences_dictionary->CenterWindow = new BPdf_Object_Element_Boolean($val);
    }
}