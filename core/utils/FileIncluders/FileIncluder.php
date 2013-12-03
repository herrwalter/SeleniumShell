<?php

/**
 * Will include the files by given directory
 */
class FileIncluder 
{
    /** @var string Directory to include */
    protected $_dir;
    /** @var array files  */
    protected $_files;
    /** @var FileScanner */
    protected $_scanner;
    
    protected $_includedFiles = array();
    
    public function __construct( $dir )
    {
        $this->_setDir( $dir );
        $this->_setScanner();
        $this->_setFiles();
    }
    
    
    protected function _setDir( $dir )
    {
        if( filetype($dir) == 'dir' ){
            $this->_dir = $dir;
        }
        else{
            throw new Exception('The following path is not a dir: ' . $dir . ' it is read a an: ' . filetype($dir));
        }
    }
    
    protected function _setScanner()
    {
        $this->_scanner = new FileScanner( $this->_dir );
    }
    
    protected function _setFiles()
    {
        $foundFiles = $this->_scanner->getFiles();
        foreach( $foundFiles as $dir => $filenames ){
            foreach( $filenames as $filename ){
                $this->_files[] = $dir . $filename;
            }
        }
    }
    
    protected function _includeFile( $file )
    {
        require_once($file);
        $this->_addToIncludedFiles($file);
    }
    
    private function _addToIncludedFiles( $file )
    {
        $this->_includedFiles[] = $file;
    }
    
    public function includeFiles()
    {
        foreach( $this->_files as $file ){
            $this->_includeFile($file);
        }
    }
    
    public function getInlcudedFiles()
    {
        return $this->_includedFiles;
    }
}