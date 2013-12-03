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
            
            $dir = new DirectoryScanner($path);
            var_dump($dir->getFiles());
        }
    }
    
    private function _readDirRecursive( $dir )
    {
        
    }
}
