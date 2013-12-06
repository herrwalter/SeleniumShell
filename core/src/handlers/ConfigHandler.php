<?php

class ConfigHandler
{
    private $_projects;
    
    private $_path;
    
    private $_config;
    
    public function __construct( $path )
    {
        $this->_setConfigPath( $path );
        $this->_setConfig();
    }
    
    private function _setConfigPath( $path )
    {
        $this->_path = $path;
    }
    
    private function _setConfig()
    {
        $this->_config = parse_ini_file($this->_path);
    }
    
    /**
     * returns the attribute of the config
     * @param type $attr
     * @return boolean
     */
    public function getAttribute( $attr )
    {
        if(array_key_exists($attr, $this->_config) ){
            return $this->_config[$attr];
        }
        return false;
    }
    
}
