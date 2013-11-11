<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ProjectHandlersInitiator
{
    
    public function __construct()
    {
        $this->_setProjectActions();
    }
    
    private function _setProjectActions(){
        if ($handle = opendir( PROJECT_NAME . '\handlers' )) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    try{
                        $file = explode( '.php', $entry ); 
                        require_once( PROJECT_NAME . '\handlers\\' . $entry );
                        $this->__set($file[0], eval( 'return new ' . $file[0] . '();' ));
                    }
                    catch( Exeption $e ){}
                 }
            }
            closedir($handle);
        }
    }
    
    public function __set($key, $val) {
        return $this->$key = $val;
    }
    
}