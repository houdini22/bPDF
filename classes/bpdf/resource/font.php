<?php

class BPdf_Resource_Font
{
    protected $_bPdf = null;
    protected $_pdf_font = null;
    protected $_pdf_descendant_font = null;
    protected $_pdf_font_descriptor = null;
    protected $_pdf_cid_to_gid_map = null;
    protected $_pdf_font_file = null;
    protected $_cw = null;

    public $font_label = null;

    public function __construct($bPdf, $font_num, $font_name, $path)
    {
        $this->_bPdf = $bPdf;
        $this->font_label = "F" . $font_num;
        $this->_pdf_font = $this->_bPdf->getFactory()->newObject();
        $this->_pdf_descendant_font = $this->_bPdf->getFactory()->newObject();
        $this->_pdf_font_descriptor = $this->_bPdf->getFactory()->newObject();
        $this->_pdf_cid_to_gid_map = $this->_bPdf->getFactory()->newStreamObject();
        $this->_pdf_font_file = $this->_bPdf->getFactory()->newStreamObject();

        $file = $path . '/' . $font_name . '.php';

        if (!file_exists($file)) {
            throw new Exception("Plik $file nieistnieje");
        }

        include($file);

        $this->_pdf_font->Type = new BPdf_Object_Element_Name('Font');
        $this->_pdf_font->Subtype = new BPdf_Object_Element_Name('Type0');
        $this->_pdf_font->BaseFont = new BPdf_Object_Element_Name($font_data['name'] . '-UCS');
        $this->_pdf_font->Encoding = new BPdf_Object_Element_Name('Identity-H');
        $this->_pdf_font->DescendantFonts = new BPdf_Object_Element_Array();
        $this->_pdf_font->DescendantFonts->items = $this->_pdf_descendant_font;

        $this->_pdf_descendant_font->Type = new BPdf_Object_Element_Name('Font');
        $this->_pdf_descendant_font->Subtype = new BPdf_Object_Element_Name('CIDFontType2');
        $this->_pdf_descendant_font->BaseFont = new BPdf_Object_Element_Name($font_data['name']);
        $this->_pdf_descendant_font->CIDSystemInfo = new BPdf_Object_Element_Dictionary();
        $this->_pdf_descendant_font->CIDSystemInfo->Registry = new BPdf_Object_Element_String('Adobe');
        $this->_pdf_descendant_font->CIDSystemInfo->Ordering = new BPdf_Object_Element_String('UCS');
        $this->_pdf_descendant_font->CIDSystemInfo->Supplement = new BPdf_Object_Element_Numeric(0);
        $this->_pdf_descendant_font->FontDescriptor = $this->_pdf_font_descriptor;
        $this->_pdf_descendant_font->W = new BPdf_Object_Element_Array();

        $this->_cw = $font_data['cw'];

        foreach ($font_data['cw'] as $k => $v) {
            $this->_pdf_descendant_font->W->items = new BPdf_Object_Element_Numeric($k);
            $v2 = new BPdf_Object_Element_Numeric($v);
            $a = array();
            $a[] = $v2;
            $this->_pdf_descendant_font->W->items = new BPdf_Object_Element_Array($a);
//			$a = array();
//			$a[] = new NumericElement($v);
//			$this->_pdf_descendant_font->W->items = new ArrayElement($a);
        }

        $this->_pdf_descendant_font->CIDToGIDMap = $this->_pdf_cid_to_gid_map;
        //$this->_pdf_descendant_font->W->items = new ArrayElemen

        $this->_pdf_font_descriptor->Type = new BPdf_Object_Element_Name('FontDescriptor');
        $this->_pdf_font_descriptor->FontName = new BPdf_Object_Element_Name($font_data['name']);
        $this->_pdf_font_descriptor->FontFile2 = $this->_pdf_font_file;
        $this->_pdf_font_descriptor->Ascent = new BPdf_Object_Element_Numeric($font_data['ascent']);
        $this->_pdf_font_descriptor->Descent = new BPdf_Object_Element_Numeric($font_data['descent']);
        $this->_pdf_font_descriptor->CapHeight = new BPdf_Object_Element_Numeric($font_data['cap_height']);
        $this->_pdf_font_descriptor->Flags = new BPdf_Object_Element_Numeric($font_data['flags']);
        $this->_pdf_font_descriptor->ItalicAngle = new BPdf_Object_Element_Numeric($font_data['italic_angle']);
        $this->_pdf_font_descriptor->StemV = new BPdf_Object_Element_Numeric($font_data['stemv']);
        $this->_pdf_font_descriptor->MissingWidth = new BPdf_Object_Element_Numeric($font_data['missing_width']);
        $this->_pdf_font_descriptor->FontBBox = new BPdf_Object_Element_Array();

        foreach ($font_data['fontbbox'] as $val) {
            $this->_pdf_font_descriptor->FontBBox->items = new BPdf_Object_Element_Numeric($val);
        }


        $file = $path . '/' . $font_data['ctg'];
        $size = filesize($file);

        if (!$size) {
            throw new Exception('File doesnt exists');
        }

        $this->_pdf_cid_to_gid_map->Filter = new BPdf_Object_Element_Name('FlateDecode');

        $fh = fopen($file, 'rb');
        $this->_pdf_cid_to_gid_map->stream = fread($fh, $size);

        $file = $path . '/' . $font_name . '.z';

        $this->_pdf_font_file->Filter = new BPdf_Object_Element_Name('FlateDecode');
        $this->_pdf_font_file->Length1 = new BPdf_Object_Element_Numeric($font_data['original_size']);

//		if (isset($lenght2)) {
//			$this->_pdf_font_file->Length2 = new NumericElement($lenght2);
//		}

        $fh = fopen($file, 'rb');
        $this->_pdf_font_file->stream = fread($fh, filesize($file));
    }

    public function getPdfFont()
    {
        return $this->_pdf_font;
    }

    public function getLabel()
    {
        return $this->_font_label;
    }

    public function getCw()
    {
        return $this->_cw;
    }
}