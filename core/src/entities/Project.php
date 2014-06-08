<?php

class Project
{

    private $_name;
    private $_path;
    /** @var ConfigHandler */
    private $_config;
    private $_browsers = array();
    private $_coreConfig;
//    private $_testFiles;
//    private $_testClassNames;
//    private $_generatedFiles = array();
//    private $_setupBeforeProjectFiles = array();
//    private $_generatedSetupBeforeProjectFiles = array();
//    private $_setupBeforeProjectClassNames;

    public function __construct($projectName, ConfigHandler $coreConfig)
    {
        $this->_coreConfig = $coreConfig;
        $this->_setProjectName($projectName);
        $this->_setProjectPath();
        $this->_setProjectConfig();
        $this->_setBrowsers();
    }

    private function _setProjectName($projectName)
    {
        $this->_name = $projectName;
    }

    private function _setProjectPath()
    {
        // check if project path is set by project section config. 
        // else use the defaults path
        if( $this->_coreConfig->sectionExists('project-' . $this->_name) ){
            $path = $this->_coreConfig->getAttribute('path', 'project-' . $this->_name);
        } else {
            $path = PROJECTS_FOLDER . DIRECTORY_SEPARATOR . $this->_name;
        }
        
        if (is_dir($path)) {
            $this->_path = $path;
        } else {
            throw new ErrorException('Project not found. Checked the path: ' . $path);
        }
    }
    
    private function _setTestSuitesPath()
    {
        $this->_testSuitesPath = $this->_path . DIRECTORY_SEPARATOR . 'testsuites';
    }
    
    
    private function _setProjectConfig()
    {
        $this->_config = new ConfigHandler($this->_path . '\config\project.ini');
    }
    
    private function _setBrowsers()
    {
        $browsers = $this->_getDefinedBrowsers();
        foreach ($browsers as $browser) {
            $browserSection = 'browser-' . $browser;
            if( $this->_coreConfig->sectionExists($browserSection) ){
                $browserName = $this->_coreConfig->getAttribute('browserName', $browserSection);
                $version = $this->_coreConfig->getAttribute('version', $browserSection);
                $platform = $this->_coreConfig->getAttribute('platform', $browserSection);
                $this->_browsers[] = new Browser( $browser, $browserName, $version, $platform );
             }
        }
    }

