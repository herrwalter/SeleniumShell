<?php

class ScreenshotSession
{

    protected $name;
    protected $screenshots;
    protected $path;

    public function __construct($sessionname)
    {
        $this->name = $sessionname;
        $this->path = GENERATED_SCREENSHOTS_PATH . $sessionname;
        $this->setScreenshots();
    }
    
    protected function setScreenshots()
    {
        $files = array_diff(scandir($this->path), array('.', '..'));
        $screenshots = array();
        foreach($files as $screenshot){
            if(pathinfo($screenshot, PATHINFO_EXTENSION) == 'jpg'){
                $screenshots[$screenshot] = new Image($this->path . DIRECTORY_SEPARATOR . $screenshot);
            }
        }
        $this->screenshots = $screenshots;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPath()
    {
        return $this->path;
    }
    
    public function getScreenshots()
    {
        return $this->screenshots;
    }
    
    public function getScreenshot($name){
        if( $this->hasScreenshot($name)){
            return $this->screenshots[$name];
        }
        return false;
    }
    
    public function hasScreenshot($name)
    {
        return array_key_exists($name, $this->screenshots );
    }

}
