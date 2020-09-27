<?php

class BPdf_Resource_Image_Jpeg extends BPdf_Resource_Image
{
    public function __construct($bPdf, $image_num, $image)
    {
        parent::__construct($bPdf, $image_num, $image);

        $this->_bPdf = $bPdf;
        $this->_image_label = "Im" . $image_num;

        $image_info = getimagesize($image);

        switch ($image_info['channels']) {
            case 3:
                $color_space = 'DeviceRGB';
                break;
            case 4:
                $color_space = 'DeviceCMYK';
                break;
            default:
                $color_space = 'DeviceGray';
                break;
        }

        $this->_resource->Width = new BPdf_Object_Element_Numeric($image_info[0]);
        $this->_resource->Height = new BPdf_Object_Element_Numeric($image_info[1]);
        $this->_resource->ColorSpace = new BPdf_Object_Element_Name($color_space);
        $this->_resource->BitsPerComponent = new BPdf_Object_Element_Numeric($image_info['bits']);

        if ($image_info[2] == IMAGETYPE_JPEG) {
            $this->_resource->Filter = new BPdf_Object_Element_Name('DCTDecode');
        } else if ($image_info[2] == IMAGETYPE_JPEG2000) {
            $this->_resource->Filter = new BPdf_Object_Element_Name('JPXDecode');
        } else {
            throw new Exception('Unsupported format');
        }

        $byte_count = filesize($image);
        $data = file_get_contents($image);

        $this->_resource->stream = $data;

        $this->_width = $image_info[0];
        $this->_height = $image_info[1];
    }
}