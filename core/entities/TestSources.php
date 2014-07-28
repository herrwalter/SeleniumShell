<?php



class TestSources {
    
    protected $_files;     
    protected $_classNames = array();
    
    public function __construct( array $files )
    {
        $this->_files = $files;
        $this->_setClassNames();
    }
    
    protected function _setClassNames()
    {
        foreach($this->_files as $file )
        {
            $contents = file_get_contents($file);
            preg_match('/(class )(.*?)(\n|\{| extends|\r)/', $contents, $matches);
            if( array_key_exists(2, $matches) ){
                $this->_classNames[] = $matches[2];
            }
        }
    }
    
    public function getClassNames()
    {
        return $this->_classNames;
    }
    
    public function getFiles()
    {
        return $this->_files;
    }
    
    
}