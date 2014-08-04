<?php

class ImageHighlighter implements ImageEditor
{

    /** @var Image */
    protected $image;

    /** @var Dimensions */
    protected $dimensions;
    protected $createdImage;
    protected $highlightedImage;
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
            $this->createdIimage = imagecreatetruecolor($this->image->getWidth(), $this->image->getHeight());

            //set color
            $highlightcolor = imagecolorallocate($this->resource, 255, 0, 0);
            $left = $this->dimensions->getLeft();
            $top = $this->dimensions->getTop();
            $width = $this->dimensions->getWidth();
            $height = $this->dimensions->getHeight();
            
            // draw all highlight borders
            for($i = $left; $i < ($width + $left); $i++ ){
                //top border
                imagesetpixel($this->resource, $i, $top, $highlightcolor);
                //bottom border
                imagesetpixel($this->resource, $i, $top + $height, $highlightcolor);
            }
            for($i = $top; $i < ($height + $top); $i++){
                //left border
                imagesetpixel($this->resource, $left, $i, $highlightcolor);
                //right border
                imagesetpixel($this->resource, $left + $width, $i, $highlightcolor);
            }
            
            imagecopy($this->createdIimage, $this->resource, 0, 0, 0, 0, $this->image->getWidth(), $this->image->getHeight());
            $this->highlightedImage = $this->createdIimage;
        } else {
            throw new ErrorException('Something went wrong while creating an image');
        }
    }
    

    public function save($path)
    {
        imagepng($this->get(), $path, 2);
        $this->destroy();
    }

    public function get()
    {
        if ($this->highlightedImage) {
            return $this->highlightedImage;
        } else {
            throw new ErrorException('images allready destroyed');
        }
    }

    public function destroy()
    {
        imagedestroy($this->resource);
        if( $this->createdImage ){
            imagedestroy($this->createdImage);
        }
    }

}
