<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SetupBeforeProjectTestClassRecreator extends TestClassRecreator
{

    protected function _deleteTestsThatShouldNotRunInThisBrowser($browser)
    {
        $commandChain = new Annotations_ChainOfCommand();
        $commandChain->addCommand(new Browsers_AnnotationCommand());
        $commandChain->addCommand(new SetupBeforeProject_AnnotationCommand());

        $testMethods = $this->_testMethods;
        $testMethods = $commandChain->runCommand('setup-before-project', array('testMethods' => $testMethods));
        $testMethods = $commandChain->runCommand('browsers', array('testMethods' => $testMethods, 'browser' => $browser));

        foreach ($testMethods as $testMethod) {
            if ($testMethod->getStripMethodState()) {
                $this->_stripMethod($testMethod->getMethod());
            }
        }
    }

}
