<?php

class ImageSlicer
{

    /** @var Image */
    protected $_image;

    /** @var Dimensions */
    protected $_dimensions;
    protected $_slice;

    public function __construct(Image $imgSource, Dimensions $dimensions)
    {
        $this->_image = $imgSource;
        $this->_dimensions = $dimensions;
        $this->_setSlice();
    }

    protected function _setSlice()
    {
        $rescource = imagecreatefromstring(file_get_contents($this->_image->getPath()));
        $image = imagecreatetruecolor($this->_dimensions->getWidth(), $this->_dimensions->getHeight());
        imagecopy($image, $rescource, 0, 0, $this->_dimensions->getLeft(), $this->_dimensions->getTop(), $this->_dimensions->getWidth(), $this->_dimensions->getHeight());
        $this->_slice = $image;
    }

    public function getSlice()
    {
        return $this->_slice;
    }

}
