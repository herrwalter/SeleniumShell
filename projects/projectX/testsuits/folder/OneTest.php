<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class OneTest extends SeleniumShell_Test {
    
    public function __construct() {
        parent::__construct('Wouter');
    }
    
    public function testAutoLoad()
    {
        $this->assertTrue( true );
    }
    
    public function testSecondTest()
    {
        $this->assertFalse( true );
    }
}