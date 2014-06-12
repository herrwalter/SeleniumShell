<?php

class PathCreator
{
    protected $_paths;
    
    public function __construct( $paths )
    {
        $this->_paths = $paths;
        $this->_createPaths();
        $this->_definePaths();
    }
    
    private function _createPaths()
    {
        foreach($this->_paths as $path ){
            if( !file_exists($path) ){
                mkdir($path);
            }
        }
    }
    
    private function _definePaths()
    {
        foreach($this->_paths as $constant => $path ){
            if(!defined($constant)){
                define($constant, $path);
            }
        }
    }
    
}
