<?php

/**
 * Reads the annotations on the testMethod as returned by the TestClassReader
 * 
 */
class AnnotationReader
{
    private $_annotations;
    private $_soloRun = false;
    private $_browsers = array();
    
    public function __construct( $annotations ) {
        $this->_annotations = $annotations;
        $this->_readAnnotations();
    }
    
    public function hasAnnotations(){
        return $this->_annotations !== false;
    }
    
    private function _readAnnotations(){
        if( !$this->_annotations ){
            $this->_annotations = false;
            return;
        }
        foreach( $this->_annotations as $annotation ){
            $annotationName = key($annotation);
            $annotationValue = $annotation[$annotationName];
            switch( key($annotation) ){
                case 'ss-solo-run':
                    $this->_soloRun = true;
                    break;
                case 'ss-browsers':
                    $browsers = explode(',', $annotationValue);
                    $trimmedBrowsers = array_map('trim', $browsers);
                    $this->_browsers = $trimmedBrowsers;
                    break;
            }
        }
    }
    
    public function hasSoloRun(){
        return $this->_soloRun === true;
    }
    
    public function hasBrowser($browser){
        foreach($this->_browsers as $browserName ){
            if( strtolower($browser) == strtolower($browserName)){
                return true;
            }
        }
        return false;
    }
    
    public function hasBrowsersAnnotationSet(){
        return count($this->_browsers) !== 0;
    }
    
    public function getBrowsers(){
        return $this->_browsers;
    }
    
}