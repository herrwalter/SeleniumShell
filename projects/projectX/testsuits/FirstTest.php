<?php

class FirstTestSuit extends SeleniumShell_Test {
    
    public function __construct() {
        var_dump('FirstTestSuit extended');
        parent::__construct('Wouter');
    }
    
    public function testSeleniumShell()
    {
        $this->assertTrue( true );
    }
    
}
