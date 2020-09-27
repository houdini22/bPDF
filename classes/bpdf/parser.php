<?php

class BPdf_Parser
{
    protected $_bPdf = null;

    public function __construct($bpdf)
    {
        $this->_bPdf = $bpdf;
    }

    public function parse()
    {
        $factory = $this->_bPdf->getFactory();
        $objects = $factory->getObjects();

        $xref = array();
        $xref[-1] = 0;
        $xref[0] = 0;
        $strObj = array();
        $sizeObj = array();
        $buffer = "";
        $i = 0;

        $trailer = $this->_bPdf->getPdfTrailer();
        $trailer->Size = new BPdf_Object_Element_Numeric($factory->getNbObjects());
        $trailer->Root = $this->_bPdf->getPdfCatalog();

        $buffer .= "%PDF-1.3\n\n";
        $buffer .= "%\xE2\xE3\xCF\xD3\n";
        $sizeObj[] = strlen($buffer);

        foreach ($objects as $k => $obj) {
            $buffer .= $obj->dump();
            $sizeObj[] = strlen($obj->dump());
        }

        for ($i = 0; $i <= $factory->getNbObjects(); $i++) {
            $xref[] = $xref[$i - 1] + $sizeObj[$i];
        }

        $nb_objects = $factory->getNbObjects();

        $buffer .= "xref\n";
        $buffer .= "0 " . ($nb_objects + 1) . "\n";
        $buffer .= "0000000000 65535 f\n";

        foreach ($xref as $k => $p) {
            $buffer .= substr('0000000000', 0, 10 - strlen($p)) . $p . " 00000 n\n";
        }

        $buffer .= "\n";
        $buffer .= $trailer->dump() . "\n";
        $buffer .= "startxref\n";
        $buffer .= strlen($buffer) . "\n";
        $buffer .= "%%EOF";

        return $buffer;

    }

    public function saveToFile($factory = null)
    {
        if ($factory === null) {
            $factory = $this->_factory;
        }

        $output = $this->parse($factory);

        $h = fopen("test.pdf", "w");
        fwrite($h, $output);
        fclose($h);
    }

    public function stream($options = array())
    {
        if (!is_array($options)) {
            $options = array();
        }

        $tmp = $this->parse();

        header("Content-type: application/pdf");
        header("Content-Length: " . strlen(ltrim($tmp)));
        $fileName = (isset($options['Content-Disposition']) ? $options['Content-Disposition'] : 'file.pdf');
        header("Content-Disposition: inline; filename=" . $fileName);
        if (isset($options['Accept-Ranges']) && $options['Accept-Ranges'] == 1) {
            header("Accept-Ranges: " . strlen(ltrim($tmp)));
        }
        echo ltrim($tmp);
        die();
    }
}
