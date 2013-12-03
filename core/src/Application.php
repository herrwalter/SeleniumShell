<?php

class Application
{
    /** @var ConfigHandler */
    private $_config;
    
    /** @var PHPUnit_Framework_TestSuite*/
    private $_suite;
    
    private $_projects;
    
    public function __construct()
    {
        $this->_setConfig();
        $this->_setProjects();
        $this->_setSuite();
    }
    /**
     * Sets the testsuite
     * @param type $suite
     */
    private function _setSuite()
    {
        $testsuiteInitiator = new TestSuiteInitiator($this->_projects);
        $this->_suite = $testsuiteInitiator->getTestSuite();
    }
    
    /**
     * Set config from ini file in core/config/config.ini
     */
    private function _setConfig()
    {
        $this->_config = new ConfigHandler();
    }
    
    
    private function _setProjects()
    {
        $projects = array();
        $projectNames = $this->_config->getProjects();
        foreach($projectNames as $projectName)
        {
            $projects[$projectName] = new Project($projectName);
        }
        $this->_projects = $projects;
    }
    
    
    public function getTestSuite()
    {
        return $this->_suite;
    }
}
