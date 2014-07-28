<?php

class HelpController extends Controller
{

    protected $_controllers;

    protected function setControllers()
    {
        $controllers = array();
        $controllerFiles = new ControllerFileScanner(CORE_SRC_PATH . DIRECTORY_SEPARATOR . 'controllers');
        $filenames = $controllerFiles->getFileNames();
        foreach ($filenames as $filename) {
            $controllers[$this->getCommand($filename)] = $filename;
        }
        $this->_controllers = $controllers;
    }

    protected function getControllers()
    {
        if( $this->_controllers === null ){
            $this->setControllers();
        }
        return $this->_controllers;
    }

    protected function getCommand($controllerName)
    {
        $controllerName = substr($controllerName, 0, strlen($controllerName) - 10);
        return preg_replace('/(^|[a-z])([A-Z])/e', 'strtolower(strlen("\\1") ? "\\1-\\2" : "\\2")', $controllerName);
    }

    public function run()
    {
        $echo = array();
        $echo[] = '';
        $echo[] = 'SeleniumShell usage: ';
        $echo[] = '';
        foreach ($this->getControllers() as $command => $controller) {
            $echo[] = $this->addWhitespaceToCommand($command) . $controller::getHelpDescription();
        }
        $echo[] = '';
        echo implode(PHP_EOL, array_map('HelpController::addSpace', $echo));
    }

    public function addTab($name)
    {
        return "\t" . $name;
    }

    protected function getLongestCommandName()
    {
        $longest = 0;
        foreach ($this->getControllers() as $command => $value) {
            if (strlen($command) > $longest) {
                $longest = strlen($command);
            }
        }
        return $longest;
    }
    
    protected function addSpace($name){
        return ' ' . $name;
    }

    public function addWhitespaceToCommand($command)
    {
        $longest = $this->getLongestCommandName();
        $whitespacedCommand = $command;
        for( $i = strlen($command); $i < $longest + 6; $i++ ){
           $whitespacedCommand .= ' '; 
        }
        return $whitespacedCommand;
    }

    public static function getHelpDescription()
    {
        return 'for this screen';
    }

}
