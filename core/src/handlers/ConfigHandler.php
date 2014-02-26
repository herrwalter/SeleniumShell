<?php

class ConfigHandler
{
    private $_projects;
    
    private $_path;
    
    private $_config;
    
    private $_parameters;
    
    public function __construct( $path = false )
    {
        if( $path ){
            $this->_setConfigPath( $path );
            $this->_setConfig();
        }
        $this->_setParameters();
    }
    
    private function _setConfigPath( $path )
    {
        $this->_path = $path;
    }
    
    private function _setParameters()
    {
        $phpunitVariables = new PHPUnitParameterReader();
        $this->_parameters = $phpunitVariables->getSeleniumShellVariables();
    }
    
    public function isParameterSet( $parameter ){
        if(key_exists($parameter, $this->_parameters)){
            return $this->_parameters[$parameter] !== false;
        }
        return false;
    }
    
    public function getParameter( $parameter ){
        return $this->_parameters[$parameter];
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
    /**
     * 
     * @return PHPUnitParameterReader 
     */
    public function getParameters(){
        return $this->_parameters;
    }
    
}
