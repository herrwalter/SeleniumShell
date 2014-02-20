<?php

class FirstTestSuit extends SeleniumShell_Test {
    
    public function __construct() {
        
        parent::__construct();
    }
    
    /**
     * @ss-browsers firefox
     */
    public function testSeleniumShell()
    {
        $this->assertTrue( true );
    }
    /**
     * @ss-browsers chrome,ie8
     */
    public function testSecond() {
        $this->assertTrue(true);
    }
}
