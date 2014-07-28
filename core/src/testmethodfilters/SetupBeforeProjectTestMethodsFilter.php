<?php

class SetupBeforeProjectTestMethodsFilter extends TestMethodsFilter
{
    protected function shouldFilter(\TestMethod $testMethod)
    {
        $annotations = $testMethod->getAnnotations();
        return $annotations->hasSetupBeforeProject();
    }

}
