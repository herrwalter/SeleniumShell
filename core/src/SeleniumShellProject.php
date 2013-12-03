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
    
    private $_projects = array();
    
    public function __construct()
    {
    }
    
    //abstract function _setConfig( $array );
    
    
    
    
}