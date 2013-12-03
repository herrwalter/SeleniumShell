<?php

include('C:\wamp\bin\php\php5.3.13\pear\PHPUnit\Autoload.php');
include('C:\wamp\bin\php\php5.3.13\pear\PHPUnit\TestSuite.php');


class SeleniumShell extends PHPUnit_Extensions_Selenium2TestCase {
    
    /** @var ProjectActionsInitiator */
    public $actions;
    /** @var ProjectHandlersInitiator */
    public $handlers;
    /** @var array childClasses all instances of selenium */
    public $childClasses;
    /** @var boolean initialized Indicates if SeleniumShell is initialized */
    public $initialized = false;
    
    public $project;

    public function setUp(){
        $this->setBrowser('iexplore');
        $this->setBrowserUrl('http://www.google.nl');
        $this->setHost('127.0.0.1');
        $this->setPort(4444);
        $this->setSeleniumServerRequestsTimeout(5000);
        $this->setDesiredCapabilities(array());
        //$this->_includeProjectTests(get_class($this));
    }
    
    public function __construct()//$name = NULL, array $data = array(), $dataName = '')
    {
        if( !$this->initialized ){
            $this->_bootstrap();
            $this->_setInitialisation();
        }
        
        //$this->_includeProjectTests(get_class($this));

        //$this->actions = new ProjectActionsInitiator();
        //$this->handlers = new ProjectHandlersInitiator();
        parent::__construct();//$name, $data, $dataName);
        
        
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
    public static function _bootstrap()
    {
        $rel_path = substr(str_replace('\\', '/', realpath(dirname(__FILE__))), strlen(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']))));
        
        include( 'bootstrap.php' );
    }

    /**
     * Will get the tests of the current
     * project
     */
    final protected function _includeProjectTests( $projectName )
    {
        
//        include( PROJECTS_FOLDER . '/' . $projectName . '/testsuits/FirstTest.php' );
//        $suite = new PHPUnit_TestSuite();
//        $suite->addTestSuite('FirstTestSuit');
        
        
        //$test = new FirstTestSuit();
        //$test->testSeleniumShell();
    }
    
    public static function suite($suite) {
        //include( PROJECTS_FOLDER . '/' . 'projectX' . '/testsuits/FirstTest.php' );
        self::_bootstrap();
        $app = new Application();
        $config = parse_ini_file('/config/config.ini');
        include( '..\projects\projectX\testsuits\FirstTest.php' );
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestSuite('FirstTestSuit');
        return $suite;
    }
    
    
    
    
    
}

