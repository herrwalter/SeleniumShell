<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SoloRun_AnnotationCommand  implements Interface_Command{
    
    public function onCommand($name, $args) {
        
        $config = new ConfigHandler();
        if( $name !== 'solo-run'  ){
            return false;
        }
        if( $args['testMethods'] !== null ){
            throw new ErrorException( 'Annotation command needs "testMethods" in the args ' . $args['testMethods'] );
        }
        if( $config->isParameterSet('--ss-ignore-solo-run') ){
            return $args['testMethods'];
        }
        
        $soloRun = new SoloRun_AnnotationRule();
        // check if solo_run is applied on one of the methods.
        foreach( $args['testMethods'] as $testMethod){
            if( $testMethod ){
                $annotations = $testMethod->getAnnotations();
                if( $testMethod->hasAnnotations() && $annotations->hasSoloRun() ){
                    return $soloRun->filterMethods($args['testMethods']);
                }
            }
        }
        
        return $args['testMethods'];
        
        
    }

}