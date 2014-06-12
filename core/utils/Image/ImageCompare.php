<?php

class ImageCompare
{

    protected $_img1;
    protected $_img2;
    protected $_xOffset = array();
    protected $_yOffset = array();
    /** @var Dimensions */
    protected $_offsetDimensions;

    public function __construct(Image $img1, Image $img2)
    {
        $this->_img1 = $img1;
        $this->_img2 = $img2;
        $this->_setOffsetDimensions();
    }

    public function equalHeight()
    {
        return $this->_img1->getHeight() === $this->_img2->getHeight();
    }

    public function equalWidth()
    {
        return $this->_img1->getWidth() === $this->_img2->getWidth();
    }

    public function equalDimensions()
    {
        return $this->equalHeight() && $this->equalWidth();
    }

    public function equalColors()
    {
        return empty($this->_xOffset) && empty($this->_yOffset);
    }
    /**
     * Sets the dimensions of the difference between two images
     */
    protected function _setOffsetDimensions()
    {
        if (!$this->equalDimensions()) {
            return;
        }
        for ($y = 0; $y < $this->_img1->getHeight(); $y++) {
            for ($x = 0; $x < $this->_img1->getWidth(); $x++) {
                if ($this->_img1->getColorAt($x, $y) !== $this->_img2->getColorAt($x, $y)) {
                    // save x and y if the colors dont match
                    $this->_xOffset[$x] = $x;
                    $this->_yOffset[$y] = $y;
                }
            }
        }
        if( !$this->equalColors() ){
            sort($this->_xOffset);
            sort($this->_yOffset);
            $this->_offsetDimensions =  new Dimensions( 
                                            array_shift($this->_yOffset),   //top
                                            array_pop($this->_xOffset),     //right
                                            array_pop($this->_yOffset),     //bottom
                                            array_shift($this->_xOffset)    //left
                                        );
        }
    }
    /**
     * @return \Dimensions
     */
    public function getOffsetDimensions()
    {
        return $this->_offsetDimensions;
    }

}
