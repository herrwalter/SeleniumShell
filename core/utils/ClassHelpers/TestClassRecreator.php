<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TestClassRecreator {
    
    private $_rapport;
    private $_file;
    private $_testMethods;
    private $_testClassFile;
    private $_testClassFileName;
    private $_filePath;
    
    public function __construct( $testClassFile ){
        
        $testClassReader = new TestClassReader($testClassFile);
        $this->_rapport =  $testClassReader->getFileRapport();
        $this->_testMethods = $testClassReader->getTestMethods();
        $this->_testClassFile = $testClassFile;
        $this->_testClassFileName = basename($testClassFile);
        $this->_file = file_get_contents($testClassFile);
        $this->_testMethods = $testClassReader->getTestMethods();
        $config = new ConfigHandler(CORE_CONFIG_PATH . '\config.ini');
        $browsers = $config->getAttribute('browsers');
        foreach( $browsers as $browser ){
            $this->_createFileForBrowser( $browser );
        }
    }
    
    private function _setFile(){
        if( is_file($this->_testClassFile) ){
            $this->_file = file_get_contents($this->_testClassFile);
        }
        else{
            throw new Exception('Testclass file provided is not a file');
        }
    }
    
    protected function _createFileForBrowser( $browser ){
        $this->_setFile();
        $this->_changeTestFileClassName($browser);
        $this->_deleteTestsThatShouldNotRunInThisBrowser($browser);
        $this->_saveFile($browser);
    }
    
    /**
     * Adds name to current classname
     * @param type $name
     */
    protected function _changeTestFileClassName( $name ){
        $this->_file = str_replace(  'class ', 'class '.$name, $this->_file );
    }
    
    protected function _saveFile($name){
        file_put_contents(GENERATED_PATH . '/' . $name . $this->_testClassFileName, $this->_file);
    }
    
    protected function _deleteTestsThatShouldNotRunInThisBrowser($browser){
        // check the annotations of the testmethods
        foreach( $this->_testMethods as $testMethod ){
            $annotations = new AnnotationReader($testMethod);
            $browsers = $annotations->getBrowsers();
            // strip method is browsers is set and one is not in the ss-browsers annotation
            if( !empty($browsers) && strpos( strtolower($browsers[0]), strtolower($browser)) == -1 ){
                $this->_stripMethod($testMethod);
            }
        }
    }
    
    protected function _stripMethod( $testMethod ){
        $this->_file = str_replace( $testMethod['test'], '', $this->_file );
    }
}