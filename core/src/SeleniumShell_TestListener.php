<?php

class SeleniumShell_TestListener implements PHPUnit_Framework_TestListener
{
    /**
     * @var Log 
     */
    protected $log;
    /**
     * @var Log 
     */
    protected $failLog;
    /**
     * @var Log 
     */
    protected $errorLog;
    /**
     * @var Log 
     */
    protected $skippedLog;
    /**
     * @var Log 
     */
    protected $assertions;
    /**
     * @var Log 
     */
    protected $incompleteLog;

    public function __construct()
    {
        $this->log = new Log(GENERATED_RESULTS_PATH . Session::getId() . DIRECTORY_SEPARATOR . 'results.txt');
        $this->failLog = new Log(GENERATED_RESULTS_PATH . Session::getId() . DIRECTORY_SEPARATOR . 'failures.txt' );
        $this->errorLog = new Log(GENERATED_RESULTS_PATH . Session::getId() . DIRECTORY_SEPARATOR . 'errors.txt' );
        $this->skippedLog = new Log(GENERATED_RESULTS_PATH . Session::getId() . DIRECTORY_SEPARATOR . 'skipped.txt' );
        $this->incompleteLog = new Log(GENERATED_RESULTS_PATH . Session::getId() . DIRECTORY_SEPARATOR . 'incompletes.txt' );
        $this->assertions = new Log(GENERATED_RESULTS_PATH . Session::getId() . DIRECTORY_SEPARATOR . 'assertions.txt' );
    }

    public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
        $this->errorLog->write( $test->getName() . ' got an error: ' . $test->getStatusMessage() . ' ' . $e->getTraceAsString() . PHP_EOL);
    }

    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->failLog->write( $test->getName() . ' failed: ' . $test->getStatusMessage()  . PHP_EOL . $test->getActualOutput() );
    }

    public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
        $this->incompleteLog->write( $test->getName() . ' incomplete: ' . $test->getStatusMessage() . PHP_EOL );
    }

    public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
        $this->skippedLog->write( $test->getName() . ' skipped: ' . $test->getStatusMessage() . PHP_EOL );
    }

    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        try{
            $this->saveScreenshot($test);
        } catch (Exception $ex) {
            DebugLog::write('Could not take screenshot: ' . $ex->getMessage());
        }
        $currentCount = intval($this->assertions->getLog());
        $newCount = $currentCount + $test->getCount();
        $this->assertions->overwrite($newCount);
        $this->log->write( $test->getStatusRepresentation() );
    }
    
    public function saveScreenshot(SeleniumShell_Test $test)
    {
        $session = $test->prepareSession();
        $screenshot = $session->currentScreenshot();
        file_put_contents(GENERATED_SCREENSHOTS_PATH . Session::getId() . DIRECTORY_SEPARATOR . $test->getBrowser() . $test->getName() . '.jpg', $screenshot );
    }

    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
    }

    public function startTest(\PHPUnit_Framework_Test $test)
    {
        
    }

    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        
    }

}
