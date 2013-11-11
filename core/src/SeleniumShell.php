<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once('C:\wamp\bin\php\php5.3.13\pear\PHPUnit\Autoload.php');

class SeleniumShell extends PHPUnit_Extensions_Selenium2TestCase {
    
    /** @var ProjectActionsInitiator */
    public $actions;
    /** @var ProjectHandlersInitiator */
    public $handlers;
    

    public function setUp(){
        $this->setBrowser('chrome');
        $this->setBrowserUrl('https://www.brachyacademy.com/contact/');
        parent::setUp();
    }
    
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        
        $this->actions = new ProjectActionsInitiator();
        $this->handlers = new ProjectHandlersInitiator();
        parent::__construct($name, $data, $dataName);
        
        
    }

    
}
