<?php


/**
 * Provide it with an array of filenames
 * and it will give you functionality to 
 * instansiate a class of those files.
 */
class ClassInstantiator
{
    private $_files;
    
    private $_classNames = array();
    
    private $_classes = array();
    /**
     * @param array $files Array with full paths to files
     */
    public function __construct( $files )
    {
        $this->_setFiles($files);
        $this->_setClassNames();
        $this->_setClasses();
    }
    
    private function _setFiles($files)
    {
        $this->_files = $files;
    }
    
    private function _setClassNames()
    {
        foreach( $this->_files as $file )
        {
            $className = $this->_getClassNameFromFile($file);
            if( $className ){
                $this->_classNames[] = $className;
            }
        }
    }
    /**
     * Gets the className form files. 
     * Will assus you use only one class per file.
     * @param type $file
     */
    private function _getClassNameFromFile( $file )
    {
        $contents = file_get_contents($file);
        preg_match('/(class )(.*?)(\n|\{| extends|\r)/', $contents, $matches);
        if( array_key_exists(2, $matches) ){
            return $matches[2];
        }
        return false;
    }
    
    public function getClassNames()
    {
        return $this->_classNames;
    }
    /**
     * Will instantiate classes to $_classes
     */
    private function _setClasses()
    {
        foreach( $this->_classNames as $className )
        {
            
        }
    }
}