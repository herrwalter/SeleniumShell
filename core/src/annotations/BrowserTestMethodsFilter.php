<?php


class BrowserTestMethodsFilter extends TestMethodsFilter
{
    protected $browser;
    
    public function __construct($testMethods, $browser)
    {
        $this->browser = $browser;
        parent::__construct($testMethods);
    }


    protected function shouldFilter(\TestMethod $testMethod)
    {
        $annotations = $testMethod->getAnnotations();
        return $annotations && $annotations->hasBrowsersAnnotationSet() && !$annotations->hasBrowser($this->browser);
    }
}