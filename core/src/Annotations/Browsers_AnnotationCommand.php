<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Browsers_AnnotationCommand implements Interface_Command {
    
    
    public function onCommand($name, $args) {
        if( $name !== 'browsers' ){
            return false;
        }
        $config = new ConfigHandler( CORE_CONFIG_PATH . DIRECTORY_SEPARATOR . '/config.ini');
        
        /**
         * If the browser value is overruled by the PHPUnit parameter this check is obsolete
         */
        $buildingForBrowser = $args['browser'];
        $browser = $config->getParameter('--ss-browsers');
        $browsers = explode(',', $browser);
        $browsers = array_map('trim', $browsers);
        if( in_array($args['browser'], $browsers) ){
            return $args['testMethods'];
        }
        
        $browsers = new Browsers_AnnotationRule($args['browser']);
        return $browsers->filterMethods($args['testMethods']);
    }
    

}