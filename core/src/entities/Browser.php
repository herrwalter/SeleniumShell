<?php

class Browser
{

    protected $name;
    protected $version;
    protected $platform;
    protected $uniqueName;

    public function __construct($uniqueName, $browserName, $version = false, $platform = false)
    {
        $this->setUniqueName($uniqueName);
        $this->setBrowserName($browserName);
        $this->setVersion($version);
        $this->setPlatform($platform);
    }

    public function toString()
    {
        $browserArray = 'array(';
        $browserArray .= '"browserName" => "' . $this->getBrowserName() . '"';
        $browserArray .= ', "desiredCapabilities" => array(';
        if ($this->getVersion()) {
            $browserArray .= '"version" => "' . $this->getVersion(). '"';
            
        }
        if( $this->getPlatform() ){
            $browserArray .= ', "platform" => "' . $this->getPlatform(). '"';
        }
        $browserArray .= ')';
        $browserArray .= ')';
        return $browserArray;
    }

    public function setUniqueName($uniqueName)
    {
        $this->uniqueName = $uniqueName;
    }

    public function setBrowserName($browserName)
    {
        $this->name = $browserName;
    }

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function setPlatform($platform)
    {
        $this->platform = $platform;
    }

    public function getUniqueName()
    {
        return $this->uniqueName;
    }

    public function getBrowserName()
    {
        return $this->name;
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function getVersion()
    {
        return $this->version;
    }

}
