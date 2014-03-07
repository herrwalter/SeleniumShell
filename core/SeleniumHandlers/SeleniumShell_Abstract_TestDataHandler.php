<?php

abstract class SeleniumShell_Abstract_TestDataHandler{

    private $_testDataPaths;
    private $_networkPath;
    private $_testFiles = array();
    
    public function __construct(){
        $this->_setTestDataPaths();
        $this->_setTestDataFiles();
    }
    
    /**
     * loop the provided evironment paths and add them to the testfiles array.
     */
    private function _setTestDataFiles(){
        foreach( $this->_testDataPaths as $environment => $path ){
            $fileScanner = new FileScanner($path);
            $testFiles = $fileScanner->getFilesInOneDimensionalArray();
            $this->_testFiles[$environment] = $testFiles;
        }
    }


    /**
     * Sets the testDatapaths by the implemented method getTestDataPaths
     * @return type
     * @throws ErrorException
     */
    private function _setTestDataPaths(){
        $testDataFiles = $this->getTestDataPaths();
        if( is_array($testDataFiles) ){
            $this->_testDataPaths = $testDataFiles;
            return;
        }
        throw new ErrorException( 'The method getTestDataPaths should return an Array of testDataPaths where the key is the enviornment and the value is the full path to your testfiles' );
    }
    
    /**
     * Gets the testDatafile by the SS_ENVIRONMENT variable (you can set it in config/project.ini)
     * @param type $filename provide Filename with extension
     */
    public function getTestDataFile( $filename ){
        if( !array_key_exists(SS_ENVIRONMENT, $this->_testFiles)){
            throw new ErrorException( 'There are no test datafiles provided in the getTestDataFiles' );
        }
        foreach( $this->_testFiles[SS_ENVIRONMENT] as $testFile ){
            $testFileName = pathinfo( $testFile, PATHINFO_BASENAME );
            if( $testFileName == $filename ){
                return $testFile;
            }
        }
        throw new ErrorException( 'Could not find the testdatafile you wanted for the environment: ' . SS_ENVIRONMENT );
    }
    /**
     * If your using seleniumShell on a grid in a network, provide the full path here including your
     * pc name. This way we will are able to add to files on fileupload forms.
     * @return array key should be the environment, value should be a string holding the path to your testDataFiles
     */
    abstract protected function getTestDataPaths();
    
}