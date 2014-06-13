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
    
    public function __construct( $maxSessions, array $commands ){
        $this->_maxSessions = $maxSessions;
        $this->_nrOfCommands = count($this->_commands);
        $this->_commands = $commands;
    }
    
    public function getFinishedProcesses()
    {
        return $this->getFinishedProcesses();
    }
    
    protected function notifyCallbacks( $arguments )
    {
        if( !empty($this->_callbacks) ){
            foreach($this->_callbacks as $callback){
                call_user_func_array($callback, $arguments);
            }
        }
    }
    
    public function registerCallback($classInstance, $method){
        $this->_callbacks[] = array($classInstance, $method);
    }
    
    
    public function runProcesses()
    {
        $starttime = microtime(true);
        $running = 0;
        while (true) {
            if ($running < $this->_maxSessions) {
                $command = array_shift($this->_commands);
                $this->_processes[] = array('process'=> new Process($command), 'notified' => false);
                $running++;
            }
            foreach ($this->_processes as $key => $process) {
                if ($process['process']->isRunning()) {
                    if(!$process['notified']){
                        $this->notifyCallbacks( array('process' => $process['process']) );
                        $process['notified'] = true;
                        $running--;
                        $this->_finishedProcesses[] = $process;
                    }
                    unset($this->_processes[$key]);
                }
            }
            if (count($this->_finishedProcesses) == $this->_nrOfCommands ) {
                $this->_finished = true;
                break;
            } else {
                $this->_finishedProcesses = array();
            }
        }
        $this->_runtime = microtime(true) - $starttime;
    }
    
    public function finished(){
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