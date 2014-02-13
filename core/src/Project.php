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
        
        
        // first find the testfiles in this project.
        $testFileScanner = new TestFileScanner( $this->_path . '\testsuits');
        $testFiles = $testFileScanner->getFilesInOneDimensionalArray();
        
        // get default browsers
        $config = new ConfigHandler(CORE_CONFIG_PATH . '\config.ini');
        $browsers = $config->getAttribute('browsers');
        
        // overwrite browsers with project default
        $projectBrowserSettings = $this->_config->getAttribute('browsers');
        if( !empty($projectBrowserSettings)){
            $browsers = $projectBrowserSettings;
        }
        
        $generatedTestsuitPath = GENERATED_PATH . DIRECTORY_SEPARATOR . 'testsuits' . DIRECTORY_SEPARATOR;
        
        // delete old testfiles
        $fileScanner = new FileScanner($generatedTestsuitPath );
        $generatedTestFiles = $fileScanner->getFilesInOneDimensionalArray();
        foreach( $generatedTestFiles as $testFile ){
            if( is_file($testFile)){
            unlink($testFile);
            }
        }
        
        
        foreach( $testFiles as $key => $file ){
            $tcr = new TestClassRecreator($file);
            $tcr->setSavePath($generatedTestsuitPath);
            foreach( $browsers as $browser ){
                $tcr->createFileForBrowser( $browser );
            }
        }
        
        
        $testFileIncluder = new TestFileIncluder($generatedTestsuitPath);
        $testFileIncluder->includeFiles();
        $includedFiles = $testFileIncluder->getInlcudedFiles();
        
        $classInstantiator = new ClassInstantiator($includedFiles);
        $this->_testClassNames = $classInstantiator->getClassNames();
        // get their classnames
        
    }
    
    
    
}