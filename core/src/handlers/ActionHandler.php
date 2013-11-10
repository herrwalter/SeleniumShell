<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ProjectActionHandler {
    
    public function __construct(){
        $this->_assignUtilsToProject();
    }
    
    private function _assignUtilsToProject(){
        var_dump(getcwd());
    }
    
}