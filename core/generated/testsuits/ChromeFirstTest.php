<?php

class ChromeFirstTestSuit extends SeleniumShell_Test {

	public $browser = "Chrome";

    
    public function __construct() {
        
        parent::__construct();
    }
    
    /**
     * @ss-browsers firefox
     */
    
    /**
     * @ss-browsers chrome,ie8
     */
    public function testSecond() {
        $this->assertTrue(true);
    }
}
