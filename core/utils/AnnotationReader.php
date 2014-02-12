<?php

/**
 * Reads the annotations on the testMethod as returned by the TestClassReader
 * 
 */
class AnnotationReader
{
    private $_testMethod;
    private $_soloRun = false;
    private $_browsers = array();
    
    public function __construct( $testMethod ) {
        $this->_testMethod = $testMethod;
        $this->_annotations = $testMethod['annotations'];
        $this->_readAnnotations();
    }
    
    public function hasAnnotations(){
        return $this->_annotations !== false;
    }
    
    private function _readAnnotations(){
        if( !$this->_annotations ){
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
                    $this->_browsers = explode(', ', $annotationValue);
                    break;
            }
        }
    }
    
    public function hasSoloRun(){
        return $this->_soloRun;
    }
    
    public function getBrowsers(){
        return $this->_browsers;
    }
    
}