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
    private $_savePath;
    private $_projectName = '';
    
    public function __construct( $testClassFile ){
        $this->_savePath = GENERATED_PATH . DIRECTORY_SEPARATOR . 'testsuites' . DIRECTORY_SEPARATOR;
        
        $testClassReader = new TestClassReader($testClassFile);
        $this->_rapport =  $testClassReader->getFileRapport();
        $this->_testMethods = $testClassReader->getTestMethods();
        
        $this->_testClassFile = $testClassFile;
        $this->_testClassFileName = basename($testClassFile);
    }

    public function setProjectName( $projectName )
    {
        $projectName = str_replace(' ', '', $projectName);
        $this->_projectName = $projectName;
    }
    
    public function setSavePath( $path ){
        if( is_dir( $path ) ){
            $this->_savePath = $path;
        }else{
            mkdir($path);
            $this->_savePath = $path;
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
    
    public function createFileForBrowser( $browser ){
        $this->_setFile();
        $this->_changeTestFileClassName($browser);
        $this->_setBrowserVariable($browser);
        $this->_deleteTestsThatShouldNotRunInThisBrowser($browser);
        $this->_saveFile($browser);
    }
    
    /**
     * Adds name to current classname
     * @param type $name
     */
    protected function _changeTestFileClassName( $name ){
        $this->_file = str_replace(  'class ', 'class '.$this->_projectName.$name, $this->_file );
    }
    
    protected function _saveFile($name){
        file_put_contents($this->_savePath .$this->_projectName. $name . $this->_testClassFileName, $this->_file);
    }
    
    protected function _deleteTestsThatShouldNotRunInThisBrowser($browser){
        // check the annotations of the testmethods
        
        // check for the solorun annotations. if found,
        // all other testmethods should be stripped from the class.
        $solorun = $this->_checkForSolorun();
        if( $solorun ){
            foreach( $this->_testMethods as $testMethod ){
                if( $solorun !== $testMethod ){
                    $this->_stripMethod($testMethod);
                }
            }
        }
        
        // if --ss-browsers is set, this should overrule. and the browsers annotation rule should be ignored 
        $config = new ConfigHandler();
        if( $config->isParameterSet('--ss-browsers') ){
            return;
        }
        
        // check for the browsers annotation. if found,
        // check if it contains the current browser, else use default.
        foreach( $this->_testMethods as $testMethod ){
            $annotations = new AnnotationReader($testMethod);
            $browsers = $annotations->getBrowsers();
            // strip method is browsers is set and one is not in the ss-browsers annotation
            if( !empty($browsers) && strpos( strtolower($browsers[0]), strtolower($browser) ) === false ){
                $this->_stripMethod($testMethod);
            }
        }
    }
    
    protected function _setBrowserVariable( $browser ){
        $classDefenitionPosition = strpos( $this->_file, 'class ' );
        $openingBracketClassPosition = strpos( $this->_file, '{', $classDefenitionPosition);
        $this->_file = substr_replace($this->_file, PHP_EOL . PHP_EOL . "\t" . 'public $browser = "' . $browser .'";' .PHP_EOL, $openingBracketClassPosition + 1, 0);
        
    }
    
    
    protected function _checkForSolorun( )
    {
        //loop over testmethods for solorun annotation
        foreach( $this->_testMethods as $testMethod){
            $annotations = new AnnotationReader($testMethod);
            $solorun = $annotations->hasSoloRun();
            if( $solorun ){
                // if found, we will return the test method.
                return $testMethod;
            }
        }
        return false;
    }
    
    protected function _stripMethod( $testMethod ){
        $this->_file = str_replace( $testMethod['test'], '', $this->_file );
    }
}