    private function _getDefinedBrowsers()
    {
        // get default browsers
        $uniqueBrowserNames = array_unique($this->_coreConfig->getAttribute('browsers', 'core-config'));
        
        // overwrite browsers with project default
        $projectBrowserSettings = $this->_config->getAttribute('browsers');
        if (!empty($projectBrowserSettings)) {
            $uniqueBrowserNames = $projectBrowserSettings;
        }
        
        // overwrite browsers from phpunit paramter
        if ($this->_coreConfig->isParameterSet('--ss-browsers')) {
            $browsers = $this->_coreConfig->getParameter('--ss-browsers');
            $uniqueBrowserNames = array_map('trim', explode(',', $browsers));
        }

        return $uniqueBrowserNames;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getPath()
    {
        return $this->_path;
    }
    
    public function getBrowsers()
    {
        return $this->_browsers;
    }
    
    public function getConfig()
    {
        return $this->_config;
    }
    
//    public function getGeneratedFiles()
//    {
//        return $this->_generatedFiles;
//    }


//    public function getProjectsTestClassNames()
//    {
//        return $this->_testClassNames;
//    }

//    public function getSetupBeforeProjectClassNames()
//    {
//        return $this->_setupBeforeProjectClassNames;
//    }

    
//    private function _setTestFiles()
//    {
//        // first find the testfiles in this project.
//        $testFileScanner = new TestFileScanner($this->_path . '\testsuites');
//        $this->_testFiles = $testFileScanner->getFilesInOneDimensionalArray();
//    }
//
//    private function _filterTestSuitesIfSeleniumShellParameterIsSet()
//    {
//        $config = new ConfigHandler();
//        $testSuite = $config->isParameterSet('--ss-testsuite');
//        if ($testSuite) {
//            foreach ($this->_testFiles as $dir => $file) {
//                $testSuite = $config->getParameter('--ss-testsuite');
//                $pathInfo = pathinfo($file);
//                $filename = $pathInfo['filename'];
//                if ($filename === $testSuite) {
//                    $this->_testFiles = array($dir => $file);
//                    return;
//                }
//            }
//        }
//    }

    

//    private function _generateTestFilesBeforeProjectSetup()
//    {
//        foreach ($this->_setupBeforeProjectFiles as $key => $file) {
//            $reader = new TestClassReader($file);
//            if ($reader->fileHasBeforeProjectSetupAnnotation()) {
//                $this->_createSetupTestFile($file);
//            }
//        }
//    }
//
//    private function _generateTestFilesForAllBrowsers()
//    {
//        // for every file, create the new browser tests.
//        foreach ($this->_testFiles as $key => $file) {
//            $reader = new TestClassReader($file);
//
//            //if ss-solo-run has been applied to one of the test. Then only this file should be processed.
//            if ($reader->fileHasSolorunAnnotation()) {
//                //$this->_deleteGeneratedTestFiles();
//                $this->_createBrowserTestsForTestFile($file);
//                $this->_testFiles = array($file);
//                break;
//            }
//            $this->_createBrowserTestsForTestFile($file);
//        }
//    }
//
//    private function _prepareProjectsTestClassNamesForSuite()
//    {
//        // include the generated browsertest
//        foreach ($this->_generatedFiles as $file) {
//            require_once($file);
//        }
//        // Instantiate classes and save their classNames for test initialisation.
//        $classInstantiator = new ClassInstantiator($this->_generatedFiles);
//        $this->_testClassNames = $classInstantiator->getClassNames();
//    }
//
//    private function _prepareProjectsTestClassNamesForProjectSetup()
//    {
//        // include the generated browsertest
//        foreach ($this->_setupBeforeProjectFiles as $file) {
//            require_once($file);
//        }
//        // Instantiate classes and save their classNames for test initialisation.
//        $classInstantiator = new ClassInstantiator($this->_setupBeforeProjectFiles);
//        $this->_setupBeforeProjectClassNames = $classInstantiator->getClassNames();
//    }
//
//    /**
//     * deletes the Generated test files.
//     */
//    protected function _deleteGeneratedTestFiles()
//    {
//        // delete old testfiles
//        $fileScanner = new FileScanner(GENERATED_TESTSUITES_PATH . session_id());
//        $generatedTestFiles = $fileScanner->getFilesInOneDimensionalArray();
//        foreach ($generatedTestFiles as $testFile) {
//            if (is_file($testFile)) {
//                unlink($testFile);
//            }
//        }
//    }
//
//    protected function _createSetupTestFile($file)
//    {
//        $tcr = new SetupBeforeProjectTestClassRecreator($file);
//        $tcr->setSavePath(GENERATED_SETUP_BEFORE_PROJECT_PATH . session_id() . DIRECTORY_SEPARATOR . $this->_name . DIRECTORY_SEPARATOR);
//        $tcr->setProjectName($this->_name . '_setup_before_project_');
//        foreach ($this->_browsers as $browser) {
//            $this->_generatedSetupBeforeProjectFiles[] = $tcr->createFileForBrowser($browser);
//        }
//    }
//
//    protected function _createBrowserTestsForTestFile($file)
//    {
//        $tcr = new TestClassRecreator($file);
//        $tcr->setSavePath(GENERATED_TESTSUITES_PATH . session_id() . DIRECTORY_SEPARATOR . $this->_name . DIRECTORY_SEPARATOR);
//        $tcr->setProjectName($this->_name);
//        foreach ($this->_browsers as $browser) {
//            $this->_generatedFiles[] = $tcr->createFileForBrowser($browser);
//        }
//    }

}
