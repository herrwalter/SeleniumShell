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
        $this->_initializeProjects();
        $this->_setEnvironmentConstant();
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
    
    
    private function _initializeProjects()
    {
        $projects = array();
        $projectNames = $this->_config->getAttribute('projects');
        /* if --ss-project parameter is set, initialize that project */
        /* elseif the seleniumshell config contains the projects parameter,  use those */
        /* else initialize all projects in the project folder */
        if( $this->_config->isParameterSet('--ss-project') ){
            $projectName = $this->_config->getParameter('--ss-project');
            $projects[$projectName] = new Project($projectName);
        }elseif( $projectNames ){
            foreach( $projectNames as $projectName ){
                $projects[$projectName] = new Project($projectName);
            }
        }else{
            $folder = scandir( PROJECTS_FOLDER );
            foreach( $folder as $dir ){
                if( !( $dir == '.' || $dir == '..' ) && !is_file($dir) ){
                    $projects[$dir] = new Project($dir);
                }
            }
        }
        $this->_projects = $projects;
    }
    
    
    public function getTestSuite(){
        return $this->_suite;
    }
    
    public function _setEnvironmentConstant(){
        
        /**
         * If --ss-project is set, we will only run that project, so we have to check
         * if we need to use that config for setting the project environment.
         */
        $project = $this->_config->getParameter('--ss-project');
        if( $project ){
            $projectConfig = new ConfigHandler(PROJECTS_FOLDER . '\\' . $project . '\\config\\project.ini');
        }
        
        if( $this->_config->isParameterSet('--ss-env') ){
            define( 'SS_ENVIRONMENT', $this->_config->getParameter('--ss-env') );
        } else if( $projectConfig !== null && $projectConfig->getAttribute('project-environment') ){
            define( 'SS_ENVIRONMENT', $projectConfig->getAttribute('project-environment'));
        } else if( $this->_config->getAttribute('project-environment') ) {
            define( 'SS_ENVIRONMENT', $this->_config->getAttribute('project-environment'));
        } else {
            define( 'SS_ENVIRONMENT', false );
        }
    }
}
