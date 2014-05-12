<?php

class Process {

    public $cmd = '';
    public $descriptors = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('pipe', 'a')
        );
    public $pipes = null;
    public $desc = '';
    private $strt_tm = 0;
    public $resource = null;
	public $status = null;
    private $exitcode = null;

    function __construct($cmd = '', $desc = '')
    {
        $this->cmd = $cmd;
        $this->desc = $desc;

        $this->resource = proc_open($this->cmd, $this->descriptors, $this->pipes, null, null);

        $this->strt_tm = microtime(true);
    }

    public function isRunning()
    {
        $status = proc_get_status($this->resource);

        /**
         * proc_get_status will only pull valid exitcode one
         * time after process has ended, so cache the exitcode
         * if the process is finished and $exitcode is uninitialized
         */
        if ($status['running'] === false && $this->exitcode === null){
            $this->exitcode = $status['exitcode'];
			$this->status = $status;
		}

        return $status['running'];
    }

    public function getExitcode()
    {
        return $this->exitcode;
    }

    public function get_elapsed()
    {
        return microtime(TRUE) - $this->strt_tm;
    }
}




	$processes = array(
		new Process('selenium-shell --browsers chrome --host 127.0.0.1 --project ePw --testsuite EPW_ParaTest'),
		new Process('selenium-shell --browsers chrome --host 127.0.0.1 --project ePw --testsuite EPW_ParaFailTest'),
		new Process('selenium-shell --browsers chrome --host 127.0.0.1 --project ePw --testsuite EPW_ParaFailTest'),
		new Process('selenium-shell --browsers chrome --host 127.0.0.1 --project ePw --testsuite EPW_ParaTest')
	);

	$results = array();
	$runningProcesses = array();

	$i = 0;

	while( true ){
		$finished = 0;
		foreach($processes as $process){
			if( !$process->isRunning() ){
				$finished ++;
			}
		}
		if( $finished === count($processes) ){
			break;
		} else {
			$finished = 0;
		}
	}
	
	foreach($processes as $process){
		var_dump(stream_get_contents($process->pipes[1]));
		//var_dump(stream_get_contents($process->pipes[2]));
		//var_dump($process->status);
		var_dump($process->getExitcode());
	}
	

