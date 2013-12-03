<?php



class Project
{
    private $_name;
    
    private $_path;
    
    private $_testClassNames;
    
    public function __construct( $projectName )
    {
        $this->_setProjectName($projectName);
        $this->_setProjectPath();
        $this->_setProjectsTestClassNames();
    }
    
    private function _setProjectName( $projectName )
    {
        $this->_name = $projectName;
    }
    
    private function _setProjectPath()
    {
        $this->_path = PROJECTS_FOLDER . '\\' . $this->_name . '\testsuits';;
    }
    
    public function getProjectName()
    {
       return $this->_name;
    }
    
    public function getProjectPath()
    {
        return $this->_path;
    }
    
    public function getProjectsTestClassNames()
    {
        return $this->_testClassNames;
    }
    
    private function _setProjectsTestClassNames()
    {
        // first include the files
        $testFiles = new TestFileIncluder($this->_path);
        $testFiles->includeFiles();
        
        // then get the files that got included
        $includedFiles = $testFiles->getInlcudedFiles();
        
        // get their classnames
        $classInstantiator =  new ClassInstantiator($includedFiles);
        $this->_testClassNames = $classInstantiator->getClassNames();
        
    }
    
    
    
}