<?php


class SeleniumShell extends PHPUnit_Extensions_Selenium2TestCase {
    
    /** @var boolean initialized Indicates if SeleniumShell is initialized */
    public $initialized = false;
    
    public $project;
    
    /* @var Application */
    private static $_app;
    
    public function __construct()//$name = NULL, array $data = array(), $dataName = '')
    {
        if( !$this->initialized ){
            $this->_bootstrap();
            $this->_setInitialisation();
        }
        $this->_app = new Application();
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

    public function __destruct()
    {
        session_destroy();
    }
    
    public static function suite($suite) {
        self::_bootstrap();
        $app = new Application();
        $suite = $app->getTestSuite();
        return $suite;
    }
    
    
    
      
    
}

