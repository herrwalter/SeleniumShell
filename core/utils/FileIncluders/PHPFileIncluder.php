<?php



class PHPFileIncluder extends FileIncluder
{
    
    public function __construct($dir) {
        parent::__construct($dir);
    }
    
    private function _isPhpFile( $file )
    {
        $check = pathinfo($file);
        return $check['extension'] === 'php';
    }
    
    public function includeFiles()
    {
        foreach( $this->_files as $file ){
            if( $this->_isPhpFile($file) ){
                require_once($file);
            }
        }
    }
    
}