<?php

class SoloRun_AnnotationRule implements Interface_AnnotationRule{
    
    /**
     * @param TestMethod $testMethods
     */
    public function filterMethods($testMethods) {
        foreach( $testMethods as $key => $testMethod ){
            $annotations = $testMethod->getAnnotations();
            if( !$annotations->hasSoloRun() ){
                $testMethods[$key]->stripMethod();
            } 
        }
        return $testMethods;
    }

}