<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../../../core/src/SeleniumShell.php';

class FirstTestSuit extends SeleniumShell{
    
    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->actions->ExampleAction->echoActionName();
        $this->handlers->ExampleHandler->echoHandlerName();
        
    }
    
    protected function setUp(){
        
    }
    
    protected function tearDown(){
        
    }
}


new FirstTestSuit();