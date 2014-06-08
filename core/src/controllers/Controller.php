<?php

abstract class Controller
{

    protected $_validateArgumentCount = true;
    protected $_findUnsupportedArguments = true;
    
    /** @var ConfigHandler */
    protected $_config;

    public function __construct()
    {
        $this->_config = new ConfigHandler(CORE_CONFIG_PATH . DIRECTORY_SEPARATOR . 'config.ini');
        $this->validateArgumentCount();
        $this->findUnsupportedArguments();
        $this->run();
    }
    
    
        
    private function findUnsupportedArguments()
    {
        if( !$this->_findUnsupportedArguments ){
            return false;
        }
        $arguments = $this->getArguments();
        $defined = $this->_config->getParameters();
        foreach( $arguments as $argument => $value )
        {
            if( !array_key_exists($argument, $defined) ){
                echo PHP_EOL . $argument . ' is not known as an defined argument';
            }
        }
       
    }

    private function validateArgumentCount()
    {
        if( !$this->_validateArgumentCount ){
            return false;
        }
        global $argc;
        if( count($this->getArguments()) !== $argc ){
            throw new ErrorException('Action expects ' . count($this->getArguments()) . ' arguments' );
        }
    }

    /**
     * @return array with arguments
     */
    abstract public function getArguments();

    /**
     * Main method of controller to run. 
     */
    abstract public function run();
}
