<?php

class Image
{
    protected $path;
    protected $resource;
    protected $width;
    protected $height;
    protected $colors = array();

    public function __construct($path)
    {
        $this->path = $path;
    }

    protected function setResource()
    {
        $source = file_get_contents($this->path);
        $this->resource = imagecreatefromstring($source);
    }

    protected function setDimensions()
    {
        $this->width = imagesx($this->resource);
        $this->height = imagesy($this->resource);
    }
    
    public function initialize()
    {
        if( $this->resource == null ){
            $this->setResource();
            $this->setDimensions();
        }
    }

    public function destroy()
    {
        imagedestroy($this->resource);
    }

    public function getColorAt($x, $y)
    {
        $rgb = imagecolorat($this->resource, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        return $r . $g . $b;
    }

    public function getResource()
    {
        $this->initialize();
        return $this->resource;
    }
    

    public function getWidth()
    {
        $this->initialize();
        return $this->width;
    }

    public function getHeight()
    {
        $this->initialize();
        return $this->height;
    }

    public function getColors()
    {
        $this->initialize();
        return $this->colors;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getUniqueColors()
    {
        $this->initialize();
        return array_unique($this->colors);
    }

}
