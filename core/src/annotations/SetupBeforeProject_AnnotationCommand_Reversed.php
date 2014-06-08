<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SetupBeforeProject_AnnotationCommand_Reversed  implements Interface_Command{
    
    public function onCommand($name, $args) {
        
        $config = new ConfigHandler();
        if( $name !== 'setup-before-project'  ){
            return false;
        }
        if( !isset($args['testMethods']) ){
            throw new ErrorException( 'Annotation command needs "testMethods" in the args ' );
        }
        
        $setupBeforeProject = new SetupBeforeProject_AnnotationRule_Reversed();
        // check if solo_run is applied on one of the methods.
        foreach( $args['testMethods'] as $testMethod){
            if( $testMethod ){
                $annotations = $testMethod->getAnnotations();
                if( $testMethod->hasAnnotations() && $annotations->hasSetupBeforeProject() ){
                    return $setupBeforeProject->filterMethods($args['testMethods']);
                }
            }
        }
        
        return $args['testMethods'];
        
        
    }

}