<?php


class FileScanner
{
    protected $_dir;
    protected $_phpFiles;
    protected $_files;
    
    public function __construct( $relativeDir )
    {
        $this->_files = array();
        $this->_readDirRecursive( $relativeDir );
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
                    $this->_files[$dir][] = DIRECTORY_SEPARATOR . $file;
                } else {
                    $this->_readDirRecursive( $dir . DIRECTORY_SEPARATOR . $file );
                }
            }
        }
    }
    
    public function getFiles()
    {
        return $this->_files;
    }
    
    public function getFilesInOneDimensionalArray()
    {
        $foundFiles = array();
        foreach( $this->_files as $dir => $files )
        {
            foreach( $files as $file )
            {
                $foundFiles[] = $dir.$file;
            }
        }
        return $foundFiles;
    }
}
