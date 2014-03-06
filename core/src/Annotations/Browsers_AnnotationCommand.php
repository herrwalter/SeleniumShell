<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Browsers_AnnotationCommand implements Interface_Command {
    
    private $_allowed = false;
    
    public function onCommand($name, $args) {
        if( $name !== 'browsers' ){
            return false;
        }
        
        $config = new ConfigHandler( CORE_CONFIG_PATH . DIRECTORY_SEPARATOR . '/config.ini');
        if( !$config->getParameter('--ss-browsers') ){
            $this->_allowed = true;
        }
        
        $browsers = new Browsers_AnnotationRule($args['browser']);
        return $browsers->filterMethods($args['testMethods']);
    }
    

}