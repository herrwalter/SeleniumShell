<?php

class SeleniumShell_TestMethod {
    
    /** @var AnnotationReader*/
    private $_annotations;
    
    private $_method;
    
    private $_stripMethod = false;
    
    public function setAnnotations( $annotations ){
        $this->_annotations = new AnnotationReader($annotations);
    }
    /**
     * @return AnnotationReader
     */
    public function getAnnotations( ){
        //if( $this->hasAnnotations() ){
            return $this->_annotations;
        //}
        //return false;
    }
    
    public function hasAnnotations(){
        return $this->_annotations->hasAnnotations();
    }
    
    public function setMethod( $method ){
        $this->_method = $method;
    }
   
    public function getMethod(){
        return $this->_method;
    } 
    
    public function stripMethod(){
        $this->_stripMethod = true;
    }
    
    public function getStripMethodState(){
        return $this->_stripMethod;
    }
    
    
}