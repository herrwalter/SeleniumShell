<?php



class TestSuiteInitiator
{
    /** @var PHPUnit_Framework_TestSuite */
    private $_suite;
    
    /** @var Project*/
    private $_projects;
    /**
     * 
     * @param PHPUnit_Framework_TestSuite $suite
     * @param array $projects
     */
    public function __construct( $projects )
    {
        $this->_setSuite();
        $this->_setProjects( $projects );
        $this->_addTestsToSuite();
                
    }
    
    private function _setProjects( $projects )
    {
        $this->_projects = $projects;
    }
    
    private function _setSuite()
    {
        $this->_suite = new PHPUnit_Framework_TestSuite();
    }
    
    private function _addTestsToSuite()
    {
        foreach( $this->_projects as $project )
        {
            $this->_addTestSuitsByClassNames($project->getProjectsTestClassNames());
        }
    }
    
    private function _addTestSuitsByClassNames( $classNames )
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