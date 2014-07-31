<?php

class Controller
{

    protected $findUnsupportedArguments = true;
    protected $validateMandatoryArguments = true;
    protected $validateMinNrOfArugments = true;
    protected $name = '';
    private $arguments;

    /** @var ConfigHandler */
    protected $_config;

    public function __construct($name = '')
    {
        $this->name = $name;
        $this->_config = new ConfigHandler(CORE_CONFIG_PATH . DIRECTORY_SEPARATOR . 'config.ini');
        $this->setArguments();
        $this->validateMandatoryArguments();
        $this->findUnsupportedArguments();
        $this->validateMinNrOfArguments();
    }

    private function setArguments()
    {
        $this->arguments = ArgvHandler::getArgumentValuesByArray($this->getCombinedArguments());
    }

    protected function getArguments()
    {
        return $this->arguments;
    }

    protected function getCombinedArguments()
    {
        return array_merge($this->getMandatoryArguments(), $this->getOptionalArguments());
    }
    

    private function findUnsupportedArguments()
    {
        if (!$this->findUnsupportedArguments) {
            return false;
        }
        $defined = $this->getCombinedArguments();
        $unsupported = ArgvHandler::getUnsupportedArguments($defined);
        $errors = array();
        foreach ($unsupported as $argument) {
            $errors[] = PHP_EOL . $argument . ' is not known as a argument for this command "' . $this->name . '"';
        }
        if (count($errors) > 0) {
            echo implode(PHP_EOL, $errors) . PHP_EOL;
        }
    }

    private function validateMandatoryArguments()
    {
        if (!$this->validateMandatoryArguments) {
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
    
    private function validateMinNrOfArguments(){
        if( !$this->validateMinNrOfArugments ){
            return;
        }
        
        if( count($this->getArguments()) < $this->getMinNrOfRequiredArguments() ){
            throw new ErrorException( 'Controller: ' . $this->name . ' expects'
                    . ' at least ' . $this->getMinNrOfRequiredArguments() .' arg'
                    . 'uments' . PHP_EOL );
        }
    }

    /**
     * @return array with arguments
     */
    public function getMandatoryArguments()
    {
        return array();
    }

    /**
     * @return array with optional arguments
     */
    public function getOptionalArguments()
    {
        return array();
    }

    /**
     * Set nr of required arguments to validate for.
     * @return int default 0
     */
    protected function getMinNrOfRequiredArguments()
    {
        return 0;
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
    public static function getHelpDescription()
    {
        return '';
    }

}
