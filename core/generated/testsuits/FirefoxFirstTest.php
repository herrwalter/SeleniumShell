<?php

class FirefoxFirstTestSuit extends SeleniumShell_Test {

	public $browser = "Firefox";

    
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
    
}
