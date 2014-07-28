<?php

class PathCreator
{
    protected $paths;
    
    public function __construct( $paths = array() )
    {
        $this->paths = $paths;
        $this->createPaths();
    }
    
    public function setPaths( array $paths ){
        $this->paths = $paths;
    }
    
    
    public function createPaths()
    {
        foreach($this->paths as $path ){
            if( !file_exists($path) ){
                mkdir($path);
            }
        }
    }
    
}
