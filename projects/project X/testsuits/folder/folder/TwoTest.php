<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class TwoTest extends SeleniumShell_Test
{
    
    public function testNew(){
    
        $this->assertTrue(true);
    }
    /**
     * @ss-solo-run true
     * @ss-browsers chrome
     */
    public function testNew2(){
    
        new BlaBlaTestAutoload();
        $this->assertTrue(true);
        
    }
    public function testNew4(){
    
        
    }
}