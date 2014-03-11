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
     * if a method is set to false, it will not be deleted.
     * @param SeleniumShell_TestMethod $testMethods
     */
    public function filterMethods($testMethods) {
        // loop over testmethods
        foreach($testMethods as $key =>$testMethod ){
            // get method's annotations
            $annotations = $testMethod->getAnnotations();
            // if the browser annotation is set and it contains te browser, set it to false.
            if( $annotations && 
                    $annotations->hasBrowsersAnnotationSet() && 
                    !$annotations->hasBrowser($this->browser) ){
                $testMethod->stripMethod();
            }
        }
        return $testMethods;
    }

}