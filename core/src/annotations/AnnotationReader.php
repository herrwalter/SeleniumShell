<?php

/**
 * Reads the annotations on the testMethod as returned by the TestClassReader
 * 
 */
class AnnotationReader
{
    private $_annotations;
    private $_soloRun = false;
    private $_setupBeforeProject = false;
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
        foreach( $this->_annotations as $name => $value){
            switch( $name ){
                case 'ss-solo-run':
                    $this->_soloRun = true;
                    break;
                case 'ss-browsers':
                    $browsers = explode(',', $value);
                    $trimmedBrowsers = array_map('trim', $browsers);
                    $this->_browsers = $trimmedBrowsers;
                    break;
                case 'ss-setup-before-project':
                    $this->_setupBeforeProject = true;
                    break;
                default:
                    break;
            }
        }
    }
    
    public function hasSoloRun(){
        return $this->_soloRun;
    }
    
    public function hasSetupBeforeProject()
    {
        return $this->_setupBeforeProject;
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