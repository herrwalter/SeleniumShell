<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SeleniumShell extends PHPUnit_Extensions_Selenium2TestCase{
    
    public $actions;
    
    public function __construct($name = NULL, array $data = array(), $dataName = ''){
        
        $this->actions = new ActionHandler();
        parent::__construct($name, $data, $dataName);
        
        
    }
    /**
     * 
     */
    function setUp() {
        new ProjectActionHandler();
        parent::setUp();
    }
    
}
