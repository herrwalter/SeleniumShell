<?php

class SuiteCreator
{
    /**
     * @var TestSources
     */
    protected $_sources;
    /**
     * @var SeleniumShell_TestSuite 
     */
    protected $_suite;

    public function __construct( TestSources $sourceFiles ){
        $this->_sources = $sourceFiles;
        $this->_suite = new SeleniumShell_TestSuite();
        $this->includeSources();
        $this->addSourcesToSuite();
    }
    
    protected function includeSources()
    {
        foreach( $this->_sources->getFiles() as $file ){
            require_once $file;
        }
    }
    
    protected function addSourcesToSuite()
    {
        foreach($this->_sources->getClassNames() as $className ){
            $this->_suite->addTestSuite($className);
        }
    }
    
    public function getSuite()
    {
        return $this->_suite;
    }
    
}
