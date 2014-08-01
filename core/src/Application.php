<?php

class Application
{

    /** @var ConfigHandler */
    private $_config;

    /** @var SeleniumShell_TestSuite */
    private $_suite;

    /** @var Session */
    protected $_session;

    /** @var Project */
    private $_project;

    public function __construct()
    {
        $this->_setConfig();
        $this->_setProject();
        $this->_setSession();
        $this->_setEnvironmentConstant();
        $this->_setSuite();
    }

    private function _setSuite()
    {

        if ($this->_config->isParameterSet('--ss-setup-before-project')) {
            $setupSourceRewriter = new SourceRewriter($this->_project, new TestFileScanner, new SetupBeforeProjectTestClassRecreator(), $this->_project->getPath() . DIRECTORY_SEPARATOR . 'setup-before-project');
            $setupSources = $setupSourceRewriter->getSources();
            $setupSuite = new SuiteCreator($setupSources);
            $setupSuite->getSuite()->run(new PHPUnit_Framework_TestResult());
            die();
        }

        $sourceRewriter = new SourceRewriter($this->_project, new TestFileScanner(), new TestClassRecreator(), $this->_project->getPath() . '/testsuites' . $this->getTestsuitesSubPaths());
        $sourceFiles = $sourceRewriter->getSources();
        if ($this->_config->isParameterSet('--ss-testsuite')) {
            $filter = new TestSourcesFilter($sourceFiles);
            $sourceFiles = $filter->filterSourcesByClassName($this->_config->getParameter('--ss-testsuite'));
        }


        $suiteCreator = new SuiteCreator($sourceFiles);

        $this->_suite = $suiteCreator->getSuite();

        if ($this->_config->isParameterSet('--ss-print-tests')) {
            $this->printGeneratedTests();
            exit(0);
        }
    }

    /**
     * @return string Sub path with prepending directory seperator
     */
    public function getTestsuitesSubPaths()
    {
        if (!$this->_config->isParameterSet('--ss-subpath')) {
            return;
        }
        $subpath = $this->_config->getParameter('--ss-subpath');
        if ($subpath && in_array($subpath[0], array('/', '\\'))) {
            return $subpath;
        } elseif ($subpath) {
            return DIRECTORY_SEPARATOR . $subpath;
        }
        return '';
    }

    public function printGeneratedTests()
    {
        $testClases = $this->_suite->tests();
        foreach ($testClases as $testClass) {
            $testMethods = $testClass->tests();
            foreach ($testMethods as $testMethod) {
                if (get_class($testMethod) == 'PHPUnit_Framework_TestSuite_DataProvider') {
                    $testMethodName = explode('::', $testMethod->getName());
                    $data = PHPUnit_Util_Test::getProvidedData(
                            $testClass->getName(), $testMethodName[1]
                    );
                    foreach ($data as $key => $value) {
                        echo $testClass->getName() . '::' . $testMethodName[1] . ' with data set #' . $key . PHP_EOL;
                    }
                } else {
                    echo $testClass->getName() . '::' . $testMethod->getName() . PHP_EOL;
                }
            }
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

    private function _setSession()
    {
        if ($this->_config->isParameterSet('--ss-session')) {
            $sessionId = $this->_config->getParameter('--ss-session');

            DebugLog::write($sessionId);
            Session::setId($sessionId);
        } else {
            Session::setId(time() . '-' . $this->_project->getName());
        }
    }

    private function _setConfig()
    {
        $this->_config = new ConfigHandler(CORE_CONFIG_PATH . '/config.ini', true);
    }

    public function getTestSuite()
    {
        return $this->_suite;
    }

    /**
     * If --ss-project is set, we will only run that project, so we have to check
     * if we need to use that config for setting the project environment.
     */
    public function _setEnvironmentConstant()
    {
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

}
