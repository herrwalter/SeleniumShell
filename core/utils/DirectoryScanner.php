<?php


class DirectoryScanner
{
    protected $_dir;
    protected $_phpFiles;
    protected $_files;
    
    public function __construct( $relativeDir )
    {
        $this->_files = array();
        $this->_readDirRecursive( $relativeDir, $this->_files );
        $this->_setPhpFiles();
    }
    
    private function _readDirRecursive( $dir )
    {
        try{
            $curDirFiles = scandir($dir);
        } catch( Exeption $e ) {
            return $files;
        }
        if( $curDirFiles ){
            foreach( $curDirFiles as $file ){
                if( is_dir($file) ){
                    // . and .. skipped
                } else if ( is_file( $dir . DIRECTORY_SEPARATOR . $file) ){
                    $this->_files[$dir] = $file;
                } else {
                    $this->_readDirRecursive( $dir . DIRECTORY_SEPARATOR . $file );
                }
            }
        }
    }
    
    /**
     * Checks if the file is a php file.
     * @param type $file
     * @return type
     */
    protected function _isPhpFile( $file )
    {
        $check = pathinfo($file);
        return $check['extension'] === 'php';
    }
    
    protected function _setPhpFiles()
    {
        foreach( $this->_files as $file ){
            if( $this->_isPhpFile($file) ){
                $this->_phpFiles[] = $file;
            }
        }
    }
    public function getPhpFiles()
    {
        return $this->_phpFiles;
    }
    
    public function includePhpFiles()
    {
        foreach( $this->_phpFiles as $file )
        {
            require_once( $file );
        }
    }
    
    public function getFiles()
    {
        return $this->_files;
    }
}
