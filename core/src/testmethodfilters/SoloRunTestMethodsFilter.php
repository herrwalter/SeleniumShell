<?php


class SoloRunTestMethodsFilter extends TestMethodsFilter
{
    protected $filterApplies;
    
    public function __construct(array $testMethods = array())
    {
        parent::__construct($testMethods);
        $this->setFilterApplies();
    }
    
    public function setTestMethods($testMethods)
    {
        parent::setTestMethods($testMethods);
        $this->setFilterApplies();
    }

    /**
     * @return type
     */
    protected function setFilterApplies()
    {
        $this->filterApplies = false;
        foreach($this->testMethods as $testMethod ){
            $annotations = $testMethod->getAnnotations();
            if($annotations->hasSoloRun()){
                $this->filterApplies = true;
                return;
            }
        }
    }

    protected function shouldFilter(\TestMethod $testMethod)
    {
        if( $this->filterApplies ){
            $annotations = $testMethod->getAnnotations();
            return !$annotations->hasSoloRun();
        }
    }
}