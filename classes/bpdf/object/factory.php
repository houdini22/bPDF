<?php

class BPdf_Object_Factory
{
    protected $objects = array();
    protected $nb_objects = 0;

    public function getNbObjects()
    {
        return $this->nb_objects;
    }

    public static function getFactory()
    {
        return new BPdf_Object_Factory();
    }

    public function newTrailer($bPdf)
    {
        //$this->nb_objects++;
        $obj = new BPdf_Object_Trailer($bPdf);
        return $obj;
    }

    public function newObject()
    {
        $this->nb_objects++;
        $obj = new BPdf_Object_Object($this->nb_objects);
        $this->objects[] = $obj;
        return $obj;
    }

    public function newProcsetObject()
    {
        $this->nb_objects++;
        $obj = new BPdf_Object_Procset($this->nb_objects);
        $this->objects[] = $obj;
        return $obj;
    }

    public function newStreamObject($val = null)
    {
        $this->nb_objects++;
        $obj = new BPdf_Object_Stream($val, $this->nb_objects);
        $this->objects[] = $obj;
        return $obj;
    }

    public function getObjects()
    {
        return $this->objects;
    }
}
