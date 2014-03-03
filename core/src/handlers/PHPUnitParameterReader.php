<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class  PHPUnitParameterReader {
    
    protected $seleniumShellVariables = array(
        '--ss-project' => false,
        '--ss-email-results' => false,
        '--ss-testsuite' => false,
        '--ss-browsers' => false,
        '--ss-env' => false
    );
    
    public function __construct(){
        $this->_findSeleniumShellVariables();
    }
    
    protected function _findSeleniumShellVariables()
    {
        global $argv;
        foreach( $this->seleniumShellVariables as $ssVar => $value){
            $key = array_search($ssVar, $argv);
            if( $key || $key === 0 ){
                $this->seleniumShellVariables[$ssVar] = $argv[$key + 1];
            }
        }
    }
    
    public function getSeleniumShellVariables(){
        return $this->seleniumShellVariables;
    }
}