<?php

abstract class TestMethodsFilter
{

    protected $testMethods;
    protected $filteredTestMethods = array();
    protected $filterted;
    protected $unfiltered;

    public function __construct(array $testMethods = array())
    {
        $this->testMethods = $testMethods;
    }
    
    public function setTestMethods($testMethods){
        $this->testMethods = $testMethods;
    }
    
    public function filterTestMethods()
    {
        foreach($this->testMethods as $testMethod ){
            if( $this->shouldFilter($testMethod) ){
                $testMethod->stripMethod();
            }
            $this->filteredTestMethods[] = $testMethod;
        }
    }
    
    public function getFilteredMethods()
    {
        return $this->filteredTestMethods;
    }

    /**
     * @return boolean if method should be filtered
     */
    abstract protected function shouldFilter( TestMethod $testMethod );
}
