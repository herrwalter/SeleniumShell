<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ChromeOneTest extends SeleniumShell_Test {

	public $browser = "Chrome";

    
    public function __construct() {
        parent::__construct('Wouter');
    }
    
    /**
     * @ss-solo-run true
     * @ss-browsers FireFox,Chrome
     */
    public function testAutoLoad()
    {
        $this->assertTrue( true );
    }
    
    
}