<?php

abstract class Controller
{

    protected $_validateArgumentCount = true;
    protected $_findUnsupportedArguments = true;
    protected $_name = '';
    protected $_userOptions = array();
    
    /** @var ConfigHandler */
    protected $_config;

    public function __construct( $name ='' , $options = array() )
    {
        $this->_name = $name;
        $this->_userOptions = $options;
        $this->_config = new ConfigHandler(CORE_CONFIG_PATH . DIRECTORY_SEPARATOR . 'config.ini');
        $this->validateArgumentCount();
        $this->findUnsupportedArguments();
    }
        
    private function findUnsupportedArguments()
    {
        if( !$this->_findUnsupportedArguments ){
            return false;
        }
        $arguments = $this->_userOptions;
        $defined = $this->getMandatoryArguments();
        foreach( $arguments as $argument => $value ){
            if( !in_array($argument, $defined) ){
                echo PHP_EOL . $argument . ' is not known as a mandatory argument';
            }
        }
       
    }

    private function validateArgumentCount()
    {
        if( !$this->_validateArgumentCount ){
            return false;
        }
        global $argc;
        if( count($this->getMandatoryArguments()) !== $argc - 2 ){
            throw new ErrorException('Command expects ' . count($this->getMandatoryArguments()) . ' arguments' );
        }
    }

    /**
     * @return array with arguments
     */
    abstract public function getMandatoryArguments();
    

    /**
     * Main method of controller to run. 
     */
    abstract public function run();
    
    /**
     * @return string
     */
    abstract public function getHelpDescription();
    
    
    
}
