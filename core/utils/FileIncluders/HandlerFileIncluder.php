<?php





class HandlerFileIncluder extends FileIncluder
{
    public function __construct($dir) {
        parent::__construct($dir);
    }
    
    private function _isHandlerFile( $file )
    {
        $check = pathinfo($file);
        $filename = $check['filename'];
        return strpos($filename, 'Handler') > 0;
    }
    
    public function includeFiles()
    {
        foreach( $this->_files as $file ){
            if( $this->_isHandlerFile($file) ){
                $this->_includeFile($file);
            }
        }
    }
}