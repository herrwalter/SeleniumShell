<?php

class Application
{
    /** @var ConfigHandler */
    private $_config;
    
    public function __construct()
    {
        $this->_setConfig();
        $this->_instanciateTestSuits();
    }
    
    private function _setConfig()
    {
        $this->_config = new ConfigHandler();
    }
    
    private function _instanciateTestSuits()
    {
        $projects = $this->_config->getProjects();
        foreach($projects as $project )
        {
            $path = PROJECTS_FOLDER . '\\' . $project . '\testsuits';
            $fileIncluder = new PHPFileIncluder($path);
            $fileIncluder->includeFiles();
        }
    }
    
    private function _readDirRecursive( $dir )
    {
        
    }
}
