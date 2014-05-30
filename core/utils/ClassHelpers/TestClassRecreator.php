<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TestClassRecreator {
    
    protected $_rapport;
    protected $_file;
    /** @var SeleniumShell_TestMethod */
    protected $_testMethods;
    protected $_testClassFile;
    private $_testClassFileName;
    protected $_filePath;
    protected $_savePath;
    protected $_projectName = '';
    
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
    
    protected function _setFile(){
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
        return $this->_savePath .$this->_projectName. $browser . $this->_testClassFileName;
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
        $commandChain = new Annotations_ChainOfCommand();
        $commandChain->addCommand(new Browsers_AnnotationCommand());
        $commandChain->addCommand(new SoloRun_AnnotationCommand());
        $commandChain->addCommand(new SetupBeforeProject_AnnotationCommand);
        
        $testMethods = $this->_testMethods;
        $testMethods = $commandChain->runCommand('setup-before-project', array('testMethods' => $testMethods) );
        $testMethods = $commandChain->runCommand('solo-run', array('testMethods' => $testMethods) );
        $testMethods = $commandChain->runCommand('browsers', array('testMethods' => $testMethods, 'browser' => $browser) );
        
        foreach($testMethods as $testMethod ){
            if( $testMethod->getStripMethodState() ){
                $this->_stripMethod($testMethod->getMethod());
            }
        }
    }
    
    protected function _setBrowserVariable( $browser ){
        $classDefenitionPosition = strpos( $this->_file, 'class ' );
        $openingBracketClassPosition = strpos( $this->_file, '{', $classDefenitionPosition);
        $this->_file = substr_replace($this->_file, PHP_EOL . PHP_EOL . "\t" . 'public $browser = "' . $browser .'";' .PHP_EOL, $openingBracketClassPosition + 1, 0);
        
    }
    
    
    protected function _stripMethod( $testMethod ){
        $this->_file = str_replace( $testMethod, '', $this->_file );
    }
}