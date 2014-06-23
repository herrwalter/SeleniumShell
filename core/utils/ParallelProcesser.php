<?php

class ParallelProcesser
{

    private $_commands;
    private $_maxSessions;
    private $_processes = array();
    private $_finishedProcesses = array();
    private $_runtime;
    private $_nrOfCommands;
    private $_callbacks = array();
    private $_finished = false;

    public function __construct($maxSessions, array $commands)
    {
        $this->_maxSessions = $maxSessions;
        $this->_commands = $commands;
        $this->_nrOfCommands = count($this->_commands);
    }

    public function getFinishedProcesses()
    {
        return $this->getFinishedProcesses();
    }

    protected function notifyCallbacks($arguments)
    {
        if (!empty($this->_callbacks)) {
            foreach ($this->_callbacks as $callback) {
                call_user_func_array($callback, $arguments);
            }
        }
    }

    public function registerCallback($classInstance, $method)
    {
        $this->_callbacks[] = array($classInstance, $method);
    }

    public function runProcesses()
    {
        $starttime = microtime(true);
        while (true) {
            //loop commands to execute
            foreach ($this->_commands as $key => $command) {
                if (count($this->_processes) < $this->_maxSessions) {
                    // save process as a running process
                    $this->_processes[] = array(
                        'command' => new Process($command),
                        'test' => $command
                    );
                    // delete command so it won't be executed
                    unset($this->_commands[$key]);
                }
            }

            // loop running processes and check if one of them is done
            foreach ($this->_processes as $key => $process) {
                if (!$process['command']->isRunning()) {
                    $this->notifyCallbacks(array($process['command']));
                    //$testUpdates[$process['test']]->setExitcode($process['command']->getExitcode());
                    //writeTestProgress($testUpdates);
                    
                    // save process as a finished process
                    $this->_finishedProcesses[] = $process;
                    // unset is as a running process
                    unset($this->_processes[$key]);
                }
            }
            // we are done if the commands are out and when there are no more running processes.
            if (empty($this->_commands) && empty($this->_processes)) {
                break;
            }
        }
        // finally done.. save elapsed time
        $this->_runtime = microtime(true) - $starttime;
        $this->_finished = true;
    }

    public function finished()
    {
        return $this->_finished;
    }

    public function printTotalRuntime()
    {
        $runtimeInSeconds = number_format($this->_runtime, 2);
        $minutes = floor($runtimeInSeconds / 60);
        $seconds = $runtimeInSeconds % 60;
        echo PHP_EOL . 'Total Runtime: ' . $minutes . ' minutes ' . $seconds . ' seconds';
    }

}
