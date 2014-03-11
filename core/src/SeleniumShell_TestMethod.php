<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SeleniumShell_TestMethod {
    
    private $_annotations;
    
    private $_method;
    
    public function setAnnotations( $annotations ){
        $this->_annotations = new AnnotationReader($annotations);
    }
    /**
     * @return AnnotationReader
     */
    public function getAnnotations( ){
        return $this->_annotations;
    }
    
    public function setMethod( $method ){
        $this->_method = $method;
    }
   
    public function getMethod(){
        return $this->_method;
    } 
    
}