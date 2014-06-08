<?php

class Application
{

    /** @var ConfigHandler */
    private $_config;

    /** @var SeleniumShell_TestSuite */
    private $_suite;

    /** @var SeleniumShell_TestSuite */
    private $_setupSuite;
    private $_sessionId;
    /** @var Project */
    private $_project;

    public function __construct()
    {
        $this->_setConfig();
        $this->_setSession();
        $this->_setProject();
        $this->_setEnvironmentConstant();
        $this->_setSuite();
    }
    
    private function _setSuite(){
        $sourceRewriter = new SourceRewriter($this->_project, new TestFileScanner(), new TestClassRecreator(), $this->_project->getPath() . '/testsuites' );
        $sourceFiles = $sourceRewriter->getSources();
        if( $this->_config->isParameterSet('--ss-testsuite') ){
            $filter = new TestSourcesFilter($sourceFiles);
            $sourceFiles = $filter->filterSourcesByClassName($this->_config->getParameter('--ss-testsuite'));
        }
        
        $suiteCreator = new SuiteCreator($sourceFiles);
        $this->_suite = $suiteCreator->getSuite();
    }

    private function _runBeforeProjectTests()
    {
        if ($this->_config->isParameterSet('--ss-setup-before-project')) {
            $test = $this->_setupSuite->run(new PHPUnit_Framework_TestResult());
            die();
        }
    }

    
    private function _setProject()
    {
        if ($this->_config->isParameterSet('--ss-project')) {
            $this->_project = new Project($this->_config->getParameter('--ss-project'), $this->_config);
        } else {
            $this->_project = new Project($this->_config->getAttribute('project'), $this->_config);
        }
    }

    private function _setSetupBeforeProjectsSuite()
    {
        $testsuiteInitiator = new SetupSuiteInitiator($this->_projects);
        $this->_setupSuite = $testsuiteInitiator->getTestSuite();
    }

    private function _setSession()
    {
        if ($this->_config->isParameterSet('--ss-session')) {
            $sessionId = $this->_config->getParameter('--ss-session');
            session_id($sessionId);
        } else {
            session_id(time() . rand(1, 123123));
        }
        $sessionFolder = GENERATED_TESTSUITES_PATH . session_id();
        if (!file_exists($sessionFolder)) {
            mkdir(GENERATED_TESTSUITES_PATH . session_id(), '0777');
        }
        if (!file_exists(GENERATED_SETUP_BEFORE_PROJECT_PATH . session_id())) {
            mkdir(GENERATED_SETUP_BEFORE_PROJECT_PATH . session_id(), '0777');
        }
        if (!file_exists(GENERATED_RESULTS_PATH . session_id())) {
            mkdir(GENERATED_RESULTS_PATH . session_id(), '0777');
        }
        if (!file_exists(GENERATED_DEBUG_PATH . session_id())) {
            mkdir(GENERATED_DEBUG_PATH . session_id(), '0777');
        }
        if (!file_exists(GENERATED_RESULTS_PATH . session_id() . DIRECTORY_SEPARATOR . 'results.txt')) {
            file_put_contents(GENERATED_RESULTS_PATH . session_id() . DIRECTORY_SEPARATOR . 'results.txt', PHP_EOL . "PHPUnit by Sebastian Bergmann. \nSeleniumShell by Wouter Wessendorp \n\n");
        }
    }

    /**
     * Set config from ini file in core/config/config.ini
     */
    private function _setConfig()
    {
        $this->_config = new ConfigHandler(CORE_CONFIG_PATH . '/config.ini', true);
    }


    public function getTestSuite()
    {
        return $this->_suite;
    }

    public function _setEnvironmentConstant()
    {
        /**
         * If --ss-project is set, we will only run that project, so we have to check
         * if we need to use that config for setting the project environment.
         */
        $projectConfig = null;
        $project = $this->_config->getParameter('--ss-project');
        if ($project) {
            $projectConfig = $this->_project->getConfig();
        }

        if ($this->_config->isParameterSet('--ss-env')) {
            define('SS_ENVIRONMENT', $this->_config->getParameter('--ss-env'));
        } else if ($projectConfig !== null && $projectConfig->getAttribute('project-environment')) {
            define('SS_ENVIRONMENT', $projectConfig->getAttribute('project-environment'));
        } else if ($this->_config->getAttribute('project-environment')) {
            define('SS_ENVIRONMENT', $this->_config->getAttribute('project-environment'));
        } else {
            define('SS_ENVIRONMENT', false);
        }
    }

    /**
     * Sets the testsuite
     * @param type $suite
     */
//    private function _setSuite()
//    {
//        if ($this->_config->isParameterSet('--ss-generate')) {
//            die('Only generated the files. ');
//        }
//
//        $testsuiteInitiator = new TestSuiteInitiator($this->_projects);
//
//        if ($this->_config->isParameterSet('--ss-print-tests')) {
//            $testsuite = $testsuiteInitiator->getTestSuite();
//            $testClases = $testsuite->tests();
//            foreach ($testClases as $testClass) {
//                $testMethods = $testClass->tests();
//                foreach ($testMethods as $testMethod) {
//                    if (get_class($testMethod) == 'PHPUnit_Framework_TestSuite_DataProvider') {
//                        $testMethodName = explode('::', $testMethod->getName());
//                        $data = PHPUnit_Util_Test::getProvidedData(
//                                        $testClass->getName(), $testMethodName[1]
//                        );
//                        foreach ($data as $key => $value) {
//                            echo $testClass->getName() . '::' . $testMethodName[1] . ' with data set #' . $key . PHP_EOL;
//                        }
//                    } else {
//                        echo $testClass->getName() . '::' . $testMethod->getName() . PHP_EOL;
//                    }
//                }
//            }
//            die();
//        }
//
//        $this->_suite = $testsuiteInitiator->getTestSuite();
//    }

}
