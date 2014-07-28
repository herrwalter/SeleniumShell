<?php



class TestMethodsFilterChain
{
    protected $testMethods;
    protected $filters;
    
    public function __construct( array $testMethods, array $filters)
    {
        $this->testMethods = $testMethods;
        $this->filters = $filters;
        $this->runChain();
        
    }
    
    protected function runChain()
    {
        foreach($this->filters as $testMethodsFilter )
        {
            if( $testMethodsFilter instanceof TestMethodsFilter ){
                $testMethodsFilter->setTestMethods($this->testMethods);
                $testMethodsFilter->filterTestMethods();
                $this->testMethods = $testMethodsFilter->getFilteredMethods();
            } else {
                throw new ErrorException( get_class($testMethodsFilter) . ' is not an instance of TestMethodsFilter' );
            }
        }
    }
    
    public function getFilteredTestMethods()
    {
        return $this->testMethods;
    }
    
}