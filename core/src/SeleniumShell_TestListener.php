<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
    protected $incompleteLog;

    public function __construct()
    {
        $this->log = new Log(GENERATED_RESULTS_PATH . session_id() . DIRECTORY_SEPARATOR . 'results.txt');
        $this->failLog = new Log(GENERATED_RESULTS_PATH . session_id() . DIRECTORY_SEPARATOR . 'failures.txt' );
        $this->errorLog = new Log(GENERATED_RESULTS_PATH . session_id() . DIRECTORY_SEPARATOR . 'errors.txt' );
        $this->skippedLog = new Log(GENERATED_RESULTS_PATH . session_id() . DIRECTORY_SEPARATOR . 'skipped.txt' );
        $this->incompleteLog = new Log(GENERATED_RESULTS_PATH . session_id() . DIRECTORY_SEPARATOR . 'incompletes.txt' );
    }

    public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
        $this->errorLog->write( $test->getName() . ' got an error: ' . $test->getStatusMessage() . ' ' . $e->getTraceAsString() . PHP_EOL);
    }

    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->failLog->write( $test->getName() . ' failed: ' . $test->getStatusMessage() . PHP_EOL );
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
        $this->log->write( $test->getStatusRepresentation() );
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
