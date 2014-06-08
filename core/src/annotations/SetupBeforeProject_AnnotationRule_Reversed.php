<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SetupBeforeProject_AnnotationRule_Reversed implements Interface_AnnotationRule{
    
    /**
     * @param TestMethod $testMethods
     */
    public function filterMethods($testMethods) {
        foreach( $testMethods as $key => $testMethod ){
            $annotations = $testMethod->getAnnotations();
            if( $annotations->hasSetupBeforeProject() ){
                $testMethods[$key]->stripMethod();
            } 
        }
        return $testMethods;
    }

}