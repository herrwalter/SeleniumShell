<?php

class FileScannerFilter
{
    protected $patterns;
    
    /**
     * A pattern is a part of the filepath
     * Like .php 
     * @param array $patterns
     */
    public function __construct(array $patterns){
        $this->patterns = $patterns;
    }
    
    public function addPattern( $pattern ){
        $this->patterns[] = $pattern;
    }
    
    public function removePattern( $pattern ){
        $key = array_search($pattern, $this->patterns);
        if( $key !== false ){
            unset($this->patterns[$key]);
        }
    }
    
    protected function matchFile($file){
        foreach($this->patterns as $pattern){
            if( strpos($file, $pattern )!== false){
                return true;
            }
        }
        return false;
    }
    
    public function filterFiles(array $files){
        $files = array();
        foreach ($files as $file){
            if( $this->matchFile($file) ){
                $files[] = $file;
            }
        }
        return $files;
    }
}
