<?php

class ImageSlicer
{

    /** @var Image */
    protected $image;

    /** @var Dimensions */
    protected $dimensions;
    protected $slice;
    protected $createdImage;
    protected $resource;

    public function __construct(Image $imgSource, Dimensions $dimensions)
    {
        $this->image = $imgSource;
        $this->dimensions = $dimensions;
        $this->setSlice();
    }

    protected function setSlice()
    {
        $this->resource = imagecreatefromstring(file_get_contents($this->image->getPath()));
        $this->createdIimage = imagecreatetruecolor($this->dimensions->getWidth(), $this->dimensions->getHeight());
        imagecopy($this->createdIimage, $this->resource, 0, 0, $this->dimensions->getLeft(), $this->dimensions->getTop(), $this->dimensions->getWidth(), $this->dimensions->getHeight());
        $this->slice = $this->createdIimage;
    }

    public function getSlice()
    {
        if ($this->slice) {
            return $this->slice;
        } else {
            throw new ErrorException('images allready destroyed');
        }
    }

    public function destroyImages()
    {
        imagedestroy($this->resource);
    }

}
