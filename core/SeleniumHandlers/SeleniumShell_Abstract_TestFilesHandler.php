<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class SeleniumShell_Abstract_TestFilesHandler{
    
    private $_pathToTestFilesFolder;
    
    private $_pathToRewriteLocalFilesTo;
    
    private $_testFiles = array();
    
    private $_testFilesCache = array();
    
    public function __construct(){
        $this->_pathToTestFilesFolder = $this->getPathToTestDataFilesFolder();
        $this->_setPathToRewriteLocalFilesTo();
        $this->_setTestFiles();
    }
    
    private function _setPathToRewriteLocalFilesTo(){
        $path = $this->getPathToRewriteLocalFilesTo();
        $endingChar = substr($path, 1, -1);
        if( $endingChar !== '/'  ){
            $path = $path . DIRECTORY_SEPARATOR;
        }
        $this->_pathToRewriteLocalFilesTo = $path;
    }
    
    private function _setTestFiles(){
        $fileScanner = new FileScanner( $this->_pathToTestFilesFolder );
        $this->_testFiles = $fileScanner->getFilesInOneDimensionalArray();
        
    }
    
    private function _writeAllTestFilesToCache(){
        foreach($this->_testFiles as $testFile ){
            $this->_testFilesCache[$testFile] = file_get_contents($testFile);
        }
    }
    
    private function _rewriteTestDataFileToLocalPath( $testFile ){
        $fileName = pathinfo($testFile, PATHINFO_FILENAME);
        $testFile = file_get_contents($testFile);
        file_put_contents($this->_pathToRewriteLocalFilesTo . $fileName, $testFile );
    }
    
    public function getTestDataFile($name){
        foreach( $this->_testFiles as $testFile ){
            $fileName = pathinfo($testFile, PATHINFO_FILENAME);
            if( $fileName == $name ){
                $this->_rewriteTestDataFileToLocalPath($testFile);
                return $this->_pathToRewriteLocalFilesTo . $fileName;
            }
        }
        return false;
    }
    /**
     * @return String with the full path to your testFilesFolder 
     */
    abstract protected function getPathToTestDataFilesFolder();
    
    /**
     * Will set an array of local path files to rewrite the testfiles to.
     * @return Array with local paths to rewrite the testfiles to.
     */
    abstract protected function getPathToRewriteLocalFilesTo();
}