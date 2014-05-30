<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SetupSuiteInitiator extends TestSuiteInitiator {
    
    
     protected function _addTestsToSuite()
     {
        foreach( $this->_projects as $project )
        {
            $this->_addTestSuitesByClassNames($project->getSetupBeforeProjectClassNames());
        }
     }
    
}