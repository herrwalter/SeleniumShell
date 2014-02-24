<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class projectXChromeTwoTest extends SeleniumShell_Test
{

	public $browser = "Chrome";

    
    
    /**
     * @ss-solo-run true
     * @ss-browsers chrome
     */
    public function testNew2(){
    
        new BlaBlaTestAutoload();
        $this->assertTrue(true);
        
    }
    
}