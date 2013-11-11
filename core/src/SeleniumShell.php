<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'handlers/ProjectActionsInitiator.php';
require_once 'handlers/ProjectHandlersInitiator.php';

class SeleniumShell {
    
    public $actions;
    public $handlers;
    

    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        
        $this->actions = new ProjectActionsInitiator();
        $this->handlers = new ProjectHandlersInitiator();
        //parent::__construct($name, $data, $dataName);
        
        
    }

    
}
