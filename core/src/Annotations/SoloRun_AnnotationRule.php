<?php

class SoloRun_AnnotationRule implements Interface_AnnotationRule{
    
    /**
     * @param SeleniumShell_TestMethod $testMethods
     */
    public function filterMethods($testMethods) {
        $this->methods = $testMethods;
        foreach( $testMethods as $key => $testMethod ){
            $annotations = $testMethod->getAnnotations();
            if( $annotations->hasSoloRun() ){
                $testMethods[$key] = false;
            }
        }
        return $testMethods;
    }

}