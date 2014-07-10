<?php

class ConfigHandler
{
    private $_projects;
    
    private $_path;
    
    private $_config;
    
    private $_parameters;
    
    private $_process_section;
    
    public function __construct( $path = false, $process_section = false )
    {
        $this->_process_section = $process_section;
        if( $path ){
            $this->_setConfigPath( $path );
            $this->_setConfig();
        }
        $this->_setParameters();
    }
    
    private function _setConfigPath( $path )
    {
        $this->_path = $path;
        chmod( $this->_path, 0644 );
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
        $this->_config = parse_ini_file($this->_path, $this->_process_section);
    }
    
    public function getConfig(){
        return $this->_config;
    }
    
    /**
     * returns the attribute of the config
     * @param type $attr
     * @return boolean
     */
    public function getAttribute( $attr, $section = false )
    {
        if( $section && !array_key_exists($section, $this->_config)){
            return false;
        } else if( 
            $section && 
            array_key_exists($section, $this->_config) && 
            array_key_exists($attr, $this->_config[$section]) 
            ){
            return $this->_config[$section][$attr];
        } else if( array_key_exists($attr, $this->_config ) ){
            return $this->_config[$attr];
        } else {
            return false;
        }
    }
    

    public function sectionExists( $section ){
        return isset($this->_config[$section]);
    }
    
    /**
     * 
     * @return PHPUnitParameterReader 
     */
    public function getParameters(){
        return $this->_parameters;
    }
    
    
    public function addAttributeValue($attribute, $section = false, $value){
        if($section && !$this->sectionExists($section)){
            throw new ErrorException($section . ' does not exist as section in current config');
            return;
        }
        $currentAttributeValues = $this->getAttribute($attribute, $section);
        $configContents = file_get_contents($this->_path);
        $configContentLines = explode(PHP_EOL, $configContents);
        foreach($configContentLines as $index => $line ){
            if(strpos($line, $attribute.'[]') !== false){
                array_splice($configContentLines, $index, 0, $attribute.'[] = '.$value);
                break;
            }
        }
        file_put_contents($this->_path, implode(PHP_EOL, $configContentLines));
        $this->_setConfig();
    }
    
    public function addSection($section, array $values){
        if($this->sectionExists($section)){
            throw new ErrorException($section . ' section allready exists' );
        }
        
        $config = PHP_EOL . '[' . $section . ']' . PHP_EOL;
        foreach($values as $key => $value ){
            $config .= $key . ' = ' . $value . PHP_EOL;
        }
        file_put_contents($this->_path, $config, FILE_APPEND);
        $this->_setConfig();
    }
    
    public function removeSection( $section ){
        if( $this->sectionExists($section) ){
            
        }
    }
}
