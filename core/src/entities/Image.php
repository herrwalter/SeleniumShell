<?php

class Image
{

    protected $_path;
    protected $_resource;
    protected $_width;
    protected $_height;
    protected $_colors = array();

    public function __construct($path)
    {
        $this->_path = $path;
        $this->_setResource();
        $this->_setDimensions();
    }
    

    protected function _setResource()
    {
        $source = file_get_contents($this->_path);
        $this->_resource = imagecreatefromstring($source);
    }

    protected function _setDimensions()
    {
        $this->_width = imagesx($this->_resource);
        $this->_height = imagesy($this->_resource);
    }
    
    public function getColorAt($x,$y){
        $rgb = imagecolorat($this->_resource, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        return $r.$g.$b;
    }
    
    public function getResource()
    {
        return $this->_resource;
    }

    public function getWidth()
    {
        return $this->_width;
    }

    public function getHeight()
    {
        return $this->_height;
    }

    public function getColors()
    {
        return $this->_colors;
    }
    
    public function getPath()
    {
        return $this->_path;
    }
    
    public function getUniqueColors()
    {
        return array_unique($this->_colors);
    }
    

}
