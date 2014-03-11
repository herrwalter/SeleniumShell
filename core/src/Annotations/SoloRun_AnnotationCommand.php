<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SoloRun_AnnotationCommand  implements Interface_Command{
    
    public function onCommand($name, $args) {
        if( $name !== 'solo-run' ){
            return false;
        }
        
        $soloRun = new SoloRun_AnnotationRule();
        return $soloRun->filterMethods($args['testMethods']);
        
    }

}