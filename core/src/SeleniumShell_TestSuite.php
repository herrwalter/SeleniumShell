<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SeleniumShell_TestSuite extends PHPUnit_Framework_TestSuite{
    
    public function __construct($theClass = '', $name = '')
    {
        parent::__construct($theClass, $name);
    }
    
    public function run(\PHPUnit_Framework_TestResult $result = NULL, $filter = FALSE, array $groups = array(), array $excludeGroups = array(), $processIsolation = FALSE)
    {
        $result->addListener(new SeleniumShell_TestListener());
        parent::run($result, $filter, $groups, $excludeGroups, $processIsolation);
    }
}