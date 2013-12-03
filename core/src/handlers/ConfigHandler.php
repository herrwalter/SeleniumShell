<?php

class ConfigHandler
{
    private $_projects;
    
    public function __construct()
    {
        $this->_setProjects();
    }
    
    private function _setProjects()
    {
        $config = parse_ini_file(CORE_CONFIG_PATH . '/config.ini');
        $this->_projects = $config['projects'];
    }
    
    public function getProjects()
    {
        return $this->_projects;
    }
            
}
