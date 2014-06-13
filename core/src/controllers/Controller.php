<?php

class Controller
{

    protected $_findUnsupportedArguments = true;
    protected $_validateMandatoryArguments = true;
    protected $_name = '';
    protected $_arguments;

    /** @var ConfigHandler */
    protected $_config;

    public function __construct($name = '')
    {
        $this->_name = $name;
        $this->_config = new ConfigHandler(CORE_CONFIG_PATH . DIRECTORY_SEPARATOR . 'config.ini');
        $this->setArguments();
        $this->_validateMandatoryArguments();
        $this->_findUnsupportedArguments();
    }

    protected function setArguments()
    {
        $this->_arguments = ArgvHandler::getArgmentValuesByArray($this->getCombinedArguments());
    }

    protected function getArguments()
    {
        return $this->_arguments;
    }

    protected function getCombinedArguments()
    {
        return array_merge($this->getMandatoryArguments(), $this->getOptionalArguments());
    }

    private function _findUnsupportedArguments()
    {
        if (!$this->_findUnsupportedArguments) {
            return false;
        }
        $defined = $this->getCombinedArguments();
        $unsupported = ArgvHandler::getUnsupportedArguments($defined);
        $errors = array();
        foreach ($unsupported as $argument) {
            $errors[] = PHP_EOL . $argument . ' is not known as a argument for this command "' . $this->_name . '"';
        }
        if (count($errors) > 0) {
            echo implode(PHP_EOL, $errors) . PHP_EOL;
        }
    }

    private function _validateMandatoryArguments()
    {
        if (!$this->_validateMandatoryArguments) {
            return false;
        }
        $mandatoryArguments = $this->getMandatoryArguments();
        $errors = array();
        foreach ($mandatoryArguments as $argument) {
            if (!ArgvHandler::getArgumentValue($argument)) {
                $errors[] = $argument . ' is not provided and is mandatory';
            }
        }
        if (count($errors) > 0) {
            echo implode(PHP_EOL, $errors) . PHP_EOL;
            die();
        }
    }

    /**
     * @return array with arguments
     */
    public function getMandatoryArguments()
    {
        
    }

    /**
     * @return array with optional arguments
     */
    public function getOptionalArguments()
    {
        
    }

    /**
     * Main method of controller to run. 
     */
    public function run()
    {
        
    }

    /**
     * @return string
     */
    public function getHelpDescription()
    {
        
    }

}
