<?php

// Require SeleniumShell
require( 'SeleniumShell.php' );
/**
 * This will cover the config setup of your SeleniumShell project
 * 
 */
abstract class SeleniumShellProject extends SeleniumShell
{
    /** @var string projectName name of the project */
    public $projectName;
    /** @var string emailResults email to mail results to */
    public $emailResults;
    /** @var string projectFolder */
    public $projectFolder;
    
    public function __construct( $config )
    {
        
    }
    
    abstract function _setConfig( $array );
    
    public function setUp()
    {
        $this->_includeProjectTests();
    }
    
    protected function _includeProjectTests()
    {
        
    }
    
    
}