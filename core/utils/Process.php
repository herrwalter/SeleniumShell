<?php


class Process
{

    protected $cmd = '';
    protected $descriptors = array(
        0 => array('pipe', 'r'),
        1 => array('pipe', 'w'),
        2 => array('pipe', 'a')
    );
    public $pipes = null;
    protected $desc = '';
    private $strt_tm = 0;
    protected $resource = null;
    protected $status = null;
    private $exitcode = null;

    function __construct($cmd = '', $desc = '')
    {
        $this->cmd = $cmd;
        $this->desc = $desc;
        $this->resource = proc_open($this->cmd, $this->descriptors, $this->pipes, null, null);
        $this->strt_tm = microtime(true);
    }
    
    public function closeProcess()
    {
        foreach($this->pipes as $pipe){
            fclose($pipe);
        }
        proc_close($this->resource);
    }

    public function isRunning()
    {
        $status = proc_get_status($this->resource);
        /**
         * proc_get_status will only pull valid exitcode one
         * time after process has ended, so cache the exitcode
         * if the process is finished and $exitcode is uninitialized
         */
        if ($status['running'] === false && $this->exitcode === null) {
            $this->exitcode = $status['exitcode'];
            $this->status = $status;
        }

        return $status['running'];
    }

    public function getExitcode()
    {
        return $this->exitcode;
    }
    
    public function getCommand()
    {
        return $this->cmd;
    }

}