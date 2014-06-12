<?php

class Session
{

    protected $_id;

    public function __construct($additionalId = '')
    {
        $this->setId($additionalId);
        $this->createSessionPaths();
        $this->createSessionResultsFile();
    }
    
    public function getId()
    {
        return $this->_id;
    }

    protected function setId($id)
    {
        session_id( $id ); 
    }
    
    protected function createSessionResultsFile()
    {
        $resultsFile = SESSION_RESULTS_PATH . DIRECTORY_SEPARATOR . 'results.txt';
        if (!file_exists($resultsFile)) {
            file_put_contents($resultsFile, PHP_EOL . "PHPUnit by Sebastian Bergmann. \nSeleniumShell by Wouter Wessendorp \n\n");
        }
    }

    protected function createSessionPaths()
    {
        $paths = array(
            'SESSION_TESTSUITES_PATH' => GENERATED_TESTSUITES_PATH . session_id(),
            'SESSION_SETUP_BEFORE_PROJECT_PATH' => GENERATED_SETUP_BEFORE_PROJECT_PATH . session_id(),
            'SESSION_RESULTS_PATH' => GENERATED_RESULTS_PATH . session_id(),
            'SESSION_SCREENSHOTS_PATH' => GENERATED_SCREENSHOTS_PATH . session_id(),
            'SESSION_DEBUG_PATH' => GENERATED_DEBUG_PATH . session_id(),
            'SESSION_TESTSUITES_PATH' => GENERATED_TESTSUITES_PATH . session_id(),
        );
        new PathCreator( $paths );
    }
    

}
