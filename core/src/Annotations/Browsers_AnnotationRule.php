<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Browsers_AnnotationRule implements Interface_AnnotationRule {
    
    private $browser;
    
    public function __construct($browser ){
        $this->browser = $browser;
    }
    /**
     * @param SeleniumShell_TestMethod $testMethods
     */
    public function filterMethods($testMethods) {
        foreach($testMethods as $key => $testMethod ){
            if( $testMethod !== false ){
                $annotations = $testMethod->getAnnotations();
                if( $annotations->hasBrowser($this->browser) ){
                    $testMethods[$key] = false;
                }
            }
        }
        return $testMethods;
    }

}