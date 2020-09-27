<?php

class BPdf_Resource_Image
{
    protected $_bPdf = null;
    protected $_resource = null;
    protected $_image_label = null;

    protected $_width = 0;
    protected $_height = 0;

    public function getResource()
    {
        return $this->_resource;
    }

    public function getImageLabel()
    {
        return $this->_image_label;
    }

    public function getWidth()
    {
        return $this->_width;
    }

    public function getHeight()
    {
        return $this->_height;
    }

    public function __construct($bPdf, $image_num, $image)
    {
        $this->_bPdf = $bPdf;
        $this->_image_label = "Im" . $image_num;

        $this->_resource = $this->_bPdf->getFactory()->newStreamObject();
        $this->_resource->Type = new BPdf_Object_Element_Name('XObject');
        $this->_resource->Subtype = new BPdf_Object_Element_Name('Image');

    }
}