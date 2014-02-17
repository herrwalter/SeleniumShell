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
        $this->_config = new ConfigHandler( CORE_CONFIG_PATH . '/config.ini');
    }
    
    
    private function _setProjects()
    {
        $projects = array();
        $projectNames = $this->_config->getAttribute('projects');
        
        if( $this->_config->isParameterSet('--ss-project') ){
            $projectName = $this->_config->getParameter('--ss-project');
            $projects[$projectName] = new Project($projectName);
        }
        else{
            /**
             * @TODO: crawl projects in projects folder
             */
        }
        $this->_projects = $projects;
    }
    
    
    public function getTestSuite()
    {
        return $this->_suite;
    }
}
