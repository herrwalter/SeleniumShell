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
        $filterChain = new TestMethodsFilterChain($this->_testMethods, array(
            new SetupBeforeProjectReversedTestMethodsFilter(),
            new BrowserTestMethodsFilter(array(), $browser)));
        $testMethods = $filterChain->getFilteredTestMethods();

        foreach ($testMethods as $testMethod) {
            if ($testMethod->getStripMethodState()) {
                $this->_stripMethod($testMethod->getMethod());
            }
        }
    }

}
