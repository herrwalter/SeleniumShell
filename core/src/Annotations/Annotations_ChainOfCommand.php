<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Annotations_ChainOfCommand {
    
    private $_commands = array();
    
    public function addCommand($cmd){
        $this->_commands[] = $cmd;
    }
    
    public function runCommand( $name, $args ){
        foreach( $this->_commands as $cmd ){
            $return = $cmd->onCommand($name, $args);
            if( $return ){
                return $return;
            }
        }
    }
}