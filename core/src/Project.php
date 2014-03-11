<?php



class Project
{
    private $_name;
    
    private $_path;
    
    private $_testClassNames;
    
    private $_config;
    
    private $_testFiles;
    
    private $_browsers;
    
    public function __construct($projectName)
    {
        $this->_setProjectName($projectName);
        $this->_setProjectPath();
        $this->_setProjectConfig();
        $this->_setTestFiles();
        $this->_filterTestSuitesIfSeleniumShellParameterIsSet();
        $this->_setBrowserSettings();
        $this->_deleteGeneratedTestFiles();
        $this->_generateTestFilesForAllBrowsers();
        $this->_prepareProjectsTestClassNamesForSuite();
    }
    
    private function _setProjectName( $projectName )
    {
        $this->_name = $projectName;
    }
    
    private function _setProjectPath()
    {
        $path = PROJECTS_FOLDER . DIRECTORY_SEPARATOR . $this->_name;
        if( is_dir($path) ){
            $this->_path = $path;
        }else{
            throw new ErrorException('Project not found.');
        }
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
    
    private function _setTestFiles()
    {
        // first find the testfiles in this project.
        $testFileScanner = new TestFileScanner( $this->_path . '\testsuites');
        $this->_testFiles = $testFileScanner->getFilesInOneDimensionalArray();
    }
    
    private function _filterTestSuitesIfSeleniumShellParameterIsSet()
    
    {
        $config = new ConfigHandler();
        $testSuite = $config->isParameterSet('--ss-testsuite');
        if( $testSuite ){
            foreach( $this->_testFiles as $dir => $file ){
                $testSuite = $config->getParameter('--ss-testsuite');
                $pathInfo = pathinfo($file);
                $filename = $pathInfo['filename'];
                if( $filename === $testSuite  ){
                    $this->_testFiles = array( $dir => $file );
                    return;
                }
            }
        }
    }
    
    private function _setBrowserSettings()
    {// get default browsers
        $config = new ConfigHandler(CORE_CONFIG_PATH . '\config.ini');
        $browsers = $config->getAttribute('browsers');
        
        // overwrite browsers with project default
        $projectBrowserSettings = $this->_config->getAttribute('browsers');
        if( !empty($projectBrowserSettings)){
            $browsers = $projectBrowserSettings;
        }
        
        // overwrite browsers from phpunit paramter
        if( $config->isParameterSet('--ss-browsers') ){
            $browsers = $config->getParameter('--ss-browsers');
            $browsers = explode(', ', $browsers);
        }
        
        $this->_browsers = $browsers;
    }
    
    private function _generateTestFilesForAllBrowsers()
    {
        // for every file, create the new browser tests.
        foreach( $this->_testFiles as $key => $file ){
            $reader = new TestClassReader($file);
            
            //if ss-solo-run has been applied to one of the test. Then only this file should be processed.
            if( $reader->fileHasSolorunAnnotation() ){
                $this->_deleteGeneratedTestFiles();
                $this->_createBrowserTestsForTestFile($file);
                $this->_testFiles = array($file);
                break;
            }
            $this->_createBrowserTestsForTestFile($file);
        }
    }
    
    
    private function _prepareProjectsTestClassNamesForSuite()
    {
        // include the generated browsertest
        $testFileIncluder = new TestFileIncluder(GENERATED_TESTSUITES_PATH);
        $testFileIncluder->includeFiles();
        $includedFiles = $testFileIncluder->getInlcudedFiles();
        // Instantiate classes and save their classNames for test initialisation.
        $classInstantiator = new ClassInstantiator($includedFiles);
        $this->_testClassNames = $classInstantiator->getClassNames();
    }
    
    /**
     * deletes the Generated test files.
     */
    protected function _deleteGeneratedTestFiles(){
        // delete old testfiles
        $fileScanner = new FileScanner( GENERATED_TESTSUITES_PATH );
        $generatedTestFiles = $fileScanner->getFilesInOneDimensionalArray();
        foreach( $generatedTestFiles as $testFile ){
            if( is_file($testFile)){
                unlink($testFile);
            }
        }
    }
    
    protected function _createBrowserTestsForTestFile($file){
        $tcr = new TestClassRecreator($file);
        $tcr->setSavePath(GENERATED_TESTSUITES_PATH  . $this->_name. DIRECTORY_SEPARATOR);
        $tcr->setProjectName($this->_name);
        foreach( $this->_browsers as $browser ){
            $tcr->createFileForBrowser( $browser );
        }
    }
    
    
    
    
}