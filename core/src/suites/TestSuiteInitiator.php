<?php


/**
 * Will add the Tests from every defined project to a newly 
 * created PHPUnit_Framework_TestSuite.
 */
class TestSuiteInitiator
{
    /** @var PHPUnit_Framework_TestSuite */
    protected $_suite;
    
    /** @var Project*/
    protected $_projects;
    /**
     * 
     * @param PHPUnit_Framework_TestSuite $suite
     * @param array $projects
     */
    public function __construct( $projects )
    {
        $this->_suite = new SeleniumShell_TestSuite();
        $this->_projects = $projects;
        $this->_addTestsToSuite();
    }
    
    protected function _addTestsToSuite()
    {
        foreach( $this->_projects as $project )
        {
            $this->_addTestSuitesByClassNames($project->getProjectsTestClassNames());
        }
    }
    
    protected function _addTestSuitesByClassNames( $classNames )
    {
        foreach($classNames as $className )
        {
            $this->_suite->addTestSuite($className);
        }
    }
    
    public function getTestSuite()
    {
        return $this->_suite;
    }
}