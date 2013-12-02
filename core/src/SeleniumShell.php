<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include('D:\wamp\bin\php\php5.4.3\pear\PHPUnit\Autoload.php');

class SeleniumShell extends PHPUnit_Extensions_Selenium2TestCase {
    
    /** @var ProjectActionsInitiator */
    public $actions;
    /** @var ProjectHandlersInitiator */
    public $handlers;
    /** @var array childClasses all instances of selenium */
    public $childClasses;
    /** @var boolean initialized Indicates if SeleniumShell is initialized */
    public $initialized = false;

    public function setUp(){
        $this->setBrowser('chrome');
        $this->setBrowserUrl('http://www.google.nl');
        parent::setUp();
    }
    
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        if( !$this->initialized ){
            $this->_bootstrap();
            $this->_setInitialisation();
        }
        
        $this->_includeProjectTests();

        //$this->actions = new ProjectActionsInitiator();
        //$this->handlers = new ProjectHandlersInitiator();
        parent::__construct($name, $data, $dataName);
        
        
    }
    
    /**
     * Sets initialisation state
     */
    private function _setInitialisation()
    {
        $this->initialized = true;
    }
    
    /**
     * Bootstraps the projects
     */
    private function _bootstrap()
    {
        $rel_path = substr(str_replace('\\', '/', realpath(dirname(__FILE__))), strlen(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']))));
        require_once( $rel_path . '/../bootstrap.php' );
    }

    
    /**
     * Will get the tests of the current
     * project
     */
    final protected function _includeProjectTests()
    {
        require_once( PROJECTS_FOLDER . $projectFolder . '/testsuits/FirstTestSuit.php' );
    }
    
}

