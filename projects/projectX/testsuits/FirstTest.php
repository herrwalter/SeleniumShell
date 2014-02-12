<?php

class FirstTestSuit extends SeleniumShell_Test {
    
    public function __construct() {
        
        parent::__construct();
    }
    
    /**
     * @solo-run true
     * @ss-browsers firefox,chrome
     */
    public function testSeleniumShell()
    {
        $this->assertTrue( true );
    }
    
}
