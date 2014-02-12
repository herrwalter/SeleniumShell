<?php


/**
 * Will include all the testfiles to the session 
 * of the give directory.
 */
class TestFileIncluder extends FileIncluder
{
    public function __construct($dir) {
        parent::__construct($dir);
    }
    
    private function _isTestFile( $file )
    {
        $check = pathinfo($file);
        $filename = $check['filename'];
        return strpos($filename, 'Test') > 0;
    }
    
    public function includeFiles()
    {
        foreach( $this->_files as $file ){
            if( $this->_isTestFile($file) ){
                $this->_includeFile($file);
            }
        }
    }
}