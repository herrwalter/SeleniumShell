<?php

class DefaultController extends Controller
{
    protected $_exitCode = 0;
    protected $_maxSessions = 10;
    /** @var ParallelProcesser */
    protected $_parallelProcesser;
    protected $_namespacedArguments = '';
    protected $_progressPath = '';
    protected $_resultsPath = '';
    protected $_testCommands = array();
    protected $_testNames = array();
    protected $_testUpdates = array();
    protected $_webinterfacePath = '';

    public function getOptionalArguments()
    {
        return array('-env', '-host', '-port', '-browsers', '-max-sessions', '-testsuite', '-session', '--ignore-solo-run');
    }

    public function getMandatoryArguments()
    {
        return array('-project');
    }

    public static function getHelpDescription()
    {
        return 'running tests';
    }

    public function run()
    {
        $this->_setNamespacedArguments();
        $this->_setResultsPath();
        $this->_setProgressPath();
        $this->_setWebinterfacePath();
        $this->_setMaxSessions();
        $this->_echoStartupMessage();
        $this->_runBeforeProjectTests();
        $this->_setTestNames();
        $this->_setTestUpdates();
        $this->_showWebinterface();
        $this->_createTestCommands();
        $this->_runParallelTests();
    }
    
    public function finished()
    {
        return $this->_parallelProcesser->finished();
    }
    
    public function finishedProcess( Process $process )
    {
        // update test result to our testUpdates array.
        $testName = $this->getTestNameFromCommand($process->getCommand());
        $this->_testUpdates[$testName]->setExitcode( $process->getExitcode() );
        $this->_writeTestProgress();
        $this->setExitcode( $process->getExitcode() );
        $process->closeProcess();
    }
    
    protected function setExitcode( $code )
    {
        if( (int) $code > $this->_exitCode ){
            $this->_exitCode = (int) $code;
        }
    }
    
    public function getTestNameFromCommand($command){
        preg_match('/\-\-filter\s"(.*?)"/', $command, $matches);
        return $matches[1];
    }
    
    public function getExitCode()
    {
        return $this->_exitCode;
    }
    
    public function printResults()
    {
        $results = file_get_contents($this->_resultsPath . 'results.txt');
        $errors = file_get_contents($this->_resultsPath  . 'errors.txt');
        $failures = file_get_contents($this->_resultsPath  . 'failures.txt');
        $incompletes = file_get_contents($this->_resultsPath  . 'incompletes.txt');
        $skipped = file_get_contents($this->_resultsPath  . 'skipped.txt');
        $extraInfo = array('Errors' => $errors, 'Failures' => $failures, 'Incompletes' => $incompletes, 'Skipped' => $skipped);
        echo $results . PHP_EOL;
        foreach ($extraInfo as $message => $info) {
            if ($info !== '') {
                echo PHP_EOL;
                echo $message . ': ' . PHP_EOL . PHP_EOL;
                echo $info . PHP_EOL;
            }
        }
        echo $this->_parallelProcesser->printTotalRuntime();
    }
    
    protected function _createTestCommands()
    {
        foreach($this->_testNames as $test){
            $this->_testCommands[$test] = 'phpunit --filter "' . $test . '" SeleniumShell.php --ss-session "' . Session::getId() . '" ' . $this->_namespacedArguments;
        }
    }

    protected function _echoStartupMessage()
    {
        echo PHP_EOL;
        echo 'Executing SeleniumShell with options: ' . $this->_namespacedArguments. PHP_EOL;
        echo 'Sessionname=' . Session::getId() . PHP_EOL;
        echo PHP_EOL;
    }

    protected function _runBeforeProjectTests()
    {
        $setupBeforeProject = new Process('phpunit -v  SeleniumShell.php ' . $this->_namespacedArguments . ' --ss-setup-before-project true');
        // wait for process to end..
        while ($setupBeforeProject->isRunning()) {}
    }
    
    protected function _runParallelTests()
    {
        $this->_parallelProcesser = new ParallelProcesser($this->_maxSessions, $this->_testCommands);
        $this->_parallelProcesser->registerCallback($this, 'finishedProcess');
        $this->_parallelProcesser->runProcesses();
    }
    
    protected function _setMaxSessions()
    {
        if( ArgvHandler::getArgumentValue('-max-sessions') ){
            $this->_maxSessions = ArgvHandler::getArgumentValue('-max-sessions');
        }
    }

    protected function _setNamespacedArguments()
    {
        foreach ($this->getArguments() as $switch => $value) {
            $this->_namespacedArguments .= '--ss' . $switch . ' "' . $value . '" ';
        }
    }

    protected function _setProgressPath()
    {
        $this->_progressPath = $this->_resultsPath . 'progress.html';
    }

    protected function _setResultsPath()
    {
        $this->_resultsPath = GENERATED_RESULTS_PATH . Session::getId() . DIRECTORY_SEPARATOR;
    }

    protected function _setTestNames()
    {
        exec('phpunit SeleniumShell.php ' . $this->_namespacedArguments . ' --ss-print-tests true --ss-session "' . Session::getId() . '"', $tests, $exitcode);
        $this->_testNames = $tests;
    }
    
    protected function _setTestUpdates()
    {
        foreach($this->_testNames as $test){
            $this->_testUpdates[$test] = new HtmlTestResult($test);
        }
        $this->_writeTestProgress();
    }
    
    protected function _setWebinterfacePath()
    {
        $this->_webinterfacePath = SELENIUM_SHELL . 'webinterface' . DIRECTORY_SEPARATOR . 'interface.html#progressPath=' . $this->_progressPath;
    }
    
    protected function _showWebinterface()
    {
        exec('openbrowser.vbs ' . $this->_webinterfacePath);
    }

    protected function _writeTestProgress()
    {
        $begin = '<html><head></head><body>';
        $results = '';
        $end = '</body></html>';
        foreach ($this->_testUpdates as $htmlTestResult) {
            $results .= $htmlTestResult->toString();
        }
        file_put_contents($this->_progressPath, $begin . $results . $end);
    }

}
