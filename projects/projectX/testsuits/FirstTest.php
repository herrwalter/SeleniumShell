<?php

class FirstTestSuit extends SeleniumShell_Test {
    
    public function __construct() {
        
        parent::__construct();
    }
    
    public function testSeleniumShell()
    {
        $this->assertTrue( true );
    }
    
}
