<?php



class Project
{
    private $_name;
    
    private $_path;
    
    private $_testClassNames;
    
    private $_config;
    
    public function __construct( $projectName )
    {
        $this->_setProjectName($projectName);
        $this->_setProjectPath();
        $this->_setProjectConfig();
        $this->_setProjectsTestClassNames();
    }
    
    private function _setProjectName( $projectName )
    {
        $this->_name = $projectName;
    }
    
    private function _setProjectPath()
    {
        $this->_path = PROJECTS_FOLDER . '\\' . $this->_name;;
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
    private function _setProjectConfig()
    {
        $this->_config = new ConfigHandler($this->_path . '\config\project.ini');
    }
    
    private function _setProjectsTestClassNames()
    {
        // first include the files
        $testFiles = new TestFileIncluder($this->_path . '\testsuits');
        $testFiles->includeFiles();
        
        
        // then get the files that got included
        $includedFiles = $testFiles->getInlcudedFiles();
        $refinedFiles = false;
        
        /**
         * TODO: come up with a design to implement changes by the annotations
         */
        foreach( $includedFiles as $key => $file ){
            $tcr = new TestClassReader($file);
            $tests = $tcr->getTestMethods();
            foreach( $tests as $testMethod ){
                $annotationsOnTestMethod = new AnnotationReader($testMethod);
                if( $annotationsOnTestMethod->hasSoloRun() ){
                    $refinedFiles = array($file);
                    break;
                }
            }
        }
        
        if( $refinedFiles ){
            $includedFiles = $refinedFiles;
        }
        
        // get their classnames
        $classInstantiator =  new ClassInstantiator($includedFiles);
        $this->_testClassNames = $classInstantiator->getClassNames();
        
        
        
    }
    
    
    
}