<?php

class Dimensions
{

    protected $left;
    protected $right;
    protected $top;
    protected $bottom;
    protected $width;
    protected $height;

    public function __construct($top, $right, $bottom, $left)
    {
        $this->left = $left;
        $this->right = $right;
        $this->top = $top;
        $this->bottom = $bottom;
        $this->width = $this->right - $this->left;
        $this->height = $this->bottom - $this->top;
    }

    public function getRight()
    {
        return $this->right;
    }

    public function getLeft()
    {
        return $this->left;
    }

    public function getTop()
    {
        return $this->top;
    }

    public function getBottom()
    {
        return $this->bottom;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

}
