<?php

class ImageSlicer implements ImageEditor
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
        $this->set();
    }

    public function set()
    {
        $this->resource = imagecreatefromstring(file_get_contents($this->image->getPath()));
        if ($this->resource !== false) {
            $this->createdIimage = imagecreatetruecolor($this->dimensions->getWidth(), $this->dimensions->getHeight());

            imagecopy($this->createdIimage, $this->resource, 0, 0, $this->dimensions->getLeft(), $this->dimensions->getTop(), $this->dimensions->getWidth(), $this->dimensions->getHeight());
            $this->slice = $this->createdIimage;
        } else {
            throw new ErrorException('Something went wrong while creating an image');
        }
    }

    public function save($path)
    {
        imagepng($this->get(), $path, 0);
        imagedestroy($this->resource);
        imagedestroy($this->slice);
    }

    public function get()
    {
        if ($this->slice) {
            return $this->slice;
        } else {
            throw new ErrorException('images allready destroyed');
        }
    }

    public function destroy()
    {
        imagedestroy($this->resource);
    }

}
