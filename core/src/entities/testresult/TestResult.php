<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class TestResult {
    
    protected $_testName;
    protected $_exitcode;
    
    public function __construct($testName = '', $exitcode = -1 )
    {
        $this->setTestName($testName);
        $this->setExitcode($exitcode);
    }
    
    public function setTestName( $testName ){
        $this->_testName = $testName;
    }
    
    public function setExitcode( $exitcode ){
        $this->_exitcode = intval($exitcode);
    }
    
    public function getTestName(){
        return $this->_testName;
    }
    
    public function getExitcode(){
        return $this->_exitcode;
    }
    
    abstract function toString();
}