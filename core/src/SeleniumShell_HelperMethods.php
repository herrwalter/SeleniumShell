<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SeleniumShell_HelperMethods extends PHPUnit_Extensions_Selenium2TestCase {
    
    /**
     * Select elements by css Class. 
     * 
     * @param type $selector
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function selectElementsByCssSelector( $selector ){
        return $this->elements( $this->using('css selector')->value($selector) );
    }

    /**
     * Gets an anchor by text, will return false if not found.
     * @param type $text
     */
    public function selectAnchorByText( $text ){
        $anchors = $this->selectElementsByCssSelector('a');
        foreach( $anchors as $a ){
            if( $a->text() == $text ){
                /** @var a PHPUnit_Extensions_Selenium2TestCase_Element*/
                return $a;
            }
        }
        return false;
    }
    
    public function getCurrentLocation(){
        return $this->execute(array(
            'script' => 'return window.location.href;',
            'args' => array(),
        ));
    }
    
}